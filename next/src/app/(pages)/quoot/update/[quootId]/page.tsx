"use client"

import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { canUpdateQuoot, updateQuoot } from "@/lib/actions";
import { quoot } from "@/types/types";

type Props={
  params:Promise<{quootId:number}>;
};

export default function Page({params}:Props){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);
  const [data,setData] = useState<quoot>();

  useEffect(()=>{
    const tryCanUpdateQuoot = async() =>{
      try{
        const res = await canUpdateQuoot((await params).quootId);
        setData(res);
      }catch(e){
        console.log((e as Error).message);
        router.push("/error/403");  
      }
    }
    tryCanUpdateQuoot();
  },[])
  
  const tryUpdateQuoot = async (data:FormData) =>{
    const res = await updateQuoot(data,(await params).quootId); 
    if(res){
      setError(res);
    }else{
      router.push("/quoot");   
    }
  }

  return(
    <>
      {data &&
        <div>
          <h1>Quoot更新画面</h1>
          <div>
              <p>更新フォーム</p>
              <form action={tryUpdateQuoot}>
                  <textarea id="quoot-content" name="quoot" className="block mt-1 bg-gray-100 text-gray-700">{data.content}</textarea>
                  <button type="submit">更新</button>
                  {error && <p className="text-red-500">{error}</p>}
              </form>
          </div>
        </div>
      }
    </>
  )
}