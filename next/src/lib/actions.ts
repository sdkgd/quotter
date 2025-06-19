"use server";

import axios from "axios";
import { cookies } from 'next/headers'

export const login = async (data:FormData) =>{
  try{
    const res = await axios.post(`${process.env.API_BASE_URL}/api/login`,
      {
        email: data.get("email"),
        password: data.get("password"),
      },
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    const token:string = res.data.token;
    const cookieStore = await cookies();
    cookieStore.set({
      name: "token",
      value: token,
      httpOnly: true,
    });
  }catch(e){
    console.log(e);
    throw new Error("EmailまたはPasswordに誤りがあります");
  }  
}

export const getUserData = async () =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.get(`${process.env.API_BASE_URL}/api/user`,
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
        data:{}
      }
    )
    return res.data;
  }catch(e){
    console.log(e);
    throw new Error("アクセス許可がありません");
  }
}

export const logout = async () =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.post(`${process.env.API_BASE_URL}/api/logout`,
      {},
      {
        headers: {
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      }
    );
    cookieStore.delete("token");
  }catch(e){
    console.log(e);
    throw new Error("ログアウトに失敗しました");
  }
}

export const register = async (data:FormData) =>{
  try{
    await axios.post(`${process.env.API_BASE_URL}/api/register`,
      {
        name: data.get("user_name"),
        email: data.get("email"),
        password: data.get("password"),
      },
      {
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    await login(data);
  }catch(e){
    console.log(e);
    throw new Error("ユーザ新規登録に失敗しました");
  }
}