import Link from 'next/link'
 
export default function Forbidden() {
  return (
    <>
      <div className="flex justify-center">
          <h2 id="forbidden" className="text-3xl font-bold m-4">403 Forbidden</h2>
      </div>
      <div className="flex justify-center">
          <Link href="/quoot">Return Home</Link>
      </div>
    </>
  )
}