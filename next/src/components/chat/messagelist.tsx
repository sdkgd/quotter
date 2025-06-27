import { message } from "@/types/types";
import React from "react";

type Props = {
  userId: number;
  messages: message[];
}

export default function MessageList({userId,messages}:Props){
  return(
    <div id="scrollTarget" className="bg-white rounded-md shadow-lg mt-5 mb-5 p-4 overflow-auto max-h-80">
      <ul>
        {messages?.map((message:message)=>(
          <React.Fragment key={message.id}>
            {(userId && userId===message.mentioned_user_id)?
              <div className="mb-2">
                <div className="flex justify-end">
                  <div className="p-4 bg-blue-300 rounded-md max-w-md">
                    <li id="message" className="">
                      {message.content} 
                    </li>
                  </div>
                </div>
                <p className="text-xs text-right text-slate-400">{(()=>{
                  const str:string = `${message.created_at}`;
                  const str1:string = str.substring(0,10);
                  const str2:string = str.substring(11,19);
                  return str1+" "+str2;
                })()}</p>
              </div>
            :''}
            {(userId && userId!==message.mentioned_user_id)?
              <div className="mb-2">
                <div className="flex justify-start">
                  <div className="p-4 bg-gray-300 rounded-md max-w-md">
                    <li id="message" className="">
                      {message.content} 
                    </li>
                  </div>
                </div>
                <p className="text-xs text-left text-slate-400">{(()=>{
                  const str:string = `${message.created_at}`;
                  const str1:string = str.substring(0,10);
                  const str2:string = str.substring(11,19);
                  return str1+" "+str2;
                })()}</p>
              </div>
            :''}            
          </React.Fragment>
        ))}
      </ul>     
    </div>
  )
}