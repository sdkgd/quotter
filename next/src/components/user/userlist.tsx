import Link from "next/link";
import React from "react";
import { quser } from "@/types/types";
import ImageFrame from "../element/imageframe";
import { LOCAL_DEFAULT_IMAGE_URL, S3_DEFAULT_IMAGE_URL } from "@/constants";

type Props = {
  users: quser[];
}

export default function UserList({users}:Props){
  return(
    <>
      <div className="bg-white rounded-md shadow-lg mt-5 mb-5 ">
        <ul>
          {users?.map((user:quser)=>(
            <li key={user.id} className="border-b last:border-0 border-gray-200 p-4">
              
            <div className="flex">
              {user.image? 
                <ImageFrame path={user.image.path} size={60} /> :
                process.env.NODE_ENV==="production"?
                <ImageFrame path={S3_DEFAULT_IMAGE_URL} size={60} />:
                <ImageFrame path={LOCAL_DEFAULT_IMAGE_URL} size={60} />
              }
              <div className="ml-8">
                <p className="text-xl"><Link id="user-display" href={`/user/${user.user_name}`}>{user.display_name}</Link></p>
                <p>{user.profile}</p>
              </div>
            </div>
              
            </li>
          ))}
        </ul>
      </div>
    </>
  )
}