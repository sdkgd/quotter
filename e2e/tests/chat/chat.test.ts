import { test, expect } from '@playwright/test'
import { testCreateUser, testLogin, testCreateChat } from '../utils';
import { TEXT3 } from '../constants';

let users:any[]=[];
let chat:any;

test.beforeEach(async ({ request }) => {
  const res = await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
  for(let i=0;i<3;i++){
    users[i]=await testCreateUser();
  }
  chat=await testCreateChat(1,2);
});

test.afterEach(async ({ page }) => {
  await page.close();
});

test('非ログイン時はチャットルームを閲覧できない', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
});

test('ルームメンバー以外はチャットルームを閲覧できない', async ({ page }) => {
  await testLogin(page,users[2].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/error/403`);
  await expect(page.locator('#forbidden')).toContainText(['403 Forbidden']);
});

test('ルームメンバーは「チャットを開始」ボタン押下でチャットルームに移動', async ({ page }) => {
  await testLogin(page,users[0].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[1].user_name}`);
  await expect(page.locator('#start-chat')).toHaveCount(1);
  await page.click('#start-chat');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
});

test('ルームメンバーはメッセージを投稿できる', async ({ page }) => {
  await testLogin(page,users[0].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
  await page.locator('#message-content').waitFor();
  await page.locator('#create-message').waitFor();
  await page.fill('#message-content', 'Test Message');
  await page.click('#create-message');
  await page.locator('#message').waitFor();
  await expect(page).toHaveURL(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
  await expect(page.locator('#message')).toContainText(['Test Message']);
});

test('バリデーションを満たさないメッセージは投稿できない', async ({ page }) => {
  await testLogin(page,users[0].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
  await page.locator('#message-content').waitFor();
  await page.locator('#create-message').waitFor();
  await page.fill('#message-content', TEXT3);
  await page.click('#create-message');
  await page.locator('#error-message').waitFor();
  await expect(page).toHaveURL(`${process.env.TEST_FRONT_URL}/chat/${chat.id}`);
  await expect(page.locator('#error-message')).toContainText(['メッセージ は 1000 文字以下で入力してください']);
  await expect(page.locator('#message')).toHaveCount(0);
});