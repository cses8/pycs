declare type SchoolYear = {
  id: number
  description: string
  created_at?: string
  updated_at?: string
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
  school_year_id: number | null
  start: string
  end: string
  image: string
  title: string
  description: string
  created_at?: string
  updated_at?: string
}

declare type SchoolUpdate = {
  id: number
  author_id: number | null
  author_name: string | null
  title: string
  slug: string
  summary: string | null
  content: string
  type: 'news' | 'announcement' | 'blog' | 'event'
  category: string | null
  tags: string[]
  status: 'draft' | 'scheduled' | 'published' | 'archived'
  published_at: string | null
  event_start_at: string | null
  event_end_at: string | null
  featured_image: string | null
  featured_image_url: string | null
}
