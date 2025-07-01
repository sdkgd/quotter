import { test, expect } from '@playwright/test'
import { testHaveToken } from '../utils';

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
});

test.afterEach(async ({ page }) => {
  await page.close();
});

test('registration screen can be rendered', async ({ page }) => {
  const res = await page.goto(`${process.env.TEST_FRONT_URL}/register`);
  await expect(page.locator('h1')).toContainText('Quotter');
  expect(res?.status()).toBe(200);
});

test('new users can register', async ({ page, context }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/register`);
  await page.locator('#user_name').waitFor();
  await page.locator('#email').waitFor();
  await page.locator('#password').waitFor();
  await page.locator('#confirm_password').waitFor();
  await page.locator('#register').waitFor();
  await page.fill('#user_name', 'TestUser');
  await page.fill('#email', 'test@example.com');
  await page.fill('#password', 'password');
  await page.fill('#confirm_password', 'password');
  await page.click('#register');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#logout')).toHaveCount(1);
  await expect(page.locator('#login')).toHaveCount(0);
  testHaveToken(context);
});