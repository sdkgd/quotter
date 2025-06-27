import Link from "next/link";

type Props = {
  id: string;
  path: string;
  description: string;
};

export default function LinkGet({id,path,description}:Props){
  return(
    <>
      <Link 
        id={id}
        href={`${path}`}
        className="py-1.5 px-4 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 rounded-lg"
      >
        {description}
      </Link>
    </>
  )
}