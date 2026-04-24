declare type Navs = {
  label: string
  to?: string
  description?: string
  subMenu?: NavsSubMenu[]
}

declare type NavsSubMenu = {
  label: string
  description: string
  icon: string
  to: string
}
