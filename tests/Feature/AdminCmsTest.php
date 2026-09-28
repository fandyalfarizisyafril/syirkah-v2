<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Industry;
use App\Models\Inquiry;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCmsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    private function user(string $role = 'administrator'): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_admin_requires_login_and_server_side_permissions(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/inquiries')->assertRedirect('/admin/login');
        $this->actingAs($this->user('none'))->get('/admin')->assertForbidden();
        $this->actingAs($this->user('sales'))->get('/admin/catalog/products')->assertForbidden();
        $this->get('/admin/settings')->assertForbidden();
        $this->get('/admin/inquiries')->assertOk();
        $this->actingAs($this->user('editor'))->get('/admin/inquiries')->assertForbidden();
        $this->post('/admin/catalog/categories', ['name' => 'X'])->assertSessionHasErrors();
        $this->put('/admin/settings', [])->assertForbidden();
    }

    public function test_login_logout_and_non_staff_rejection(): void
    {
        $user = User::factory()->create(['role' => 'administrator', 'password' => 'SecureTestPassword1!']);
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'wrong'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'SecureTestPassword1!'])->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
        $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertGuest();
        $user->forceFill(['role' => 'none'])->save();
        $this->post('/admin/login', ['email' => $user->email, 'password' => 'SecureTestPassword1!'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_staff_can_change_own_password_with_current_password(): void
    {
        $user = User::factory()->create(['role' => 'sales', 'password' => 'InitialPassword12']);
        $this->actingAs($user)->get('/admin/account')->assertOk();
        $data = ['current_password' => 'wrong', 'password' => 'NewPassword12345', 'password_confirmation' => 'NewPassword12345'];
        $this->put('/admin/account', $data)->assertSessionHasErrors('current_password');
        $data['current_password'] = 'InitialPassword12';
        $this->put('/admin/account', $data)->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewPassword12345', $user->fresh()->password));
    }

    public function test_cms_crud_for_category_brand_and_industry(): void
    {
        $this->actingAs($this->user());
        foreach (['categories' => Category::class, 'brands' => Brand::class, 'industries' => Industry::class] as $module => $model) {
            $base = '/admin/catalog/'.$module;
            $this->get($base)->assertOk();
            $this->get($base.'/create')->assertOk();
            $data = ['name' => 'New '.$module, 'slug' => 'new-'.$module, 'status' => 'published', 'sort_order' => 0];
            $this->post($base, $data)->assertRedirect($base);
            $entry = $model::firstOrFail();
            $this->get($base.'/'.$entry->id.'/edit')->assertOk();
            $this->put($base.'/'.$entry->id, array_replace($data, ['name' => 'Updated', 'status' => 'draft']))->assertRedirect($base);
            $this->assertSame('draft', $entry->fresh()->status);
            $this->delete($base.'/'.$entry->id)->assertRedirect($base);
            $this->assertDatabaseMissing($module, ['id' => $entry->id]);
        }
    }

    public function test_product_crud_uploads_relations_and_flexible_specs(): void
    {
        Storage::fake('public');
        $this->actingAs($this->user());
        $category = Category::factory()->create();
        $brand = Brand::factory()->create();
        $industry = Industry::factory()->create();
        $data = ['name' => 'Motor 15', 'slug' => 'motor-15', 'category_id' => $category->id, 'brand_id' => $brand->id, 'short_description' => 'Industrial motor', 'status' => 'published', 'sort_order' => 0,
            'industry_ids' => [$industry->id], 'specifications' => [['label' => 'Power', 'value' => '15 kW']], 'benefits_text' => "Efficient\nReliable",
            'image' => UploadedFile::fake()->createWithContent('motor.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAusB9Wl6cS8AAAAASUVORK5CYII=')), 'datasheet_file' => UploadedFile::fake()->createWithContent('sheet.pdf', "%PDF-1.4\n1 0 obj\n<< /Type /Catalog >>\nendobj\n%%EOF")];
        $this->post('/admin/catalog/products', $data)->assertSessionHasNoErrors()->assertRedirect('/admin/catalog/products');
        $product = Product::firstOrFail();
        $this->assertSame(['Efficient', 'Reliable'], $product->benefits);
        $this->assertTrue($product->industries->contains($industry));
        Storage::disk('public')->assertExists([$product->image, $product->datasheet_file]);
        $this->get('/admin/catalog/products')->assertOk();
        $this->get('/admin/catalog/products/'.$product->id.'/edit')->assertOk();
        unset($data['image'], $data['datasheet_file']);
        $data['name'] = 'Updated Motor';
        $this->put('/admin/catalog/products/'.$product->id, $data)->assertSessionHasNoErrors();
        $this->assertSame('Updated Motor', $product->fresh()->name);
        $this->delete('/admin/catalog/categories/'.$category->id)->assertSessionHasErrors('delete');
        $this->delete('/admin/catalog/brands/'.$brand->id)->assertSessionHasErrors('delete');
        $this->delete('/admin/catalog/products/'.$product->id)->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_invalid_upload_and_dangerous_brand_url_are_rejected(): void
    {
        $this->actingAs($this->user());
        $data = ['name' => 'Invalid', 'slug' => 'invalid', 'status' => 'draft', 'sort_order' => 0];
        $this->post('/admin/catalog/categories', $data + ['image' => UploadedFile::fake()->create('script.php', 1, 'text/plain')])->assertSessionHasErrors('image');
        $this->post('/admin/catalog/brands', $data + ['website_url' => 'javascript:alert(1)'])->assertSessionHasErrors('website_url');
    }

    public function test_industry_editor_can_attach_and_detach_products(): void
    {
        $product = Product::factory()->create();
        $industry = Industry::factory()->create();
        $this->actingAs($this->user('editor'));
        $path = '/admin/catalog/industries/'.$industry->id;
        $this->get($path.'/edit')->assertOk()->assertSee($product->name);
        $data = ['name' => $industry->name, 'slug' => $industry->slug, 'status' => 'published', 'sort_order' => 0, 'product_ids' => [$product->id]];
        $this->put($path, $data)->assertSessionHasNoErrors();
        $this->assertTrue($industry->fresh()->products->contains($product));
        unset($data['product_ids']);
        $this->put($path, $data)->assertSessionHasNoErrors();
        $this->assertCount(0, $industry->fresh()->products);
    }

    public function test_settings_and_inquiry_workflow(): void
    {
        $this->actingAs($this->user());
        $this->get('/admin')->assertOk();
        $this->get('/admin/settings')->assertOk();
        $data = array_replace(Setting::defaults(), ['company_name' => 'Updated Artomoro']);
        $this->put('/admin/settings', $data)->assertSessionHasNoErrors();
        $this->get('/tentang-kami')->assertSee('Updated Artomoro');
        $inquiry = Inquiry::create(['reference_number' => 'ART-TEST', 'name' => 'Buyer', 'company' => 'Example', 'email' => 'a@example.test', 'phone' => '08123456789', 'message' => 'Equipment request', 'consent_at' => now()]);
        $this->get('/admin/inquiries')->assertOk()->assertSee('ART-TEST');
        $this->get('/admin/inquiries/'.$inquiry->id)->assertOk();
        $this->put('/admin/inquiries/'.$inquiry->id, ['status' => 'qualified', 'internal_notes' => 'Follow up tomorrow'])->assertSessionHasNoErrors();
        $this->assertSame('qualified', $inquiry->fresh()->status);
        $this->get('/inquiries/'.$inquiry->id)->assertNotFound();
    }
}
