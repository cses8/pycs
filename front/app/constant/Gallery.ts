const dayjs = useDayjs()

export const GALLERY: Gallery = {
  id: randomIntRange(1, 9999999999),
  title: '',
  description: '',
  start: dayjs().startOf('day').format('YYYY-MM-DD HH:mm:ss'),
  end: dayjs().endOf('day').format('YYYY-MM-DD HH:mm:ss'),
  image_count: 0,
}
