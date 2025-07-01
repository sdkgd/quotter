import { test, expect } from '@playwright/test'
import { testCreateQuoot, testCreateUser, testLogin } from '../utils';
import { TEXT1 } from '../constants';

let user:any;
let user2:any;
let quoot:any;

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
  user = await testCreateUser();
  user2 = await testCreateUser();
  quoot = await testCreateQuoot(user.id,'Test Quoot');
});

test.afterEach(async ({ page }) => {
  await page.close();
});

test('非ログイン時、Quoot更新画面に移動するとログイン画面にリダイレクト', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot/create`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
});

test('別ユーザでは、Quoot更新画面に移動できない', async ({ page }) => {
  await testLogin(page,user2.email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot/update/${quoot.id}`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/error/403`);
  await expect(page.locator('#forbidden')).toContainText(['403 Forbidden']);
});

test('ログイン後、Quoot更新画面に移動、Quoot更新しレスポンスが返る', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot/update/${quoot.id}`);
  await expect(page.locator('textarea')).toContainText(['Test Quoot']);
  await page.locator('textarea').waitFor();
  await page.locator('#update-quoot').waitFor();
  await page.fill('textarea', 'Edited Test Quoot');
  await page.click('#update-quoot');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/quoot`);
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#quoot-content')).toContainText(['Edited Test Quoot']);
});

test('ログイン後、バリデーションを満たさない内容では更新できない', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot/update/${quoot.id}`);
  await page.locator('textarea').waitFor();
  await page.locator('#update-quoot').waitFor();
  await page.fill('textarea', TEXT1);
  await page.click('#update-quoot');
  await expect(page).not.toHaveURL(`${process.env.TEST_FRONT_URL}/quoot`);
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#quoot-content')).not.toContainText([TEXT1]);
});