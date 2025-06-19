"use client"

import { useState } from "react"
import { useRouter } from "next/navigation";
import { login } from "@/lib/actions";

export default function Page(){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);

  const tryLogin = async (data:FormData) =>{
    try{
      await login(data);
      router.push("/auth");      
    }catch(e){
      setError((e as Error).message);
    }
  }

  return(
    <>
      <form action={tryLogin}>
        <label>Email</label>
        <input
            id="email"
            type="email"
            name="email"
            className="block mt-1 bg-gray-100 text-gray-700"
            required
            autoFocus
        />

        <label>Password</label>
        <input
            id="password"
            type="password"
            name="password"
            className="block mt-1 bg-gray-100 text-gray-700"
            required
            autoComplete="current-password"
        />

        <div>
          {error && <p className="text-red-500">{error}</p>}
        </div>

        <button type="submit">ログイン</button>

      </form>
      
    </>
  )
}