"use client"

import Image from 'next/image'

type props ={
  path: string;
  size: number;
}

type ImageLoaderProps ={
  src: string;
}

const imageLoader = ({src}:ImageLoaderProps) =>{
  if(process.env.NODE_ENV==="production" || process.env.APP_ENV==="ci") return `${src}`;
  else return `http://localhost:8080/storage/app/public/${src}`;
}

export default function ImageFrame({path,size}:props){
  return(
    <>
      <Image loader={imageLoader} src={path} priority alt="profile image" width={size} height={size} className="object-contain" />
    </>
  )
}