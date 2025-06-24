"use client"

import { createQuoot, getUserData } from "@/lib/actions";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";

export default function Page(){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);
  const [isLogin,setIsLogin] = useState<boolean>(false);

  useEffect(()=>{
    const tryGetUserData = async() =>{
      try{
        await getUserData();
        setIsLogin(true);
      }catch(e){
        console.log((e as Error).message);
        router.push("/login");
      }
    }
    tryGetUserData();
  },[])
  
  const tryCreateQuoot = async (data:FormData) =>{
    const res = await createQuoot(data);
    if(res){
      setError(res);
    }else{
      router.push("/quoot");   
    }
  }

  return(
    <>
      {isLogin &&
        <div>
          <h1>Quoot作成画面</h1>
          <div>
              <p>投稿フォーム</p>
              <form action={tryCreateQuoot}>
                  <textarea id="quoot-content" name="quoot" className="block mt-1 bg-gray-100 text-gray-700"></textarea>
                  <button type="submit">投稿</button>
                  {error && <p className="text-red-500">{error}</p>}
              </form>
          </div>
        </div>
      }
    </>
  )
}