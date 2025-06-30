import { redirect } from "next/navigation";

export const errorRedirect = async(status:number|undefined) =>{
  if(status===401) redirect("/login");
  if(status===403) redirect("/error/403");
  if(status===404) redirect("/error/404");
}