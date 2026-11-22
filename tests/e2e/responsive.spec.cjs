const { test, expect } = require('@playwright/test');

const pages = [
  'index.php', 'categorias.php', 'nosotros.php', 'contacto.php',
  'carrito.php', 'login.php', 'registro.php',
];

for (const page of pages) {
  test(page + ' remains usable without horizontal overflow', async ({ page: browserPage }) => {
    await browserPage.goto('/' + page);
    await expect(browserPage.locator('h1').first()).toBeVisible();
    const widths = await browserPage.evaluate(() => ({
      scroll: document.documentElement.scrollWidth,
      viewport: document.documentElement.clientWidth,
    }));
    expect(widths.scroll).toBeLessThanOrEqual(widths.viewport + 1);
  });
}

test('responsive navigation and shared cart components', async ({ page, isMobile }) => {
  await page.goto('/categorias.php');
  const cards = page.locator('.product-card');
  expect(await cards.count()).toBe(40);
  if (await page.locator('[data-menu-toggle]').isVisible()) {
    await page.locator('[data-menu-toggle]').click();
    await expect(page.locator('#nav-menu')).toHaveAttribute('data-open', '');
    await page.keyboard.press('Escape');
    await expect(page.locator('#nav-menu')).not.toHaveAttribute('data-open', '');
  }
  await cards.first().locator('button.add-to-cart').click();
  await expect(page.locator('.cart-count')).toHaveText('1');
  await page.goto('/carrito.php');
  await expect(page.locator('.cart-row')).toHaveCount(1);
  await expect(page.locator('#cart-summary')).toContainText('Total estimado');
  await page.reload();
  await expect(page.locator('.cart-row')).toHaveCount(1);
});

test('catalog filters are server-rendered', async ({ page }) => {
  await page.goto('/categorias.php?categoria=Despensa');
  await expect(page.locator('.product-card')).toHaveCount(8);
  await page.goto('/categorias.php?q=unlisted-phrase-123');
  await expect(page.locator('.empty-state')).toBeVisible();
});
