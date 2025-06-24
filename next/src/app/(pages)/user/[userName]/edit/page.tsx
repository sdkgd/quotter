"use client";

import { canEditProfile, editProfile } from "@/lib/actions";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { quser } from "@/types/types";

type Props={
  params:Promise<{userName:string}>;
};

export default function Page({params}:Props) {
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);
  const [data,setData] = useState<quser>();

  useEffect(()=>{
    const tryCanEditProfile = async() =>{
      try{
        const res = await canEditProfile((await params).userName);
        setData(res);
      }catch(e){
        setError((e as Error).message);
        router.push("/error/403");
      }
    }
    tryCanEditProfile();
  },[])

  const tryEditProfile = async(data:FormData) =>{
    try{
      const res = await editProfile(data,(await params).userName);
      if(res){
        setError(res);
      }else{
        router.push(`/user/${(await params).userName}`);
      }
    }catch(e){
      setError((e as Error).message);
    }
  }
  
  return(
    <>
      {data?
        <div>
          <h1>Quotter</h1>
          <h2>プロフィールを編集</h2>
          <form action={tryEditProfile}>
              <p>表示名</p>
              <input 
                  type="text" 
                  name="input1" 
                  id="input1" 
                  className="block mt-1 bg-gray-100 text-gray-700"
                  placeholder="Enter your name" 
                  defaultValue={data.display_name} 
              />

              <p>自己紹介</p>
              <textarea
                  name="input2"
                  id="input2"
                  className="block mt-1 bg-gray-100 text-gray-700"
                  placeholder="Enter your profile"
                  defaultValue={data.profile}
              ></textarea>

              <p>プロフィール画像</p>
              {/* ここに画像アップロード機能を実装予定 */}

              {error && <p className="text-red-500">{error}</p>}

              <button type="submit">変更を保存</button>
          </form>
        </div>
      :''}
    </>
  )
}