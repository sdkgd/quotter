import { test, expect } from '@playwright/test'

test('sample test', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('h1')).toContainText('Quotter');
})