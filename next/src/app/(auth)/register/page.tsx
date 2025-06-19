"use client"

import { useState } from "react"
import { register } from "@/lib/actions";
import { useRouter } from "next/navigation";

export default function Page(){
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
        router.push("/auth");   
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
        <div>
          <label>User name</label>
          <input
              name="user_name"
              id="user_name"
              type="text"
              className="block mt-1 bg-gray-100 text-gray-700"
              onChange={e => setUserName(e.target.value)}
              value={userName}
              required
              autoFocus
          />
        </div>

        <div>
          <label>Email</label>
          <input
              name="email"
              id="email"
              type="email"
              className="block mt-1 bg-gray-100 text-gray-700"
              onChange={e => setEmail(e.target.value)}
              value={email}
              required
              autoFocus
          />
        </div>
        
        <div>
          <label>Password</label>
          <input
              name="password"
              id="password"
              type="password"
              className="block mt-1 bg-gray-100 text-gray-700"
              onChange={e => setPassword(e.target.value)}
              value={password}
              required
              autoComplete="current-password"
          />
        </div>

        <div>
          <label>Confirm password</label>
          <input
              name="confirm_password"
              id="confirm_password"
              type="password"
              className="block mt-1 bg-gray-100 text-gray-700"
              onChange={e => setConfirmPassword(e.target.value)}
              value={confirmPassword}
              required
              autoComplete="current-password"
          />
        </div>

        <div>
          {error && <p className="text-red-500">{error}</p>}
        </div>
        
        <div>
          <button type="submit">登録</button>
        </div>
      </form>
      
    </>
  )
}