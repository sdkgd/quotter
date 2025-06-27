import ButtonPost from "@/components/element/buttonpost";
import LinkGet from "@/components/element/linkget";
import QuootList from "@/components/quoot/quootlist";
import { createFollow, deleteFollow, getUserData, getUserPage, moveChatRoom } from "@/lib/actions";
import { redirect } from "next/navigation";
import React from "react";
import Link from "next/link";
import { revalidatePath } from "next/cache";
import ImageFrame from "@/components/element/imageframe";
import { LOCAL_DEFAULT_IMAGE_URL, S3_DEFAULT_IMAGE_URL } from "@/constants";

type Props={
  params:Promise<{userName:string}>;
};

let data;
export default async function Page({params}:Props) {
  let loginUserId:number=0;
  try{
    const res2 = await getUserData();
    loginUserId = res2?.id;
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
      <div className="h-8"></div>
      <div className="flex justify-between">
        <div className="flex">
          {data.imagePath? 
            <ImageFrame path={data.imagePath} size={120} /> :
            process.env.NODE_ENV==="production"?
            <ImageFrame path={S3_DEFAULT_IMAGE_URL} size={120} />:
            <ImageFrame path={LOCAL_DEFAULT_IMAGE_URL} size={120} />
          }
          <div className="ml-8">
            <h2 id="displayname" className="text-3xl font-bold mb-4">{data.displayName}</h2>
            <p id="profile">{data.profile}</p>
          </div>
        </div>
        <div>
          <ul className="flex space-x-4">
            <li><Link href={`/user/${data.userName}/follows`} className="text-center text-gray-500 hover:text-black">Follows</Link></li>
            <li><Link href={`/user/${data.userName}/followers`} className="text-center text-gray-500 hover:text-black">Followers</Link></li>
          </ul>
        </div>
      </div>

      <div className="flex flex-wrap justify-center">
        {(loginUserId && loginUserId!==data.id && !data.isFollowing)?<form action={tryCreateFollow}><ButtonPost id="create-follow" description="フォローする"/></form>:''}
        {(loginUserId && loginUserId!==data.id && data.isFollowing)?<form action={tryDeleteFollow}><ButtonPost id="delete-follow" description="フォロー解除"/></form>:''}
        {(loginUserId && loginUserId!==data.id)?<form action={tryMoveChatRoom}><ButtonPost id="start-chat" description="チャットを開始"/></form>:''}
        {(loginUserId && loginUserId===data.id)?<LinkGet id="move-edit-profile-page" path={`/user/${data.userName}/edit`} description="プロフィール編集画面へ" />:''}
      </div>

      <QuootList loginUserId={loginUserId} quoots={data.quoots} />
    </>
  )
}