<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_keeps_company_data_five_focus_areas_values_and_shared_cta(): void
    {
        $this->withoutVite();
        $response = $this->get(route('about'))->assertOk();
        foreach (['company_name', 'tagline', 'profile'] as $key) $response->assertSee(Setting::values()[$key]);
        $dom = new \DOMDocument;
        @$dom->loadHTML($response->getContent());
        $xpath = new \DOMXPath($dom);
        $this->assertSame(1, $xpath->query('//h1')->length);
        $this->assertSame('Mitra untuk kebutuhanteknis industri.', $xpath->query('//h1')->item(0)->textContent);
        $focus = $xpath->query('//ul[@class="about-focus-grid"]/li/h3');
        $expected = ['Engineering', 'Mechanical', 'Electrical', 'Instrumentation', 'Oil Spill Response & Prevention'];
        $this->assertSame(5, $focus->length);
        foreach ($expected as $index => $label) $this->assertSame($label, $focus->item($index)->textContent);
        $this->assertSame(0, $xpath->query('//ul[@class="about-focus-grid"]//a | //ul[@class="about-focus-grid"]//button')->length);
        $values = $xpath->query('//ul[contains(@class,"about-values-list")]/li/span');
        $expected = ['Reliability', 'Efficiency', 'Operational Continuity', 'Safety', 'Environmental Protection'];
        $this->assertSame(5, $values->length);
        foreach ($expected as $index => $label) $this->assertSame($label, $values->item($index)->textContent);
        $this->assertSame(1, $xpath->query('//div[@class="about-page"]//img')->length);
        $this->assertSame(asset('images/industrial.jpg'), $xpath->query('//div[@class="about-page"]//img')->item(0)->getAttribute('src'));
        $this->assertSame(1, $xpath->query('//section[@class="inquiry-band"]')->length);
        $this->assertSame(1, $xpath->query('//footer')->length);
        $this->assertSame(0, $xpath->query('//*[@id="legal"]')->length);
    }

    public function test_cms_changes_and_legal_anchor_remain_supported_and_escaped(): void
    {
        $this->withoutVite();
        $data = [
            'company_name' => 'SMART & Company', 'tagline' => 'Equipment & Technical Solutions',
            'profile' => "Profil perusahaan yang dikelola CMS.\nBaris kedua tetap tersedia.",
            'legal_information' => '<script>alert(1)</script> Legal & Company',
        ];
        Setting::create(['id' => 1, 'data' => $data]);
        $response = $this->get('/tentang-kami')->assertOk();
        foreach ($data as $value) $response->assertSee($value);
        $response->assertSee('id="legal"', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->get('/')->assertOk()->assertDontSee('class="about-page"', false)->assertSee('data-industrial-partner');
    }
}
