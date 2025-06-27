import Auth from "@/components/auth";
import UpdateForm from "@/components/quoot/updateform";
import { canUpdateQuoot } from "@/lib/actions";
import { redirect } from "next/navigation";

type Props={
  params:Promise<{quootId:number}>;
};

export default async function Page({params}:Props){
  let data;
  try{
    const res = await canUpdateQuoot((await params).quootId);
    data = res;
  }catch(e){
    console.log((e as Error).message);
    redirect("/error/403");
  }

  return(
    <>
      <Auth>
        <UpdateForm quootId={data.id} content={data.content} />
      </Auth>
    </>
  )
}