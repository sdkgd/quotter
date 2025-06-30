import React from "react";
import { getQuoot, getUserData } from "@/lib/actions";
import QuootList from "@/components/quoot/quootlist";
import { errorRedirect } from "@/lib/navigations";
export const dynamic = 'force-dynamic';

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
    await errorRedirect((e as Error & { statusCode?: number }).statusCode);
    throw new Error("予期せぬエラーが発生しました");
  }

  return(
    <>
      <div>
        <QuootList loginUserId={userId} quoots={quoots} />
      </div>
    </>
  )
}