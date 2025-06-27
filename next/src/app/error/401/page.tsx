import Link from 'next/link'
 
export default function Unauthorized() {
  return (
    <>
      <div className="flex justify-center">
          <h2 id="unauthorized" className="text-3xl font-bold m-4">401 Unauthorized</h2>
      </div>
      <div className="flex justify-center">
          <Link href="/quoot">Return Home</Link>
      </div>
    </>
  )
}