import { BrowserContext, expect, Page } from '@playwright/test';
import axios from 'axios';

export const testLogin = async(page: Page, email: string, password: string) =>{
  await page.goto(`${process.env.TEST_FRONT_URL}/login`);
  await page.locator('#email').waitFor();
  await page.locator('#password').waitFor();
  await page.locator('#login').waitFor();
  await page.fill('#email', email);
  await page.fill('#password', password);
  await page.click('#login');
  await page.waitForURL(`${process.env.TEST_FRONT_URL}/quoot`);
}

export const testHaveToken = async(context:BrowserContext) =>{
  const cookies = await context.cookies();
  const cookieNames = cookies.map(cookie => cookie.name);
  expect(cookieNames).toContain('token');
}

export const testNotHaveToken = async(context:BrowserContext) =>{
  const cookies = await context.cookies();
  const cookieNames = cookies.map(cookie => cookie.name);
  expect(cookieNames).not.toContain('token');
}

export const testCreateUser = async () =>{
  try{
    const res = await axios.post(`${process.env.TEST_API_URL}/api/test/create-testuser`);
    return res.data;
  }catch(e){
    console.log(e);
  }
}

export const testCreateQuoot = async(userId:number, quoot:string) =>{
  try{
    const res = await axios.post(`${process.env.TEST_API_URL}/api/test/create-testquoot`,
      {
        user_id: userId,
        quoot: quoot,
      },
      {
        headers:{
          "Content-Type": "application/json",
        },
      }
    );
    return res.data;
  }catch(e){
    console.log(e);
  }
}

export const testCreateFollow = async(followingId:number, followedId:number) =>{
  try{
    const res = await axios.post(`${process.env.TEST_API_URL}/api/test/create-testfollow`,
      {
        following_id: followingId,
        followed_id: followedId,
      },
      {
        headers:{
          "Content-Type": "application/json",
        },
      }
    );
    return res.data;
  }catch(e){
    console.log(e);
  }
}

export const testClearImageDisk = async(userName:string) =>{
  try{
    const res = await axios.post(`${process.env.TEST_API_URL}/api/test/clear-image-disk`,
      {
        user_name: userName,
      },
      {
        headers:{
          "Content-Type": "application/json",
        },
      }
    );
    return res.data;
  }catch(e){
    console.log(e);
  }
}

export const testCreateChat = async(user1Id:number, user2Id:number) =>{
  try{
    const res = await axios.post(`${process.env.TEST_API_URL}/api/test/create-testchat`,
      {
        user1_id: user1Id,
        user2_id: user2Id,
      },
      {
        headers:{
          "Content-Type": "application/json",
        },
      }
    );
    return res.data;
  }catch(e){
    console.log(e);
  }
}