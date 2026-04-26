import AutoImport from 'unplugin-auto-import/vite'

process.env.BROWSERSLIST_IGNORE_OLD_DATA ??= '1'

const backendBaseUrl = process.env.NUXT_PUBLIC_BACKEND_BASE_URL || 'http://localhost:8000'
const isProduction = process.env.NODE_ENV === 'production'

// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  srcDir: 'app/',
  ssr: true,

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
  devtools: { enabled: false },

  modules: [
    '@nuxtjs/tailwindcss',
    '@nuxt/eslint',
    '@nuxt/icon',
    '@vueuse/nuxt',
    '@primevue/nuxt-module',
    '@vueuse/motion/nuxt',
    'dayjs-nuxt',
    '@pinia/nuxt',
    'nuxt-auth-sanctum',
    '@nuxtjs/fontaine',
    ...(isProduction
      ? [
          '@nuxtjs/critters',
        ]
      : []),
  ],

  css: [
    // '~/assets/css/main.css',
    '~/assets/css/font.css',
    '~/assets/css/vendor.css',
    '~/assets/styles/_index.scss',
    '~/assets/themes/page-themes-with-layout.css',
  ],

  routeRules: {
    '/**': {
      headers: {
        'X-Content-Type-Options': 'nosniff',
        'X-Frame-Options': 'DENY',
        'Referrer-Policy': 'strict-origin-when-cross-origin',
        'Permissions-Policy': 'camera=(), microphone=(), geolocation=()',
        'Content-Security-Policy': [
          "default-src 'self'",
          "base-uri 'self'",
          "frame-ancestors 'none'",
          "object-src 'none'",
          "script-src 'self' 'unsafe-inline'",
          "style-src 'self' 'unsafe-inline'",
          "img-src 'self' data: blob: http://localhost:8000 http://127.0.0.1:8000",
          `connect-src 'self' ${backendBaseUrl}`,
          "font-src 'self' data:",
        ].join('; '),
      },
    },
  },

  primevue: {
    importTheme: { from: '~/themes/my-theme.js' },
    directives: {
      /**/
    },
    components: {
      include: ['Button', 'Menu'],
    },
  },

  tailwindcss: {
    cssPath: '~/assets/css/tailwind.css',
    configPath: 'tailwind.config',
  },

  vite: {
    optimizeDeps: {
      include: ['quill', 'quill-delta'],
    },
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
    provider: 'none',
    fallbackToApi: false,
    collections: ['solar'],
    serverBundle: false,
    clientBundle: {
      scan: {
        globInclude: ['app/**/*.{vue,ts,js}'],
        globExclude: ['node_modules', '.nuxt', '.output'],
      },
    },
  },

  nitro: {
    devProxy: {
      '/api': `${backendBaseUrl}/api`,
      '/storage': `${backendBaseUrl}/storage`,
    },
  },

  pinia: {
    storesDirs: ['stores/app'],
  },

  sanctum: {
    baseUrl: backendBaseUrl,
    client: {
      initialRequest: false,
    },
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
        root: 'app/assets',
      },
    ],
  },
})
