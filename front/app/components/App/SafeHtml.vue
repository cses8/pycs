<!-- eslint-disable vue/no-v-html -->
<template>
  <div class="app-safe-html" v-html="sanitizedHtml" />
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = withDefaults(defineProps<{
  html?: string | null
}>(), {
  html: '',
})

const allowedTags = new Set([
  'p',
  'br',
  'strong',
  'b',
  'em',
  'i',
  'u',
  's',
  'span',
  'ul',
  'ol',
  'li',
  'blockquote',
  'h2',
  'h3',
  'h4',
  'a',
  'img',
  'table',
  'thead',
  'tbody',
  'tr',
  'th',
  'td',
])

const voidTags = new Set(['br', 'img'])

const allowedClasses = new Set([
  'ql-align-center',
  'ql-align-right',
  'ql-align-justify',
  'ql-indent-1',
  'ql-indent-2',
  'ql-indent-3',
  'ql-indent-4',
  'ql-indent-5',
  'ql-indent-6',
  'ql-indent-7',
  'ql-indent-8',
  'ql-size-small',
  'ql-size-large',
  'ql-size-huge',
])

const allowedAttributes: Record<string, Set<string>> = {
  '*': new Set(['class']),
  a: new Set(['href', 'title', 'target', 'rel']),
  img: new Set(['src', 'alt', 'title', 'width', 'height']),
}

const htmlEntities: Record<string, string> = {
  amp: '&',
  apos: "'",
  gt: '>',
  lt: '<',
  nbsp: ' ',
  quot: '"',
}

const blockedUrlSchemes = new Set(['data', 'javascript', 'vbscript'])

const sanitizedHtml = computed(() => sanitizeEditorHtml(props.html ?? ''))

function sanitizeEditorHtml(html: string) {
  const decodedHtml = decodeEditorEntities(html)
  const withoutDangerousBlocks = decodedHtml
    .replace(/<!--[\s\S]*?-->/g, '')
    .replace(/<(script|style|iframe|object|embed|template|meta|link)\b[\s\S]*?<\/\1>/gi, '')

  return withoutDangerousBlocks.replace(
    /<\/?[A-Za-z][^>]*>|[^<]+|</g,
    (token) => {
      if (token === '<') {
        return '&lt;'
      }

      if (!token.startsWith('<')) {
        return escapeHtml(token)
      }

      return sanitizeTag(token)
    }
  )
}

function decodeEditorEntities(value: string) {
  return value.replace(/&(#x[\da-f]+|#\d+|[a-z][a-z0-9]+);/gi, (entity, rawEntity: string) => {
    const normalizedEntity = rawEntity.toLowerCase()

    if (normalizedEntity.startsWith('#x')) {
      return decodeCodePoint(entity, Number.parseInt(normalizedEntity.slice(2), 16))
    }

    if (normalizedEntity.startsWith('#')) {
      return decodeCodePoint(entity, Number.parseInt(normalizedEntity.slice(1), 10))
    }

    return htmlEntities[normalizedEntity] ?? entity
  })
}

function decodeCodePoint(fallback: string, codePoint: number) {
  if (!Number.isFinite(codePoint) || codePoint <= 0) {
    return fallback
  }

  try {
    return String.fromCodePoint(codePoint)
  } catch {
    return fallback
  }
}

function sanitizeTag(token: string) {
  const tagMatch = token.match(/^<\s*(\/?)\s*([A-Za-z][\w:-]*)\b([^>]*)>/)

  if (!tagMatch) {
    return ''
  }

  const closingSlash = tagMatch[1]
  const rawTagName = tagMatch[2]
  const rawAttributes = tagMatch[3] ?? ''

  if (!rawTagName) {
    return ''
  }

  const tagName = rawTagName.toLowerCase()

  if (!allowedTags.has(tagName)) {
    return ''
  }

  if (closingSlash) {
    return voidTags.has(tagName) ? '' : `</${tagName}>`
  }

  const attributes = sanitizeAttributes(tagName, rawAttributes)
  const serializedAttributes = attributes.length ? ` ${attributes.join(' ')}` : ''

  return `<${tagName}${serializedAttributes}>`
}

function sanitizeAttributes(tagName: string, rawAttributes: string) {
  const attributes: string[] = []
  const allowedForTag = allowedAttributes[tagName] ?? new Set<string>()
  const allowedForAll = allowedAttributes['*'] ?? new Set<string>()
  const attributePattern = /([^\s"'=<>`]+)(?:\s*=\s*(?:"([^"]*)"|'([^']*)'|([^\s"'=<>`]+)))?/g
  let attributeMatch: RegExpExecArray | null

  while ((attributeMatch = attributePattern.exec(rawAttributes))) {
    const rawName = attributeMatch[1]
    const doubleQuotedValue = attributeMatch[2]
    const singleQuotedValue = attributeMatch[3]
    const unquotedValue = attributeMatch[4]

    if (!rawName) {
      continue
    }

    const name = rawName.toLowerCase()
    const value = doubleQuotedValue ?? singleQuotedValue ?? unquotedValue ?? ''

    if (!allowedForAll.has(name) && !allowedForTag.has(name)) {
      continue
    }

    if (name === 'class') {
      const safeClasses = value
        .split(/\s+/)
        .filter(className => allowedClasses.has(className))

      if (safeClasses.length) {
        attributes.push(`class="${escapeAttribute(safeClasses.join(' '))}"`)
      }
      continue
    }

    if ((name === 'href' || name === 'src') && !isSafeUrl(value)) {
      continue
    }

    if (name === 'target' && !['_blank', '_self', '_parent', '_top'].includes(value)) {
      continue
    }

    attributes.push(`${name}="${escapeAttribute(value)}"`)
  }

  if (tagName === 'a') {
    attributes.push('rel="noopener noreferrer"')
  }

  return attributes
}

function isSafeUrl(value: string) {
  const trimmedValue = value
    .trim()
    .split('')
    .filter((character) => {
      const code = character.charCodeAt(0)

      return code > 31 && code !== 127 && !/\s/.test(character)
    })
    .join('')

  if (!trimmedValue) {
    return false
  }

  const schemeMatch = trimmedValue.match(/^([a-z][a-z0-9+.-]*):/i)
  const scheme = schemeMatch?.[1]?.toLowerCase()

  if (scheme && blockedUrlSchemes.has(scheme)) {
    return false
  }

  if (/^(https?:|mailto:|\/|#|\.\.?\/)/i.test(trimmedValue)) {
    return true
  }

  return !scheme
}

function escapeHtml(value: string) {
  return value
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
}

function escapeAttribute(value: string) {
  return escapeHtml(value).replace(/"/g, '&quot;')
}
</script>

<style scoped>
.app-safe-html :deep(.ql-align-center) {
  text-align: center;
}

.app-safe-html :deep(.ql-align-right) {
  text-align: right;
}

.app-safe-html :deep(.ql-align-justify) {
  text-align: justify;
}

.app-safe-html :deep(.ql-indent-1) {
  padding-left: 3em;
}

.app-safe-html :deep(.ql-indent-2) {
  padding-left: 6em;
}

.app-safe-html :deep(.ql-indent-3) {
  padding-left: 9em;
}

.app-safe-html :deep(.ql-indent-4) {
  padding-left: 12em;
}

.app-safe-html :deep(.ql-indent-5) {
  padding-left: 15em;
}

.app-safe-html :deep(.ql-indent-6) {
  padding-left: 18em;
}

.app-safe-html :deep(.ql-indent-7) {
  padding-left: 21em;
}

.app-safe-html :deep(.ql-indent-8) {
  padding-left: 24em;
}

.app-safe-html :deep(.ql-size-small) {
  font-size: 0.75em;
}

.app-safe-html :deep(.ql-size-large) {
  font-size: 1.5em;
}

.app-safe-html :deep(.ql-size-huge) {
  font-size: 2.5em;
}
</style>
