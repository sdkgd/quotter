"use client"

import { updateQuoot } from "@/lib/actions";
import { useRouter } from "next/navigation";
import { useState } from "react";
import ButtonPost from "../element/buttonpost";

type Props = {
  quootId: number;
  content: string;
}

export default function UpdateForm({quootId,content}:Props){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);

  const tryUpdateQuoot = async (data:FormData) =>{
    const res = await updateQuoot(data,quootId);
    if(res){
      setError(res);
    }else{
      router.push("/quoot"); 
    }
  }
  
  return(
    <>
      <form action={tryUpdateQuoot}>
        <textarea 
          id="quoot-content" 
          rows={3}
          name="quoot"
          className="focus:ring-blue-400 focus:border-blue-400 mt-1 block w-full text:text-sm border border-gray-300 bg-gray-100 text-gray-700 rounded-md p-2"
          defaultValue={content}></textarea>
        {error && <p className="text-red-500">{error}</p>}
        <div className="flex flex-wrap justify-end">
          <ButtonPost id="update-quoot" description="編集" />
        </div>
      </form>
    </>
  );
}