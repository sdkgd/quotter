import { Page, test, expect } from '@playwright/test'
import { testCreateUser, testLogin } from '../utils';
import { TEXT1 } from '../constants';

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
});

test.afterEach(async ({ page }) => {
  await page.close();
});

const testCreateQuoot = async(page: Page, quoot: string) =>{
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot/create`);
  await page.locator('textarea').waitFor();
  await page.locator('#create-quoot').waitFor();
  await page.fill('textarea', quoot);
  await page.click('#create-quoot');
}

test('非ログイン時、Quoot投稿画面に移動するとログイン画面にリダイレクト', async ({ page }) => {
  const res = await page.goto(`${process.env.TEST_FRONT_URL}/quoot/create`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
});

test('ログイン後、Quoot投稿画面に移動', async ({ page }) => {
  const user = await testCreateUser();
  await testLogin(page,user.email,'password');
  const res = await page.goto(`${process.env.TEST_FRONT_URL}/quoot/create`);
  expect(res?.status()).toBe(200);
});

test('ログイン後、Quoot投稿しレスポンスが返る', async ({ page }) => {
  const user = await testCreateUser();
  await testLogin(page,user.email,'password');
  await testCreateQuoot(page,'Test Quoot');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/quoot`);
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#quoot-content')).toContainText(['Test Quoot']);
});

test('ログイン後、バリデーションを満たさない内容は投稿できない', async ({ page }) => {
  const user = await testCreateUser();
  await testLogin(page,user.email,'password');
  await testCreateQuoot(page,TEXT1);
  await expect(page).not.toHaveURL(`${process.env.TEST_FRONT_URL}/quoot`);
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#quoot-content')).not.toContainText([TEXT1]);
});