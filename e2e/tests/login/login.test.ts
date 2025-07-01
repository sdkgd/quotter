import { test, expect } from '@playwright/test'
import { testCreateUser, testHaveToken, testLogin, testNotHaveToken } from '../utils';

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
});

test.afterEach(async ({ page }) => {
  await page.close();
});

test('login screen can be rendered', async ({ page }) => {
  const res = await page.goto(`${process.env.TEST_FRONT_URL}/login`);
  await expect(page.locator('h1')).toContainText('Quotter');
  expect(res?.status()).toBe(200);
});

test('users can authenticate using the login screen', async ({ page, context }) => {
  const user = await testCreateUser();
  await testLogin(page,user.email,'password');
  await expect(page.locator('#logout')).toHaveCount(1);
  await expect(page.locator('#login')).toHaveCount(0);
  await testHaveToken(context);
});

test('users can not authenticate with invalid password', async ({ page, context }) => {
  const user = await testCreateUser();
  await page.goto(`${process.env.TEST_FRONT_URL}/login`);
  await page.locator('#email').waitFor();
  await page.locator('#password').waitFor();
  await page.locator('#login').waitFor();
  await page.fill('#email', user.email);
  await page.fill('#password', 'wrongpassword');
  await page.click('#login');
  await expect(page).not.toHaveURL(`${process.env.TEST_FRONT_URL}/quoot`);
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#logout')).toHaveCount(0);
  await expect(page.locator('#login')).toHaveCount(1);
  await testNotHaveToken(context);
});

test('users can logout', async ({ page, context }) => {
  const user = await testCreateUser();
  await testLogin(page,user.email,'password');
  await testHaveToken(context);
  await expect(page.locator('#logout')).toHaveCount(1);
  await page.click('#logout');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
  await testNotHaveToken(context);
});