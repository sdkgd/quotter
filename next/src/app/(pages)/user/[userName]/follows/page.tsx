import UserList from "@/components/user/userlist";
import { getFollows } from "@/lib/actions"
import { errorRedirect } from "@/lib/navigations";
import React from "react";

type Props={
  params:Promise<{userName:string}>;
};

export default async function Page({params}:Props) {
  let data;
  try{
    const res = await getFollows((await params).userName);
    data = res;
  }catch(e){
    await errorRedirect((e as Error & { statusCode?: number }).statusCode);
    throw new Error("予期せぬエラーが発生しました");
  }

  return(
    <>
      <div className="flex justify-center">
        <h2 className="text-lg font-bold mb-4">{data.displayName} さんがフォロー中</h2>
      </div>
      <UserList users={data.users} />
    </>
  )
}