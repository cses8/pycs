declare type SchoolYear = {
  id: string
  description: string
}

declare type Gallery = {
  id: number
  title: string
  description: string
  start: string
  end: string
  image_count: number
}

declare type User = {
  id: number
  name: string
}

declare type Announcement = {
  id: number
  title: string
  description: string
  start: string
  end: string
}

declare type SchoolCalendar = {
  id: number
  school_year_id: string
  start: string
  end: string
  image: string
  title: string
  description: string
}
