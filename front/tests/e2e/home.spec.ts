import { expect, test } from '@playwright/test'

test('home page renders the school site', async ({ page }) => {
  await page.setViewportSize({ width: 728, height: 600 })

  const browserErrors: string[] = []
  const blockedConsoleMessages: string[] = []
  const blockedRequests: string[] = []
  const blockedMessagePattern = /sanitize-html|externalized for browser compatibility|Content Security Policy|failed to load icon|api\.iconify\.design|Nuxt DevTools|nuxt-delay-hydration|store installed/i

  page.on('console', (message) => {
    if (message.type() === 'error') {
      const location = message.location()
      browserErrors.push(`${message.text()} (${location.url})`)
    }

    if (blockedMessagePattern.test(message.text())) {
      const location = message.location()
      blockedConsoleMessages.push(`${message.type()}: ${message.text()} (${location.url})`)
    }
  })
  page.on('pageerror', (error) => {
    browserErrors.push(error.message)
  })
  page.on('request', (request) => {
    if (/api\.iconify\.design|api\/_nuxt_icon/i.test(request.url())) {
      blockedRequests.push(request.url())
    }
  })
  await page.route(/\/api\/announcements\/active(?:\?.*)?$/, async (route) => {
    const corsHeaders = {
      'Access-Control-Allow-Headers': 'authorization, content-type',
      'Access-Control-Allow-Methods': 'GET, OPTIONS',
      'Access-Control-Allow-Origin': '*',
    }

    if (route.request().method() === 'OPTIONS') {
      await route.fulfill({
        status: 204,
        headers: corsHeaders,
      })
      return
    }

    await route.fulfill({
      contentType: 'application/json',
      headers: corsHeaders,
      body: JSON.stringify([
        {
          id: 'orientation-test',
          title: 'Orientation Week & Start of Academics',
          description: '&lt;p&gt;Welcome&nbsp;our&nbsp;<strong>students</strong>&nbsp;&amp;&nbsp;parents.&lt;/p&gt;',
          start: '2026-04-24T00:00:00.000000Z',
          end: '2026-04-24T23:59:59.000000Z',
        },
      ]),
    })
  })

  await page.goto('/')

  await expect(page.getByRole('heading', { name: /Experience the Culture of Excellence/i })).toBeVisible()
  await expect(page.locator('body')).toContainText(/Philippine Yuh Chiau|PYCS/i)
  await expect(page.locator('body')).toContainText('Welcome our students & parents.')
  await expect(page.locator('body')).not.toContainText('&nbsp;')
  await expect(page.locator('body')).not.toContainText('&lt;p&gt;')
  expect(await page.evaluate(() => document.documentElement.scrollWidth)).toBeLessThanOrEqual(729)
  expect(browserErrors).toEqual([])
  expect(blockedConsoleMessages).toEqual([])
  expect(blockedRequests).toEqual([])
})
