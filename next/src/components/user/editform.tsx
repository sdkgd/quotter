"use client"

import { editProfile } from "@/lib/actions";
import { useRouter } from "next/navigation";
import ButtonPost from "../element/buttonpost";
import { useState } from "react";

type Props = {
  userName: string;
  displayName: string;
  profile: string;
}

export default function EditForm({userName,displayName,profile}:Props){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);
  
  const tryEditProfile = async(data:FormData) =>{
    try{
      const res = await editProfile(data,userName);
      if(res){
        setError(res);
      }else{
        router.push(`/user/${userName}`);
      }
    }catch(e){
      console.log(e);
      setError("プロフィール画像 には 1024 キロバイト以下の画像を指定してください");
    }
  }

  return(
    <>
      <h2 className="text-xl font-bold mb-4">プロフィールを編集</h2>
      <form action={tryEditProfile}>
        <div className="mb-4">
          <label className="text-m text-gray-700 block mb-1 font-bold">
            表示名
          </label>
          <input type="text" name="input1" id="input1" className="bg-gray-100 border border-gray-200 rounded py-1 px-3 block focus:ring-blue-500 focus:border-blue-500 text-gray-700" placeholder="Enter your name" defaultValue={displayName} />
        </div>

        <div className="mb-4">
          <label className="text-m text-gray-700 block mb-1 font-bold">
            自己紹介
          </label>
          <textarea
              rows={4}
              cols={50}
              name="input2"
              id="input2"
              className="bg-gray-100 rounded border border-gray-200 py-1 px-3 block focus:ring-blue-500 focus:border-blue-500 text-gray-700"
              placeholder="Enter your profile"
              defaultValue={profile}
          ></textarea>
        </div>

        <div className="mb-4">
          <label className="text-m text-gray-700 block mb-1 font-bold">
            プロフィール画像
          </label>
          <input 
            type="file" 
            accept="image/jpeg,image/jpg,image/png,image/gif"
            name="input3" 
            id="input3" 
            className="block w-full text-sm text-slate-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-gray-50 file:text-gray-700
                hover:file:bg-gray-100" 
          />
        </div>

        {error && <p id="error-message" className="text-red-500">{error}</p>}

        <div className="flex justify-end">
          <ButtonPost id="edit-profile" description="変更を保存" />
        </div>
      </form>
    </>
  )
}