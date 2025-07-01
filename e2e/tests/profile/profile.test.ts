import { test, expect, Page } from '@playwright/test'
import { testCreateUser, testLogin, testClearImageDisk } from '../utils';
import { TEXT2 } from '../constants';

let user:any;

test.beforeEach(async ({ request }) => {
  await request.post(`${process.env.TEST_API_URL}/api/test/reset-db`);
  user = await testCreateUser();
});

test.afterEach(async ({ page }) => {
  await page.close();
});

const waitForEditPage = async(page:Page) =>{
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${user.user_name}/edit`);
  await page.locator('#input1').waitFor();
  await page.locator('#input2').waitFor();
  await page.locator('#input3').waitFor();
  await page.locator('#edit-profile').waitFor();
}

const fillValid = async(page:Page,input1:string,input2:string) =>{
  await waitForEditPage(page);
  await page.fill('#input1', input1);
  await page.fill('#input2', input2);
  await page.click('#edit-profile');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
}

const fillInvalid = async(page:Page,input1:string,input2:string,errorMsg:string) =>{
  await waitForEditPage(page);
  await page.fill('#input1', input1);
  await page.fill('#input2', input2);
  await page.click('#edit-profile');
  await page.locator('#error-message').waitFor();
  await expect(page).not.toHaveURL(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
  await expect(page.locator('#error-message')).toContainText([`${errorMsg}`]);
}

const fillValidwithImage = async(page:Page,input1:string,input2:string,input3Path:string) =>{
  await waitForEditPage(page);
  await page.fill('#input1', input1);
  await page.fill('#input2', input2);
  await page.locator('#input3').setInputFiles(input3Path);
  await page.click('#edit-profile');
  if(!process.env.CI){
    await page.waitForURL(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
  }else{
    await expect(async () => {
      expect(page.url()).toMatch(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
    }).toPass({timeout:30000,intervals:[1000]});
  }
}

const fillInvalidwithImage = async(page:Page,input1:string,input2:string,input3Path:string,errorMsg:string) =>{
  await waitForEditPage(page);
  await page.fill('#input1', input1);
  await page.fill('#input2', input2);
  await page.locator('#input3').setInputFiles(input3Path);
  await page.click('#edit-profile');
  await page.locator('#error-message').waitFor();
  await expect(page).not.toHaveURL(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
  await expect(page.locator('#error-message')).toContainText([`${errorMsg}`]);
}

test('非ログイン時、表示名と自己紹介が表示され、「プロフィールを編集」ボタンが表示されない', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
  await expect(page.locator('#displayname')).toContainText([`${user.display_name}`]);
  await expect(page.locator('#profile')).toContainText([`Test User`]);
  await expect(page.locator('#edit-profile')).toHaveCount(0);
});

test('非ログイン時、プロフィール編集ページに移動不可', async ({ page }) => {
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${user.user_name}/edit`);
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/login`);
});

test('ログイン時、「プロフィールを編集」ボタン押下で編集ページに移動', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${user.user_name}`);
  await expect(page.locator('#move-edit-profile-page')).toHaveCount(1);
  await page.click('#move-edit-profile-page');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/user/${user.user_name}/edit`);
});

test('プロフィール編集ページに表示名と自己紹介が表示される', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await page.goto(`${process.env.TEST_FRONT_URL}/user/${user.user_name}/edit`);
  await expect(page.locator('#input1')).toHaveValue(user.display_name);
  await expect(page.locator('#input2')).toContainText([`Test User`]);
});

test('プロフィール編集を実行しリダイレクトされる', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await fillValid(page,'Edited Display Name','Edited Test User');
  await expect(page.locator('#displayname')).toContainText([`Edited Display Name`]);
  await expect(page.locator('#profile')).toContainText([`Edited Test User`]);
});

test('プロフィール編集 バリデーションが正しく機能する', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await fillInvalid(page,'','Test User','表示名 は必須入力です');
  await fillInvalid(page,TEXT2,'Test User','表示名 は 255 文字以下で入力してください');
  await fillInvalid(page,'Display Name',TEXT2,'自己紹介 は 255 文字以下で入力してください');
  await fillValid(page,'Display Name','');
});

test('プロフィール編集 画像アップロード', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await fillValidwithImage(page,'Display Name','Test User','img/profile_icon.png');
  await testClearImageDisk(user.user_name);
});

test('プロフィール編集 画像アップロード バリデーション', async ({ page }) => {
  await testLogin(page,user.email,'password');
  await fillInvalidwithImage(page,'Display Name','Test User','img/large_icon.png','プロフィール画像 には 1024 キロバイト以下の画像を指定してください');
  await fillInvalidwithImage(page,'Display Name','Test User','img/sample.txt','プロフィール画像 には画像を指定してください');
});