import { logout } from "@/lib/actions";
import { errorRedirect } from "@/lib/navigations";
import { redirect } from "next/navigation";

export default function LogoutForm(){
  const tryLogout = async () =>{
    "use server";
    try{
      await logout();
    }catch(e){
      await errorRedirect((e as Error & { statusCode?: number }).statusCode);
      throw new Error("予期せぬエラーが発生しました");
    }
    redirect("/login");
  }
  
  return(
    <>
      <form action={tryLogout}>
        <button type="submit" name="logout" id="logout" className="text-center text-gray-500 hover:text-black">Logout</button>
      </form>
    </>
  )
}