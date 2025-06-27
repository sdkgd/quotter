import Auth from "@/components/auth";
import MessagePostForm from "@/components/chat/messagepostform";
import { getMessages, getUserData } from "@/lib/actions";
import { redirect } from "next/navigation";
import React from "react";

type Props={
  params:Promise<{chatId:number}>;
};

export default async function Page({params}:Props) {
  let userId;
  try{
    const res = await getUserData();
    userId = res.id;
  }catch(e){
    console.log((e as Error).message);
    redirect("/error/401");
  }

  try{
    await getMessages((await params).chatId);
  }catch(e){
    console.log((e as Error).message);
    redirect("/error/403");
  }

  return(
    <>
      <Auth>
        <MessagePostForm userId={userId} chatId={(await params).chatId} />
      </Auth>
    </>
  );
}