"use client"

import { useEffect, useState } from "react";
import ButtonPost from "../element/buttonpost";
import { createMessage, getMessages } from "@/lib/actions";
import MessageList from "./messagelist";
import { chat } from "@/types/types";

type Props = {
  userId: number;
  chatId: number;
}

export default function MessagePostForm({userId,chatId}:Props){
  const [error,setError] = useState<string|null>(null);
  const [data,setData] = useState<chat>();

  useEffect(()=>{
    const tryGetMessages = async () =>{
      try{
        const res = await getMessages(chatId);
        setData(res);
      }catch(e){
        console.log((e as Error).message);
      }
    }
    tryGetMessages();
  },[]);

  useEffect(()=>{
    const script:HTMLScriptElement = document.createElement("script");
    script.src = "/js/scroll.js";
    script.async = true;
    document.body.appendChild(script);
    document.body.removeChild(script);
  },[data]);

  const tryCreateMessage = async (data:FormData) =>{
    const res = await createMessage(data,chatId);
    if(res){
      setError(res);
    }
    const res2 = await getMessages(chatId);
    setData(res2);
  }

  return(
    <>
      {data?
        <div>
          <p className="text-2xl font-bold mb-4">{data?.users[0]}と{data?.users[1]}の部屋</p>
          <MessageList userId={userId} messages={data?.messages} />
          <form action={tryCreateMessage}>
            <textarea 
                id="message-content" 
                rows={2}
                name="message"
                className="focus:ring-blue-400 focus:border-blue-400 mt-1 block w-full text:text-sm border border-gray-300 bg-white text-gray-700 rounded-md p-2"
                placeholder="メッセージを入力"></textarea>
            {error && <p id="error-message" className="text-red-500">{error}</p>}
            <div className="flex flex-wrap justify-end">
                <ButtonPost id="create-message" description="送信" />
            </div>
          </form>
        </div>
      :<p>Loading...</p>}
    </>
  )
}