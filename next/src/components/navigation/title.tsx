import Link from "next/link";

export default function Title(){
  return(
    <Link href="/quoot">
      <h1 className="text-center text-blue-500 text-4xl font-bold mt-8 mb-8">Quotter</h1>
    </Link>
  )
}