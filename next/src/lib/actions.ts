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
    if(axios.isAxiosError(e)){
      return "EmailまたはPasswordに誤りがあります";
    }
    throw new Error("予期せぬエラーが発生しました");
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
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
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
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
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
    if(axios.isAxiosError(e)){
      return "ユーザ新規登録に失敗しました";
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const getQuoot = async (userId?:number|null) =>{
  try{
    let res;
    if(!userId) res = await axios.get(`${process.env.API_BASE_URL}/api/quoot`);
    else res = await axios.get(`${process.env.API_BASE_URL}/api/quoot?id=${userId}`)
    return res.data;
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const createQuoot = async (data:FormData) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.post(`${process.env.API_BASE_URL}/api/quoot/create`,
      {
        quoot: data.get("quoot"),
      },
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      if(e.response?.data.status===422) return e.response?.data.message;
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const canUpdateQuoot = async (quootId:number) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.get(`${process.env.API_BASE_URL}/api/quoot/update/${quootId}`,
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
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const updateQuoot = async (data:FormData, quootId:number) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.put(`${process.env.API_BASE_URL}/api/quoot/update/${quootId}`,
      {
        quoot: data.get("quoot"),
      },
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      if(e.response?.data.status===422) return e.response?.data.message;
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const deleteQuoot = async (quootId:number) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.delete(`${process.env.API_BASE_URL}/api/quoot/delete/${quootId}`,
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
        data:{}
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const getFollows = async (userName:string) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.get(`${process.env.API_BASE_URL}/api/user/${userName}/follows`,
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
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const getFollowers = async (userName:string) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.get(`${process.env.API_BASE_URL}/api/user/${userName}/followers`,
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
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const getUserPage = async (userName:string,loginId?:number|null) =>{
  try{
    let res;
    if(!loginId) res = await axios.get(`${process.env.API_BASE_URL}/api/user/${userName}`);
    else res = await axios.get(`${process.env.API_BASE_URL}/api/user/${userName}?id=${loginId}`)
    return res.data;
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const createFollow = async (userName:string) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.post(`${process.env.API_BASE_URL}/api/user/${userName}/follow`,
      {},
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const deleteFollow = async (userName:string) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.delete(`${process.env.API_BASE_URL}/api/user/${userName}/unfollow`,
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
        data:{}
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const getMessages = async (chatId:number) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.get(`${process.env.API_BASE_URL}/api/chat/${chatId}`,
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
        data:{}
      }
    );
    return res.data;
  }catch(e){
    if(axios.isAxiosError(e)){
      return "メッセージ取得に失敗しました";
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const createMessage = async (data:FormData, chatId:number) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.post(`${process.env.API_BASE_URL}/api/chat/${chatId}`,
      {
        message: data.get("message"),
      },
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      if(e.response?.data.status===422) return e.response?.data.message;
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const moveChatRoom = async (userName:string) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.post(`${process.env.API_BASE_URL}/api/user/${userName}/chat`,
      {
        userName: userName,
      },
      {
        headers:{
          "Content-Type": "application/json",
          "Authorization": `Bearer ${token}`
        },
      }
    )
    return res.data;
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const canEditProfile = async (userName:string) =>{
  try{
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    const res = await axios.get(`${process.env.API_BASE_URL}/api/user/${userName}/edit`,
      {
        headers:{
          "Authorization": `Bearer ${token}`
        },
      }
    )
    return res.data;
  }catch(e){
    if(axios.isAxiosError(e)){
      throw Object.assign(new Error(e.response?.data.message),{statusCode:e.response?.data.status});
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}

export const editProfile = async (data:FormData, userName:string) =>{
  try{
    const input1:string = String(data.get("input1"));
    const input2:string = String(data.get("input2"));
    const input3 = data.get("input3");

    const form = new FormData();
    form.append('_method', 'PUT');
    form.append('input1',input1);
    form.append('input2',input2);
    if (input3 instanceof File) {
      if(input3.size!==0) form.append('input3',input3);
    }
    
    const cookieStore = await cookies();
    const token:string|undefined = cookieStore.get("token")?.value;
    await axios.post(`${process.env.API_BASE_URL}/api/user/${userName}/edit`,
      form,
      {
        headers:{
          "Content-Type": "multipart/form-data",
          "Authorization": `Bearer ${token}`,
        },
      }
    )
  }catch(e){
    if(axios.isAxiosError(e)){
      if(e.response?.data.status===422) return e.response?.data.message;
    }
    throw new Error("予期せぬエラーが発生しました");
  }
}