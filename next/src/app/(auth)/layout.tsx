import { Suspense } from "react";

export default function Layout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return(
    <div className="flex justify-center">
      <div className="max-w-screen-md w-full pl-8 pr-8">
        <Suspense fallback={<p>Loading...</p>}>
          {children}
        </Suspense>
      </div>
    </div>
  )
}