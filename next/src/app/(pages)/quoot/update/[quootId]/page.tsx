import Auth from "@/components/auth";
import UpdateForm from "@/components/quoot/updateform";
import { canUpdateQuoot } from "@/lib/actions";
import { errorRedirect } from "@/lib/navigations";

type Props={
  params:Promise<{quootId:number}>;
};

export default async function Page({params}:Props){
  let data;
  try{
    const res = await canUpdateQuoot((await params).quootId);
    data = res;
  }catch(e){
    await errorRedirect((e as Error & { statusCode?: number }).statusCode);
    throw new Error("予期せぬエラーが発生しました");
  }

  return(
    <>
      <Auth>
        <UpdateForm quootId={data.id} content={data.content} />
      </Auth>
    </>
  )
}