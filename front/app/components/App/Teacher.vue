<template>
  <div
    class="relative flex h-[480px] w-full flex-col items-center justify-center overflow-hidden rounded-lg border bg-background md:shadow-xl"
  >
    <div class="mx-auto text-6xl my-[2rem] text-center">
      <span class="font-semibold text-blue-600 dark:text-blue-600/70">
        Meet Our
      </span>
      <UiFlipWords
        :words="['Leaders', 'Faculty', 'Teachers']"
        :duration="3000"
        class="text-6xl font-semibold !max-w-[15rem] !min-w-[1rem]"
      />
    </div>

    <!-- First Marquee -->
    <UiMarquee pause-on-hover class="[--duration:100s]">
      <UiReviewCard
        v-for="review in firstRow"
        :key="review.username"
        :img="review.img"
        :name="review.name"
        :username="review.username"
        :body="review.body"
      />
    </UiMarquee>

    <!-- Second Marquee (reverse) -->
    <UiMarquee reverse pause-on-hover class="[--duration:100s]">
      <UiReviewCard
        v-for="review in secondRow"
        :key="review.username"
        :img="review.img"
        :name="review.name"
        :username="review.username"
        :body="review.body"
      />
    </UiMarquee>

    <!-- Third Marquee -->
    <UiMarquee pause-on-hover class="[--duration:100s] !pb-4">
      <UiReviewCard
        v-for="review in thirdRow"
        :key="review.username"
        :img="review.img"
        :name="review.name"
        :username="review.username"
        :body="review.body"
      />
    </UiMarquee>

    <!-- Left Gradient -->
    <div
      class="pointer-events-none absolute inset-y-0 left-0 w-1/3 bg-gradient-to-r from-white dark:from-background"
    />

    <!-- Right Gradient -->
    <div
      class="pointer-events-none absolute inset-y-0 right-0 w-1/3 bg-gradient-to-l from-white dark:from-background"
    />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

// Reviews data
const reviews = [
  {
    name: 'Albert Einstein',
    username: '@alberteinstein',
    body: 'Physics',
    img: 'https://avatar.vercel.sh/alberteinstein',
  },
  {
    name: 'Marie Curie',
    username: '@mariecurie',
    body: 'Chemistry',
    img: 'https://avatar.vercel.sh/mariecurie',
  },
  {
    name: 'Isaac Newton',
    username: '@isaacnewton',
    body: 'Mathematics',
    img: 'https://avatar.vercel.sh/isaacnewton',
  },
  {
    name: 'Ada Lovelace',
    username: '@adalovelace',
    body: 'Computer Science',
    img: 'https://avatar.vercel.sh/adalovelace',
  },
  {
    name: 'Charles Darwin',
    username: '@charlesdarwin',
    body: 'Biology',
    img: 'https://avatar.vercel.sh/charlesdarwin',
  },
  {
    name: 'Jane Austen',
    username: '@janeausten',
    body: 'Literature',
    img: 'https://avatar.vercel.sh/janeausten',
  },
  {
    name: 'William Shakespeare',
    username: '@williamshakespeare',
    body: 'Drama',
    img: 'https://avatar.vercel.sh/williamshakespeare',
  },
  {
    name: 'Vincent van Gogh',
    username: '@vincentvangogh',
    body: 'Art History',
    img: 'https://avatar.vercel.sh/vincentvangogh',
  },
  {
    name: 'Wolfgang Amadeus Mozart',
    username: '@wolfgangmozart',
    body: 'Music Theory',
    img: 'https://avatar.vercel.sh/wolfgangmozart',
  },
  {
    name: ' महात्मा Gandhi',
    username: '@mahatmagandhi',
    body: 'Civics',
    img: 'https://avatar.vercel.sh/mahatmagandhi',
  },
  {
    name: 'Stephen Hawking',
    username: '@stephenhawking',
    body: 'Astrophysics',
    img: 'https://avatar.vercel.sh/stephenhawking',
  },
  {
    name: 'Rosalind Franklin',
    username: '@rosalindfranklin',
    body: 'Molecular Biology',
    img: 'https://avatar.vercel.sh/rosalindfranklin',
  },
  {
    name: 'Alan Turing',
    username: '@alanturing',
    body: 'Theoretical Computer Science',
    img: 'https://avatar.vercel.sh/alanturing',
  },
  {
    name: 'Mary Somerville',
    username: '@marysomerville',
    body: 'Mathematics and Astronomy',
    img: 'https://avatar.vercel.sh/marysomerville',
  },
  {
    name: 'Galileo Galilei',
    username: '@galileogalilei',
    body: 'Physics and Astronomy',
    img: 'https://avatar.vercel.sh/galileogalilei',
  },
  {
    name: 'Emily Dickinson',
    username: '@emilydickinson',
    body: 'Poetry',
    img: 'https://avatar.vercel.sh/emilydickinson',
  },
  {
    name: 'Mark Twain',
    username: '@marktwain',
    body: 'American Literature',
    img: 'https://avatar.vercel.sh/marktwain',
  },
  {
    name: 'Leonardo da Vinci',
    username: '@leonardodavinci',
    body: 'Art and Science',
    img: 'https://avatar.vercel.sh/leonardodavinci',
  },
  {
    name: 'Ludwig van Beethoven',
    username: '@ludwigbeethoven',
    body: 'Classical Music',
    img: 'https://avatar.vercel.sh/ludwigbeethoven',
  },
  {
    name: 'Nelson Mandela',
    username: '@nelsonmandela',
    body: 'History',
    img: 'https://avatar.vercel.sh/nelsonmandela',
  },
  {
    name: 'Neil deGrasse Tyson',
    username: '@neildegrassetyson',
    body: 'Astrophysics',
    img: 'https://avatar.vercel.sh/neildegrassetyson',
  },
  {
    name: 'Jennifer Doudna',
    username: '@jenniferdoudna',
    body: 'Biochemistry',
    img: 'https://avatar.vercel.sh/jenniferdoudna',
  },
  {
    name: 'Linus Torvalds',
    username: '@linustorvalds',
    body: 'Operating Systems',
    img: 'https://avatar.vercel.sh/linustorvalds',
  },
  {
    name: 'Grace Hopper',
    username: '@gracehopper',
    body: 'Computer Programming',
    img: 'https://avatar.vercel.sh/gracehopper',
  },
  {
    name: 'Edwin Hubble',
    username: '@edwinhubble',
    body: 'Cosmology',
    img: 'https://avatar.vercel.sh/edwinhubble',
  },
  {
    name: 'Virginia Woolf',
    username: '@virginiawoolf',
    body: 'Modern Literature',
    img: 'https://avatar.vercel.sh/virginiawoolf',
  },
  {
    name: 'Ernest Hemingway',
    username: '@ernesthemingway',
    body: '20th Century Literature',
    img: 'https://avatar.vercel.sh/ernesthemingway',
  },
  {
    name: 'Pablo Picasso',
    username: '@pablopicasso',
    body: 'Modern Art',
    img: 'https://avatar.vercel.sh/pablopicasso',
  },
  {
    name: 'Johann Sebastian Bach',
    username: '@johannbach',
    body: 'Baroque Music',
    img: 'https://avatar.vercel.sh/johannbach',
  },
  {
    name: 'Martin Luther King Jr.',
    username: '@martinlutherking',
    body: 'Civil Rights',
    img: 'https://avatar.vercel.sh/martinlutherking',
  },
  {
    name: 'Carl Sagan',
    username: '@carlsagan',
    body: 'Astronomy',
    img: 'https://avatar.vercel.sh/carlsagan',
  },
  {
    name: 'Jane Goodall',
    username: '@janegoodall',
    body: 'Primatology',
    img: 'https://avatar.vercel.sh/janegoodall',
  },
  {
    name: 'Tim Berners-Lee',
    username: '@timbernerslee',
    body: 'World Wide Web',
    img: 'https://avatar.vercel.sh/timbernerslee',
  },
  {
    name: 'Katherine Johnson',
    username: '@katherinejohnson',
    body: 'Mathematics',
    img: 'https://avatar.vercel.sh/katherinejohnson',
  },
  {
    name: 'Stephen Hawking',
    username: '@stephenhawking',
    body: 'Theoretical Physics',
    img: 'https://avatar.vercel.sh/stephenhawking',
  },
  {
    name: 'Marie Curie',
    username: '@mariecurie',
    body: 'Radiochemistry',
    img: 'https://avatar.vercel.sh/mariecurie',
  },
  {
    name: 'Isaac Newton',
    username: '@isaacnewton',
    body: 'Classical Mechanics',
    img: 'https://avatar.vercel.sh/isaacnewton',
  },
  {
    name: 'Ada Lovelace',
    username: '@adalovelace',
    body: 'Computer Programming',
    img: 'https://avatar.vercel.sh/adalovelace',
  },
  {
    name: 'Charles Darwin',
    username: '@charlesdarwin',
    body: 'Evolutionary Biology',
    img: 'https://avatar.vercel.sh/charlesdarwin',
  },
  {
    name: 'Jane Austen',
    username: '@janeausten',
    body: 'British Literature',
    img: 'https://avatar.vercel.sh/janeausten',
  },
  {
    name: 'William Shakespeare',
    username: '@williamshakespeare',
    body: 'Playwriting',
    img: 'https://avatar.vercel.sh/williamshakespeare',
  },
  {
    name: 'Vincent van Gogh',
    username: '@vincentvangogh',
    body: 'Post-Impressionism',
    img: 'https://avatar.vercel.sh/vincentvangogh',
  },
  {
    name: 'Wolfgang Amadeus Mozart',
    username: '@wolfgangmozart',
    body: 'Classical Music Composition',
    img: 'https://avatar.vercel.sh/wolfgangmozart',
  },
  {
    name: ' महात्मा Gandhi',
    username: '@mahatmagandhi',
    body: 'Philosophy',
    img: 'https://avatar.vercel.sh/mahatmagandhi',
  },
  {
    name: 'Albert Einstein',
    username: '@alberteinstein',
    body: 'Relativity',
    img: 'https://avatar.vercel.sh/alberteinstein',
  },
  {
    name: 'Stephen Hawking',
    username: '@stephenhawking',
    body: 'Cosmology',
    img: 'https://avatar.vercel.sh/stephenhawking',
  },
  {
    name: 'Marie Curie',
    username: '@mariecurie',
    body: 'Radioactivity',
    img: 'https://avatar.vercel.sh/mariecurie',
  },
  {
    name: 'Isaac Newton',
    username: '@isaacnewton',
    body: 'Calculus',
    img: 'https://avatar.vercel.sh/isaacnewton',
  },
  {
    name: 'Ada Lovelace',
    username: '@adalovelace',
    body: 'Algorithms',
    img: 'https://avatar.vercel.sh/adalovelace',
  },
]

// Split reviews into two rows
const firstRow = ref(reviews.slice(0, reviews.length / 3))
const secondRow = ref(reviews.slice(reviews.length / 3))
const thirdRow = ref(reviews.slice(reviews.length / 3))
</script>
