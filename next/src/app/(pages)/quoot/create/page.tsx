import Auth from "@/components/auth";
import PostForm from "@/components/quoot/postform";

export default function Page(){
  return(
    <>
      <Auth>
        <PostForm />
      </Auth>
    </>
  )
}