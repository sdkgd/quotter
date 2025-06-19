import axios from "axios";
import React from "react";

export default async function Page(){
  const res = await axios.get(`${process.env.API_BASE_URL}/api/test`);
  const data = await res.data;

  return(
    <>
      <p>{data.message}</p>
    </>
  )
}