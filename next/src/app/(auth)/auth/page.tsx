import { getUserData, logout } from "@/lib/actions";
import { redirect } from "next/navigation";

export default async function Page(){
  try{
    await getUserData();   
  }catch(e){
    console.log((e as Error).message);
    redirect("/login");
  }

  const tryLogout = async () =>{
    "use server";
    try{
      await logout();
    }catch(e){
      console.log((e as Error).message);
    }
    redirect("/login");
  }

  return(
    <>
      <p>認証成功</p>
      <form action={tryLogout}>
        <button type="submit">Logout</button>
      </form>
    </>
  )
}