type Props = {
  id: string;
  description: string;
};

export default function ButtonPost({id,description}:Props){
  return(
    <button 
      type="submit"
      id={id}
      name="post"
      className="py-1.5 px-4 bg-gray-50 hover:bg-gray-100 active:bg-gray-200 rounded-lg ml-4 mt-4 mb-4">
        {description}
    </button>
  )
}