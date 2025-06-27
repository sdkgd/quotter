import { logout } from "@/lib/actions";
import { redirect } from "next/navigation";

export default function LogoutForm(){
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
      <form action={tryLogout}>
        <button type="submit" name="logout" id="logout" className="text-center text-gray-500 hover:text-black">Logout</button>
      </form>
    </>
  )
}