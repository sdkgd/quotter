import { test, expect } from '@playwright/test'
import { testCreateQuoot, testCreateUser, testLogin } from '../utils';

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

test('別ユーザでは、Quoot削除ボタンが表示されない', async ({ page }) => {
  await testLogin(page,user2.email,'password');
  await expect(page.locator('#quoot-delete')).toHaveCount(0);
});

test('ログイン後、Quoot削除しレスポンスが返る', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await expect(page.locator('#quoot-content')).toContainText(['Test Quoot']);
  await expect(page.locator('#quoot-delete')).toHaveCount(1);
  await page.click('#quoot-delete');
  await page.locator('#quoot-content').waitFor({state:'detached'});
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  await expect(page.locator('#quoot-content')).not.toContainText(['Test Quoot']);
});