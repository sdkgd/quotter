import { test, expect } from '@playwright/test'
import { testCreateQuoot, testCreateUser, testLogin, testCreateFollow } from '../utils';

let users:any[]=[];
let quoots:any[]=[];
let follows:any[]=[];

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
  for(let i=0;i<5;i++){
    users[i]=await testCreateUser();
  }
  for(let i=0;i<5;i++){
    quoots[i]=await testCreateQuoot(users[i].id,`I am ${users[i].display_name}`);
  }
  follows[0]=await testCreateFollow(1,3);
  follows[1]=await testCreateFollow(2,3);
  follows[2]=await testCreateFollow(3,4);
  follows[3]=await testCreateFollow(3,5);
});

test.afterEach(async ({ page }) => {
  await page.close();
});

test('非ログイン時、全ユーザのQuootが表示される', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  for(let i=0;i<5;i++){
    await expect(page.locator('#quoot-content')).toContainText([`I am ${users[i].display_name}`]);
  }
});

test('非ログイン時、フォローリストを表示しようとするとログイン画面にリダイレクト', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[2].user_name}/follows`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
});

test('非ログイン時、フォロワーリストを表示しようとするとログイン画面にリダイレクト', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[2].user_name}/followers`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
});

test('ログイン時、自分及びフォロー中のユーザのQuootのみが表示される', async ({ page }) => {
  await testLogin(page,users[2].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/quoot`);
  for(let i=0;i<2;i++){
    await expect(page.locator('#quoot-content')).not.toContainText([`I am ${users[i].display_name}`]);
  }
  for(let i=2;i<5;i++){
    await expect(page.locator('#quoot-content')).toContainText([`I am ${users[i].display_name}`]);
  }
});

test('ログイン時、フォローリストにフォロー中のユーザが表示される', async ({ page }) => {
  await testLogin(page,users[2].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[2].user_name}/follows`);
  for(let i=0;i<3;i++){
    await expect(page.locator('#user-display')).not.toContainText([`${users[i].display_name}`]);
  }
  for(let i=3;i<5;i++){
    await expect(page.locator('#user-display')).toContainText([`${users[i].display_name}`]);
  }
});

test('ログイン時、フォロワーリストに自分をフォローしているユーザが表示される', async ({ page }) => {
  await testLogin(page,users[2].email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${users[2].user_name}/followers`);
  for(let i=2;i<5;i++){
    await expect(page.locator('#user-display')).not.toContainText([`${users[i].display_name}`]);
  }
  for(let i=0;i<2;i++){
    await expect(page.locator('#user-display')).toContainText([`${users[i].display_name}`]);
  }
});