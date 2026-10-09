<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Setting;
use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicFooterTest extends TestCase
{
    use RefreshDatabase;

    private function footer(string $path = '/'): \DOMXPath
    {
        $this->withoutVite();
        $response = $this->get($path)->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$response->getContent());

        return new \DOMXPath($dom);
    }

    public function test_shared_footer_preserves_navigation_categories_contacts_and_attribution(): void
    {
        $this->seed(CatalogSeeder::class);
        Category::factory()->create(['name' => 'Private Category', 'status' => 'draft']);
        $settings = Setting::values();
        foreach (['/', '/tentang-kami', '/produk', '/brand', '/industri', '/kontak', '/privasi'] as $path) {
            $xpath = $this->footer($path);
            $this->assertSame(1, $xpath->query('//footer')->length);
            $footerText = $xpath->query('//footer')->item(0)->textContent;
            foreach (['company_name', 'tagline', 'profile', 'phone', 'email', 'sales_email', 'address'] as $key) {
                $this->assertStringContainsString($settings[$key], $footerText);
            }
            $this->assertStringContainsString('2026 Fandy Alfarizi Syafril.', $footerText);
            $this->assertStringNotContainsString('Private Category', $footerText);
            $links = [];
            foreach ($xpath->query('//footer//a') as $link) $links[] = $link->getAttribute('href');
            foreach (['home', 'about', 'products.index', 'brands.index', 'industries.index', 'contact', 'privacy'] as $route) {
                $this->assertContains(route($route), $links);
            }
            $categoryLinks = $xpath->query('//footer//nav[@aria-label="Kategori produk"]/a');
            $this->assertSame(7, $categoryLinks->length);
            foreach (Category::published()->ordered()->get() as $index => $category) {
                $this->assertSame($category->name, $categoryLinks->item($index)->textContent);
                $this->assertSame(route('products.category', $category->slug), $categoryLinks->item($index)->getAttribute('href'));
            }
            $this->assertSame(1, $xpath->query('//footer//a[starts-with(@href,"https://wa.me/")]')->length);
            $this->assertContains('tel:+6281266723815', $links);
            $this->assertContains('mailto:'.$settings['email'], $links);
            $this->assertContains('mailto:'.$settings['sales_email'], $links);
        }
    }

    public function test_footer_uses_updated_settings_and_preserves_optional_links_with_safe_escaping(): void
    {
        Setting::create(['id' => 1, 'data' => [
            'company_name' => 'SMART & Company', 'profile' => '<script>alert(1)</script>',
            'phone' => '+62 (800) 123-456', 'email' => 'office@example.com',
            'sales_email' => 'sales@example.com', 'address' => 'Alamat kantor terbaru',
            'whatsapp_number' => '62800123456', 'map_url' => 'https://maps.google.com/?q=Pekanbaru',
            'legal_information' => 'Informasi legal perusahaan',
        ]]);
        $xpath = $this->footer();
        $this->assertSame(0, $xpath->query('//footer//script')->length);
        $links = [];
        foreach ($xpath->query('//footer//a') as $link) $links[] = $link->getAttribute('href');
        foreach (['tel:+62800123456', 'mailto:office@example.com', 'mailto:sales@example.com', 'https://wa.me/62800123456', 'https://maps.google.com/?q=Pekanbaru', route('about').'#legal'] as $url) {
            $this->assertContains($url, $links);
        }
        foreach ($xpath->query('//footer//a[@target="_blank"]') as $link) {
            $this->assertSame('noopener noreferrer', $link->getAttribute('rel'));
        }
        $this->assertStringContainsString('SMART & Company', $xpath->query('//footer')->item(0)->textContent);
        $this->assertStringContainsString('Alamat kantor terbaru', $xpath->query('//footer')->item(0)->textContent);
    }

    public function test_optional_fields_and_empty_categories_do_not_create_dead_links_and_login_is_untouched(): void
    {
        Setting::create(['id' => 1, 'data' => ['sales_email' => '', 'whatsapp_number' => '', 'map_url' => '', 'legal_information' => '']]);
        $xpath = $this->footer();
        $this->assertSame(0, $xpath->query('//footer//a[@target="_blank"]')->length);
        $this->assertSame(0, $xpath->query('//footer//nav[@aria-label="Kategori produk"]')->length);
        $this->assertSame(0, $xpath->query('//footer//a[@href="" or @href="#"]')->length);
        $this->get('/admin/login')->assertOk()->assertDontSee('smart-footer-main')->assertDontSee('<footer', false);
    }
}
