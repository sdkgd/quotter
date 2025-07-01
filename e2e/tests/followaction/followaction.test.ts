import { test, expect } from '@playwright/test'
import { testCreateUser, testLogin, testCreateFollow } from '../utils';

let users:any[]=[];

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
  for(let i=0;i<2;i++){
    users[i]=await testCreateUser();
  }
});

test.afterEach(async ({ page }) => {
  await page.close();
});

test('非ログイン時、ユーザページの「フォローする」「フォロー解除」ボタンが表示されない', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[1].user_name}`);
  await expect(page.locator('#create-follow')).toHaveCount(0);
  await expect(page.locator('#delete-follow')).toHaveCount(0);
});

test('ログイン時、「フォローする」ボタン押下でレスポンスが返る', async ({ page }) => {
  await testLogin(page,users[0].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[1].user_name}`);
  await expect(page.locator('#create-follow')).toHaveCount(1);
  await expect(page.locator('#delete-follow')).toHaveCount(0);
  await page.click('#create-follow');
  await page.locator('#create-follow').waitFor({state:'hidden'});
  await page.locator('#delete-follow').waitFor({state:'visible'});
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[1].user_name}`);
  await expect(page.locator('#create-follow')).toHaveCount(0);
  await expect(page.locator('#delete-follow')).toHaveCount(1);
});

test('ログイン時、「フォロー解除」ボタン押下でレスポンスが返る', async ({ page }) => {
  await testCreateFollow(1,2);
  await testLogin(page,users[0].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[1].user_name}`);
  await expect(page.locator('#create-follow')).toHaveCount(0);
  await expect(page.locator('#delete-follow')).toHaveCount(1);
  await page.click('#delete-follow');
  await page.locator('#create-follow').waitFor({state:'visible'});
  await page.locator('#delete-follow').waitFor({state:'hidden'});
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[1].user_name}`);
  await expect(page.locator('#create-follow')).toHaveCount(1);
  await expect(page.locator('#delete-follow')).toHaveCount(0);
});