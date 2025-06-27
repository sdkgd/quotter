import React from "react";
import { getQuoot, getUserData } from "@/lib/actions";
import QuootList from "@/components/quoot/quootlist";

export default async function Page() {
  let quoots;
  let userId;
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
        <QuootList loginUserId={userId} quoots={quoots} />
      </div>
    </>
  )
}