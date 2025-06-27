import { getUserData } from "@/lib/actions";
import { redirect } from "next/navigation";
import { ReactNode } from "react";

type Props = {
  children: ReactNode;
};

export default async function Auth({children}:Props){
  let isLogin:boolean = false;
  const tryGetUserData = async() =>{
    try{
      await getUserData();
      isLogin = true;
    }catch(e){
      console.log((e as Error).message);
    }
  }
  await tryGetUserData();
  if(!isLogin) redirect("/login");
  return children;
}