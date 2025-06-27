import Guest from "@/components/guest";
import Title from "@/components/navigation/title";
import RegisterForm from "@/components/register/registerform";

export default function Page(){
  return(
    <>
      <Title />
      <Guest>
        <RegisterForm />
      </Guest>
    </>
  )
}