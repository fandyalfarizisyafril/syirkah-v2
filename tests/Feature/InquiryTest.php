<?php

namespace Tests\Feature;

use App\Mail\InquiryReceived;
use App\Models\Inquiry;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InquiryTest extends TestCase
{
    use RefreshDatabase;

    private function payload(): array
    {
        return ['name' => 'Procurement User', 'company' => 'Example Manufacturing', 'email' => 'buyer@example.test', 'phone' => '+628123456789', 'message' => 'Mohon informasi untuk kebutuhan pompa industri.', 'consent' => '1', 'source_page' => '/kontak'];
    }

    public function test_valid_inquiry_is_saved_before_mail_and_has_product_context(): void
    {
        Mail::fake();
        $product = Product::factory()->create();
        $this->post('/inquiry', $this->payload() + ['product_id' => $product->id])->assertRedirect('/kontak')->assertSessionHas('inquiry_reference');
        $inquiry = Inquiry::firstOrFail();
        $this->assertSame($product->id, $inquiry->product_id);
        $this->assertNotNull($inquiry->consent_at);
        Mail::assertSent(InquiryReceived::class, fn ($mail) => $mail->inquiry->exists && $mail->inquiry->id === $inquiry->id);
    }

    public function test_email_failure_does_not_lose_inquiry_or_show_server_error(): void
    {
        Mail::shouldReceive('to')->once()->andReturnSelf();
        Mail::shouldReceive('send')->once()->andThrow(new \RuntimeException('SMTP unavailable'));
        $this->post('/inquiry', $this->payload())->assertRedirect('/kontak')->assertSessionHas('inquiry_reference');
        $this->assertDatabaseCount('inquiries', 1);
        $this->assertNull(Inquiry::first()->notified_at);
    }

    public function test_required_fields_consent_honeypot_and_product_visibility_are_validated(): void
    {
        Mail::fake();
        $this->post('/inquiry', [])->assertSessionHasErrors(['name', 'company', 'email', 'phone', 'message', 'consent']);
        $this->post('/inquiry', $this->payload() + ['website' => 'spam.example'])->assertSessionHasErrors('website');
        $draft = Product::factory()->create(['status' => 'draft']);
        $this->post('/inquiry', $this->payload() + ['product_id' => $draft->id])->assertSessionHasErrors('product_id');
        $this->assertDatabaseCount('inquiries', 0);
        Mail::assertNothingSent();
    }

    public function test_rate_limiting_stops_repeat_submissions(): void
    {
        Mail::fake();
        for ($i = 0; $i < 5; $i++) {
            $this->post('/inquiry', $this->payload())->assertRedirect();
        }
        $this->post('/inquiry', $this->payload())->assertStatus(429);
        $this->assertDatabaseCount('inquiries', 5);
    }

    public function test_inquiry_notification_template_renders(): void
    {
        $inquiry = Inquiry::create($this->payloadWithoutConsent() + ['reference_number' => 'ART-TEST', 'consent_at' => now()]);
        $this->assertStringContainsString('ART-TEST', (new InquiryReceived($inquiry))->render());
    }

    private function payloadWithoutConsent(): array
    {
        $data = $this->payload();
        unset($data['consent']);

        return $data;
    }
}
