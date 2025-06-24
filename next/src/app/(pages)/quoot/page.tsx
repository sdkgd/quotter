import React from "react";
import { deleteQuoot, getQuoot, getUserData } from "@/lib/actions";
import Link from "next/link";
import { redirect } from "next/navigation";
import { quoot } from "@/types/types";

export default async function Page() {
  let quoots;
  let userId:null|number = null;
  try{
    const res = await getUserData();
    userId = res.id;
  }catch(e){
    console.log((e as Error).message);
  }

  try{
    const res = await getQuoot(userId);
    quoots = res.quoots;
  }catch(e){
    console.log((e as Error).message);
  }

  return(
    <>
      <div>
        {quoots?.map((quoot:quoot)=>(
          <React.Fragment key={quoot.id}>
            {quoot.content} by {quoot.quser?.display_name} posted on {quoot.created_at}
            {(userId && userId===quoot.quser?.id)?<Link href={`/quoot/update/${quoot.id}`}> 更新</Link>:''}
            {(userId && userId===quoot.quser?.id)?
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