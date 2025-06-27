import { deleteQuoot } from "@/lib/actions";
import Link from "next/link";
import { redirect } from "next/navigation";
import React from "react";
import { quoot } from "@/types/types";
import ImageFrame from "../element/imageframe";
import { LOCAL_DEFAULT_IMAGE_URL, S3_DEFAULT_IMAGE_URL } from "@/constants";

type Props = {
  loginUserId: number;
  quoots: quoot[];
}

export default function QuootList({loginUserId,quoots}:Props){
  return(
    <>
      <div className="bg-white rounded-md shadow-lg mt-5 mb-5 overflow-auto">
        <ul>
          {quoots?.map((quoot:quoot)=>(
            <li className="border-b last:border-0 border-gray-200 p-4" key={quoot.id}>
              
              <div className="flex">
                {quoot.quser?.image? 
                  <ImageFrame path={quoot.quser.image.path} size={60} /> :
                  process.env.NODE_ENV==="production"?
                  <ImageFrame path={S3_DEFAULT_IMAGE_URL} size={60} />:
                  <ImageFrame path={LOCAL_DEFAULT_IMAGE_URL} size={60} />
                }
              
                <div className="ml-4">
                  <span className="inline-block rounded-full px-2 py-1 text-s font-bold mb-1">
                    <Link href={`/user/${quoot.quser?.user_name}`}>{quoot.quser?.display_name}</Link>
                  </span> 
                  <p id="quoot-content" className="text-gray-600 px-2 mb-1">{quoot.content}</p>
                </div>
              </div>

              <p className="text-xs text-right">posted on {(()=>{
                const str:string = `${quoot.created_at}`;
                const str1:string = str.substring(0,10);
                const str2:string = str.substring(11,19);
                return str1+" "+str2;
              })()}</p>

              {(loginUserId && loginUserId===quoot.quser?.id)?
                <div className="mt-2 text-xs text-right">
                  <span className="mr-1"><Link id="quoot-update" href={`/quoot/update/${quoot.id}`}> 更新</Link></span>
                  <form className="inline" action={
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
                    <button id="quoot-delete" type="submit"> 削除</button>
                  </form>
                </div>
              :''}
            </li>
          ))}
        </ul>
      </div>
    </>
  )
}