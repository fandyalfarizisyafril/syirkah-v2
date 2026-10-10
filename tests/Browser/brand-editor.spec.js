import { test, expect } from '@playwright/test';
import { execFileSync } from 'node:child_process';

// Render the real form read-only; no local account, credential or session is changed.
const form = () => execFileSync('php', ['-r', `
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\\Contracts\\Console\\Kernel::class)->bootstrap();
view()->share('errors', new Illuminate\\Support\\ViewErrorBag);
Illuminate\\Support\\Facades\\Auth::setUser((new App\\Models\\User)->forceFill(['name' => 'Preview Editor', 'role' => 'editor']));
echo app(App\\Http\\Controllers\\Admin\\CatalogController::class)->form('brands', App\\Models\\Brand::where('slug', 'wolong')->value('id'))->render();
`], { encoding: 'utf8' });

for (const width of [1280, 390]) {
    test(`brand technology editor adds, edits and removes rows at ${width}px`, async ({ page }, testInfo) => {
        await page.setViewportSize({ width, height: 1000 });
        await page.route('**/admin/catalog/brands/*/edit', route => route.fulfill({ contentType: 'text/html', body: form() }));
        await page.goto('/admin/catalog/brands/1/edit');
        const editor = page.locator('[data-brand-technologies]');
        const rows = editor.locator('[data-technology-row]');
        const add = editor.getByRole('button', { name: 'Tambah Teknologi' });
        await expect(rows).toHaveCount(3);
        await add.click();
        await expect(rows).toHaveCount(4);
        const last = rows.last();
        await expect(last.getByLabel('Nama teknologi')).toBeFocused();
        await last.getByLabel('Nama teknologi').fill('Technology from editor');
        await last.getByLabel('Penjelasan singkat').fill('Verified scope from editor.');
        await expect(last.getByRole('button', { name: 'Hapus teknologi' }).locator('svg')).toBeVisible();
        await rows.first().getByRole('button', { name: 'Hapus teknologi' }).click();
        await expect(rows).toHaveCount(3);
        const values = await editor.locator('input:not([type="hidden"])').evaluateAll(inputs => inputs.map(input => ({ name: input.name, value: input.value, label: input.labels.length })));
        expect(values.every(input => input.label === 1)).toBe(true);
        expect(new Set(values.map(input => input.name)).size).toBe(values.length);
        expect(values.at(-1).value).toBe('Technology from editor');
        expect(await editor.evaluate(e => e.scrollWidth > e.clientWidth)).toBe(false);
        await editor.screenshot({ path: testInfo.outputPath(`brand-editor-${width}.png`) });
        for (let i = 3; i < 12; i++) await add.click();
        await expect(add).toBeDisabled();
        await rows.last().getByRole('button', { name: 'Hapus teknologi' }).click();
        await expect(add).toBeEnabled();
        const names = await rows.locator('input').evaluateAll(inputs => inputs.map(e => e.name));
        expect(new Set(names).size).toBe(names.length);
        await rows.getByRole('button', { name: 'Hapus teknologi' }).evaluateAll(buttons => buttons.forEach(button => button.click()));
        await expect(rows).toHaveCount(0);
        await expect(editor.locator('input[name="technology_details_present"]')).toHaveValue('1');
    });
}
