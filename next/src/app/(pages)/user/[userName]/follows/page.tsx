import React from "react";
import Link from "next/link";
import { getFollows } from "@/lib/actions";
import { redirect } from "next/navigation";
import { quser } from "@/types/types";

type Props={
  params:Promise<{userName:string}>;
};

export default async function Page({params}:Props) {
  let data;
  try{
    const res = await getFollows((await params).userName);
    data = res;
  }catch(e){
    console.log((e as Error).message);
    redirect("/login");
  }

  return(
    <>
      <p>{data.displayName}さんがフォロー中</p>
      {data.users?.map((user:quser)=>(
        <React.Fragment key={user.id}>
          <p><Link href={`/user/${user.user_name}`}>{user.display_name}</Link></p>
        </React.Fragment>
      ))}
    </>
  )
}