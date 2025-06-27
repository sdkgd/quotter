import Guest from "@/components/guest";
import LoginForm from "@/components/login/loginform";
import Title from "@/components/navigation/title";

export default function Page() {
  return(
    <>
      <Title />
      <Guest>
        <LoginForm />
      </Guest>
    </>
  )
}