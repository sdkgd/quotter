import Header from "@/components/navigation/header";
import { Suspense } from "react";

export default function Layout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return(
    <div className="flex justify-center">
      <Header />
      <div className="max-w-screen-md w-full pl-8 pr-8">
        <div className="h-24"></div>
        <Suspense fallback={<p>Loading...</p>}>
          {children}
        </Suspense>
      </div>
    </div>
  )
}