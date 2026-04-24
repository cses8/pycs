import AutoImport from 'unplugin-auto-import/vite'

const backendBaseUrl = process.env.NUXT_PUBLIC_BACKEND_BASE_URL || 'http://localhost:8000'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  runtimeConfig: {
    public: {
      // Define your API base URL variable
      // It reads from an environment variable NUXT_PUBLIC_API_BASE_URL
      // Provides a default fallback if the env var isn't set (optional but good practice)
      backendBase: backendBaseUrl,
    },
  },

  app: {
    head: {
      title: 'PYCS', // default fallback title
      htmlAttrs: {
        lang: 'en',
      },
      link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
    },
  },

  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },

  modules: [
    '@nuxtjs/tailwindcss',
    '@nuxt/eslint',
    '@nuxt/icon',
    '@nuxt/devtools',
    '@vueuse/nuxt',
    '@primevue/nuxt-module',
    '@vueuse/motion/nuxt',
    'dayjs-nuxt',
    '@pinia/nuxt',
    'nuxt-auth-sanctum',
    'nuxt-delay-hydration',
    'nuxt-delay-hydration',
    '@nuxtjs/fontaine',
    '@nuxtjs/critters',
  ],

  css: [
    // '~/assets/css/main.css',
    '~/assets/css/font.css',
    '~/assets/styles/_index.scss',
    '~/assets/themes/page-themes-with-layout.css',
    'primeicons/primeicons.css',
    'v-calendar/style.css',
  ],

  primevue: {
    importTheme: { from: '~/themes/my-theme.js' },
    directives: {
      /**/
    },
    components: {
      include: ['Button', 'Menu'],
    },
  },

  vite: {
    build: {
      minify: 'terser',
      terserOptions: {
        toplevel: true,
        compress: {
          drop_console: true,
        },
        mangle: {
          toplevel: true,
        },
      },
    },
    plugins: [
      // AutoImport({
      //   // targets to transform
      //   include: [
      //     /\.[tj]sx?$/, // .ts, .tsx, .js, .jsx
      //     /\.vue$/,
      //     /\.vue\?vue/, // .vue
      //     /\.vue\.[tj]sx?\?vue/, // .vue (vue-loader with experimentalInlineMatchResource enabled)
      //     /\.md$/, // .md
      //   ],
      // }),
    ],
  },

  build: {
    analyze: {
      template: 'treemap',
      brotliSize: true,
      gzipSize: true,
    },
  },

  icon: {
    mode: 'css',
  },

  nitro: {
    devProxy: {
      '/api': `${backendBaseUrl}/api`,
      '/storage': `${backendBaseUrl}/storage`,
    },
  },

  pinia: {
    storesDirs: ['./stores/app/**'],
  },

  sanctum: {
    baseUrl: backendBaseUrl,
  },

  delayHydration: {
    mode: 'init',
    // enables nuxt-delay-hydration in dev mode for testing
    // NOTE: you should disable this once you've finished testing, it will break HMR
    // debug: process.env.NODE_ENV === 'development',
  },

  // Web Vitals

  // Fontaine
  fontMetrics: {
    fonts: [
      {
        family: 'Poppins',
        fallbacks: ['Georgia'],
        fallbackName: 'fallback-poppins',
        src: 'poppins.ttf',
        root: 'assets',
      },
    ],
  },
})
