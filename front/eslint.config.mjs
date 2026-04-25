// @ts-check
import withNuxt from './.nuxt/eslint.config.mjs'

export default withNuxt(
  {
    rules: {
      '@typescript-eslint/no-explicit-any': 'warn',
      '@typescript-eslint/no-unused-vars': ['warn', { argsIgnorePattern: '^_', varsIgnorePattern: '^_' }],
      '@typescript-eslint/no-unused-expressions': 'warn',
      '@typescript-eslint/no-wrapper-object-types': 'warn',
      '@typescript-eslint/consistent-type-imports': 'off',
      '@typescript-eslint/no-import-type-side-effects': 'off',
      'nuxt/prefer-import-meta': 'warn',
      'vue/no-mutating-props': 'warn',
      'vue/no-multiple-template-root': 'warn',
      'vue/attribute-hyphenation': 'warn',
      'vue/attributes-order': 'warn',
      'vue/html-self-closing': 'warn',
      'vue/require-default-prop': 'warn',
      'vue/require-prop-types': 'warn',
      'vue/v-on-event-hyphenation': 'warn',
    },
  },
)
