"use client"
import Link from 'next/link'
 
export default function Error() {
  return (
    <>
      <div className="flex justify-center">
          <h2 className="text-3xl font-bold m-4">500 Server Error</h2>
      </div>
      <div className="flex justify-center">
          <Link href="/quoot">Return Home</Link>
      </div>
    </>
  )
}