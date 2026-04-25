import { mount } from '@vue/test-utils'
import { describe, expect, it } from 'vitest'

import AppSafeHtml from '../../app/components/App/SafeHtml.vue'

describe('AppSafeHtml', () => {
  it('renders allowed rich text as HTML', () => {
    const wrapper = mount(AppSafeHtml, {
      props: {
        html: '<p><strong>Announcement</strong> <em>details</em></p><ul><li>Bring ID</li></ul>',
      },
    })

    expect(wrapper.find('strong').text()).toBe('Announcement')
    expect(wrapper.find('em').text()).toBe('details')
    expect(wrapper.find('li').text()).toBe('Bring ID')
    expect(wrapper.text()).not.toContain('<strong>')
  })

  it('preserves safe Quill formatting classes from PrimeVue Editor output', () => {
    const wrapper = mount(AppSafeHtml, {
      props: {
        html: '<p class="ql-align-center ql-indent-1 unsafe-class">Centered</p><span class="ql-size-large unsafe-class">Large</span>',
      },
    })

    const paragraph = wrapper.find('p')
    const span = wrapper.find('span')
    expect(paragraph.classes()).toContain('ql-align-center')
    expect(paragraph.classes()).toContain('ql-indent-1')
    expect(paragraph.classes()).not.toContain('unsafe-class')
    expect(span.text()).toBe('Large')
    expect(span.classes()).toContain('ql-size-large')
    expect(span.classes()).not.toContain('unsafe-class')
  })

  it('removes script tags and unsafe URL schemes', () => {
    const wrapper = mount(AppSafeHtml, {
      props: {
        html: '<p>Allowed</p><script>alert("xss")</script><a href="javascript:alert(1)">bad</a>',
      },
    })

    expect(wrapper.html()).toContain('<p>Allowed</p>')
    expect(wrapper.html()).not.toContain('<script')
    expect(wrapper.html()).not.toContain('javascript:')
  })

  it('blocks data URI payloads and obfuscated dangerous URL schemes', () => {
    const wrapper = mount(AppSafeHtml, {
      props: {
        html: [
          '<a href="data:text/html,<script>alert(1)</script>">data link</a>',
          '<img src="data:image/svg+xml,<svg onload=alert(1)>">',
          '<a href="d a t a:text/html,<script>alert(1)</script>">spaced data link</a>',
          '<a href="java&#x73;cript:alert(1)">entity script link</a>',
          '<a href="/school-updates">safe relative link</a>',
          '<img src="/images/logo.webp" alt="Logo">',
        ].join(''),
      },
    })

    const links = wrapper.findAll('a')
    const images = wrapper.findAll('img')

    expect(links[0]?.attributes('href')).toBeUndefined()
    expect(images[0]?.attributes('src')).toBeUndefined()
    expect(links[1]?.attributes('href')).toBeUndefined()
    expect(links[2]?.attributes('href')).toBeUndefined()
    expect(links[3]?.attributes('href')).toBe('/school-updates')
    expect(images[1]?.attributes('src')).toBe('/images/logo.webp')
    expect(wrapper.html()).not.toContain('data:')
    expect(wrapper.html()).not.toContain('javascript:')
  })

  it('removes unsafe attributes from allowed tags', () => {
    const wrapper = mount(AppSafeHtml, {
      props: {
        html: '<img src="/images/logo.webp" alt="Logo" onerror="alert(1)"><p onclick="alert(1)">Text</p>',
      },
    })

    expect(wrapper.find('img').attributes('src')).toBe('/images/logo.webp')
    expect(wrapper.html()).not.toContain('onerror')
    expect(wrapper.html()).not.toContain('onclick')
  })

  it('decodes editor entities before rendering sanitized HTML', () => {
    const wrapper = mount(AppSafeHtml, {
      props: {
        html: '&lt;p&gt;Welcome&nbsp;&amp;&nbsp;<strong>orientation</strong>&lt;/p&gt;',
      },
    })

    expect(wrapper.find('p').exists()).toBe(true)
    expect(wrapper.find('strong').text()).toBe('orientation')
    expect(wrapper.text()).toContain('Welcome & orientation')
    expect(wrapper.text()).not.toContain('&nbsp;')
  })
})
