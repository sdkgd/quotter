export type image = {
  id:number,
  path: string,
}

export type quser = {
  id:number,
  user_name:string,
  display_name:string,
  profile:string,
  profile_image_id:number,
  image?:image,
}

export type quoot = {
  id:number,
  user_id:number,
  content: string,
  created_at: string,
  updated_at: string,
  quser?:quser,
}

export type message = {
  id: number,
  chat_id: number,
  mentioned_user_id: number,
  content: string,
  created_at: string,
  updated_at: string,
}

export type chat = {
  chatId: number,
  users: string[],
  messages: message[],
}