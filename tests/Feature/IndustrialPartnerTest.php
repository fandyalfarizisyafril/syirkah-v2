<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndustrialPartnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_partner_keeps_original_copy_dynamic_profile_and_about_link(): void
    {
        $this->withoutVite();
        $response = $this->get('/')->assertOk();
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame(1, $xpath->query('//section[@data-industrial-partner]')->length);
        $this->assertSame('YOUR INDUSTRIAL PARTNER', $xpath->query('//section[@data-industrial-partner]//p')->item(0)->textContent);
        $this->assertSame('Kebutuhan teknis Anda.Fokus kami.', $xpath->query('//section[@data-industrial-partner]//h2')->item(0)->textContent);
        $this->assertSame(Setting::values()['profile'], $xpath->query('//div[@class="home-partner-copy"]/p')->item(0)->textContent);
        $this->assertSame(1, $xpath->query('//section[@data-industrial-partner]//a')->length);
        $this->assertSame(route('about'), $xpath->query('//section[@data-industrial-partner]//a')->item(0)->getAttribute('href'));
        $this->assertSame(0, $xpath->query('//section[@data-industrial-partner]//img')->length);
        $this->assertSame('true', $xpath->query('//section[@data-industrial-partner]//svg')->item(0)->getAttribute('aria-hidden'));
        $this->get('/tentang-kami')->assertOk()->assertDontSee('data-industrial-partner')->assertSee('company-grid');
    }

    public function test_cms_profile_is_rendered_in_full_and_escaped(): void
    {
        $this->withoutVite();
        $profile = 'Equipment & spare parts. <script>alert(1)</script> Solusi teknis sesuai kebutuhan perusahaan.';
        Setting::create(['id' => 1, 'data' => ['profile' => $profile]]);
        $this->get('/')->assertOk()->assertSee($profile)->assertDontSee('<script>alert(1)</script>', false);
    }
}
