import React from "react";
import Link from "next/link";
import { createFollow, deleteFollow, deleteQuoot, getUserData, getUserPage, moveChatRoom } from "@/lib/actions";
import { redirect } from "next/navigation";
import { revalidatePath } from "next/cache";
import { quoot } from "@/types/types";

type Props={
  params:Promise<{userName:string}>;
};

let data;
export default async function Page({params}:Props) {
  let loginUserId:number=0;
  try{
    const res = await getUserData();
    loginUserId = res?.id;
  }catch(e){
    console.log((e as Error).message);
  }

  try{
    const res = await getUserPage((await params).userName,loginUserId);
    data = res;
  }catch(e){
    console.log((e as Error).message);
    redirect("/error/403");
  }

  const tryCreateFollow = async() =>{
    "use server";
    try{
      await createFollow((await params).userName);
      const res = await getUserPage((await params).userName,loginUserId);
      data = res;
    }catch(e){
      console.log((e as Error).message);
      redirect("/error/403");
    }
    revalidatePath(`/user/${(await params).userName}`);
  }

  const tryDeleteFollow = async() =>{
    "use server";
    try{
      await deleteFollow((await params).userName);
      const res = await getUserPage((await params).userName,loginUserId);
      data = res;
    }catch(e){
      console.log((e as Error).message);
      redirect("/error/403");
    }
    revalidatePath(`/user/${(await params).userName}`);
  }

  const tryMoveChatRoom = async() =>{
    "use server";
    let chatId;
    try{
      const res = await moveChatRoom((await params).userName);
      chatId = res.chatId;
    }catch(e){
      console.log((e as Error).message);
      redirect("/error/403");
    }
    redirect(`/chat/${chatId}`);
  }

  return(
    <>
      <p>ユーザ{data.displayName}のページ</p>
      <p>{data.profile}</p>
      {(loginUserId && loginUserId!==data.id && !data.isFollowing)?<form action={tryCreateFollow}><button type="submit">フォローする</button></form>:''}
      {(loginUserId && loginUserId!==data.id && data.isFollowing)?<form action={tryDeleteFollow}><button type="submit">フォロー解除</button></form>:''}
      {(loginUserId && loginUserId!==data.id)?<form action={tryMoveChatRoom}><button type="submit">チャットを開始</button></form>:''}
      {(loginUserId && loginUserId===data.id)?<Link href={`/user/${data.userName}/edit`}>プロフィール編集画面へ</Link>:''}
      <div>
        {data.quoots?.map((quoot:quoot)=>(
          <React.Fragment key={quoot.id}>
            {quoot.content} by {quoot.quser?.display_name} posted on {quoot.created_at}
            {(loginUserId && loginUserId===quoot.quser?.id)?<Link href={`/quoot/update/${quoot.id}`}> 更新</Link>:''}
            {(loginUserId && loginUserId===quoot.quser?.id)?
              <form action={
                async()=>{
                  "use server";
                  try{
                    await deleteQuoot(quoot.id);
                  }catch(e){
                    console.log((e as Error).message);
                  }
                  redirect("/quoot"); 
                }
              }>
                <button type="submit"> 削除</button>
              </form>:''
            }
            <br></br>
          </React.Fragment>
        ))}
      </div>
    </>
  )
}