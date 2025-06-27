import { getUserData } from "@/lib/actions";
import Link from "next/link";
import LogoutForm from "../logout/logoutform";

export default async function Header(){
  let isLogin:boolean = false;
  let userName;
  const tryGetUserData = async() =>{
    try{
      const res = await getUserData();
      isLogin = true;
      userName = res.user_name;
    }catch(e){
      console.log((e as Error).message);
    }
  }
  await tryGetUserData();

  return(
    <>
      <div className="relative">
        <header className="fixed bg-gray-200 max-w-screen-md w-full pl-8 pr-8 h-24 opacity-90">
        
          <div className="flex justify-between items-center p-6">

            <Link href="/quoot">
              <h1 className="left-1 text-center text-black text-4xl font-bold">Quotter</h1>
            </Link>

            {isLogin ?
              <nav>
                <ul className="flex space-x-4">
                  <li><Link href="/quoot/create" className="text-center text-gray-500 hover:text-black">Create</Link></li>
                  <li><Link href={`/user/${userName}`} className="text-center text-gray-500 hover:text-black">My page</Link></li>
                  <li><LogoutForm /></li>
                </ul>
              </nav>
            :
              <nav>
                <ul className="flex space-x-4">
                  <li><Link href="/login" id="login" className="text-center text-gray-500 hover:text-black">Login</Link></li>
                  <li><Link href="/register" id="register" className="text-center text-gray-500 hover:text-black">Register</Link></li>
                </ul>
              </nav>
            }

          </div>
          
        </header>
      </div>
    </>
  )
}