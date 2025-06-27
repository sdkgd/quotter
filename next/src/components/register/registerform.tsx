"use client"

import { useState } from "react"
import { register } from "@/lib/actions";
import { useRouter } from "next/navigation";
import ButtonPost from "../element/buttonpost";

export default function RegisterForm(){
  const router = useRouter();
  const [error,setError] = useState<string|null>(null);

  const [userName, setUserName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');

  const tryRegister = async (data:FormData) =>{
    const pas1 = data.get("password");
    const pas2 = data.get("confirm_password");
    if(pas1===pas2){
      try{
        await register(data);
        router.push("/quoot");   
      }catch(e){
        setError((e as Error).message);
      }
    }else{
      setError("パスワード入力が異なります");
    }
  }

  return(
    <>
      <form action={tryRegister}>
        <div className="mb-4 block mx-auto w-1/2">
          <label className="text-sm font-medium text-gray-700 block mb-1">User name</label>
          <input
              name="user_name"
              id="user_name"
              type="text"
              className="w-full bg-gray-100 border border-gray-200 rounded-lg p-2.5 block focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
              onChange={e => setUserName(e.target.value)}
              value={userName}
              required
              autoFocus
          />
        </div>

        <div className="mb-4 block mx-auto w-1/2">
          <label className="text-sm font-medium text-gray-700 block mb-1">Email</label>
          <input
              name="email"
              id="email"
              type="email"
              className="w-full bg-gray-100 border border-gray-200 rounded-lg p-2.5 block focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
              onChange={e => setEmail(e.target.value)}
              value={email}
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
              onChange={e => setPassword(e.target.value)}
              value={password}
              required
              autoComplete="current-password"
          />
        </div>

        <div className="mb-4 block mx-auto w-1/2">
          <label className="text-sm font-medium text-gray-700 block mb-1">Confirm password</label>
          <input
              name="confirm_password"
              id="confirm_password"
              type="password"
              className="w-full bg-gray-100 border border-gray-200 rounded-lg p-2.5 block focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
              onChange={e => setConfirmPassword(e.target.value)}
              value={confirmPassword}
              required
              autoComplete="current-password"
          />
        </div>

        <div className="flex justify-center">
          {error && <p className="text-red-500">{error}</p>}
        </div>
        
        <div className="flex justify-center">
          <ButtonPost id="register" description="Register" />
        </div>
      </form>
      
    </>
  )
}