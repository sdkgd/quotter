"use client"

import { useState } from "react"
import { login } from "@/lib/actions";
import { useRouter } from "next/navigation";
import ButtonPost from "../element/buttonpost";

export default function LoginForm(){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);

  const tryLogin = async (data:FormData) =>{
    try{
      await login(data);
      router.push("/quoot");      
    }catch(e){
      setError((e as Error).message);
    }
  }

  return(
    <>
      <form action={tryLogin}>
        <div className="mb-4 block mx-auto w-1/2">
          <label className="text-sm font-medium text-gray-700 block mb-1">Email</label>
          <input
              name="email"
              id="email"
              type="email"
              className="w-full bg-gray-100 border border-gray-200 rounded-lg p-2.5 block focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
              required
              autoFocus
          />
        </div>
        
        <div className="mb-4 block mx-auto w-1/2">
          <label className="text-sm font-medium text-gray-700 block mb-1">Password</label>
          <input
              name="password"
              id="password"
              type="password"
              className="w-full bg-gray-100 border border-gray-200 rounded-lg p-2.5 block focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
              required
              autoComplete="current-password"
          />
        </div>

        <div className="flex justify-center">
          {error && <p className="text-red-500">{error}</p>}
        </div>
        
        <div className="flex justify-center">
          <ButtonPost id="login" description="Login" />
        </div>
      </form>
      
    </>
  )
}