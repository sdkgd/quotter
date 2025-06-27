import UserList from "@/components/user/userlist";
import { getFollowers } from "@/lib/actions"
import { redirect } from "next/navigation";
import React from "react";

type Props={
  params:Promise<{userName:string}>;
};

export default async function Page({params}:Props) {
  let data;
  try{
    const res = await getFollowers((await params).userName);
    data = res;
  }catch(e){
    console.log((e as Error).message);
    redirect("/login");
  }

  return(
    <>
      <div className="flex justify-center">
        <h2 className="text-lg font-bold mb-4">{data.displayName} さんのフォロワー</h2>
      </div>
      <UserList users={data.users} />
    </>
  )
}