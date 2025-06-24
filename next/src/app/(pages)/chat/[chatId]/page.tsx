"use client";

import React, { useEffect, useState } from "react";
import { createMessage, getMessages, getUserData } from "@/lib/actions";
import { useRouter } from "next/navigation";
import { chat, message } from "@/types/types";

type Props={
  params:Promise<{chatId:number}>;
};

export default function Page({params}:Props) {
  const router = useRouter();
  const [userId,setUserId] = useState<number>(0);
  const [data,setData] = useState<chat>();
  const [error,setError] = useState<string|null>(null);

  useEffect(()=>{
    const tryGetUserData = async () =>{
      try{
        const res = await getUserData();
        setUserId(res.id);
      }catch(e){
        console.log((e as Error).message);
      }
    }
    tryGetUserData();
  },[]);

  useEffect(()=>{
    const tryGetMessages = async () =>{
      try{
        const res = await getMessages((await params).chatId);
        setData(res);
      }catch(e){
        console.log((e as Error).message);
        router.push("/error/403");
      }
    }
    tryGetMessages();
  },[]);

  const tryCreateMessage = async (data:FormData) =>{
    const res = await createMessage(data,(await params).chatId);
    if(res){
      setError(res);
    }
    const res2 = await getMessages((await params).chatId);
    setData(res2);
  }

  return(
    <>
      {data ?
        <div>
          <p>{data.users[0]}と{data.users[1]}の部屋</p>
          <div>
            {data.messages?.map((message:message)=>(
              <React.Fragment key={message.id}>
                {(userId && userId===message.mentioned_user_id)?<p className="text-blue-600/100">{message.content} posted on {message.created_at}</p>:''}
                {(userId && userId!==message.mentioned_user_id)?<p>{message.content} posted on {message.created_at}</p>:''}            
              </React.Fragment>
            ))}
          </div>
          <form action={tryCreateMessage}>
            <textarea 
                id="message-content" 
                rows={2}
                name="message"
                className="block mt-1 bg-gray-100 text-gray-700"
                placeholder="メッセージを入力"></textarea>
            {error && <p className="text-red-500">{error}</p>}
            <button type="submit">投稿</button>
          </form>
        </div>
      :''}
    </>
  );
}