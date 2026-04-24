---
version: alpha
name: Clockworx Operations
description: Enterprise workforce management visual system for HR, payroll, attendance, approvals, employee self-service, and admin tooling.
colors:
  primary: "#0F172A"
  secondary: "#334155"
  accent: "#4F46E5"
  success: "#16A34A"
  warning: "#D97706"
  danger: "#DC2626"
  background: "#F8FAFC"
  surface: "#FFFFFF"
  surfaceMuted: "#E2E8F0"
  text: "#0F172A"
  textMuted: "#64748B"
  border: "#CBD5E1"
typography:
  h1:
    fontFamily: Poppins
    fontSize: 3rem
    fontWeight: 700
    lineHeight: 1.05
    letterSpacing: 0
  h2:
    fontFamily: Poppins
    fontSize: 2rem
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: 0
  h3:
    fontFamily: Poppins
    fontSize: 1.25rem
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: 0
  bodyMd:
    fontFamily: Poppins
    fontSize: 1rem
    fontWeight: 400
    lineHeight: 1.6
    letterSpacing: 0
  bodySm:
    fontFamily: Poppins
    fontSize: 0.875rem
    fontWeight: 400
    lineHeight: 1.5
    letterSpacing: 0
  label:
    fontFamily: Poppins
    fontSize: 0.875rem
    fontWeight: 600
    lineHeight: 1.2
    letterSpacing: 0
  metric:
    fontFamily: Poppins
    fontSize: 1.125rem
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: 0
    fontFeature: "tnum"
rounded:
  sm: 8px
  md: 12px
  lg: 16px
spacing:
  xs: 4px
  sm: 8px
  md: 12px
  lg: 16px
  xl: 24px
  2xl: 32px
components:
  shell:
    backgroundColor: "{colors.background}"
    textColor: "{colors.text}"
  pageHeader:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    rounded: "{rounded.md}"
    padding: 24px
  panel:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    rounded: "{rounded.md}"
    padding: 20px
  dataTable:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    rounded: "{rounded.sm}"
    padding: 0px
  buttonPrimary:
    backgroundColor: "{colors.accent}"
    textColor: "#FFFFFF"
    rounded: "{rounded.sm}"
    padding: 12px
  buttonSecondary:
    backgroundColor: "{colors.surface}"
    textColor: "{colors.text}"
    rounded: "{rounded.sm}"
    padding: 12px
  statusPositive:
    backgroundColor: "{colors.success}"
    textColor: "#FFFFFF"
    rounded: "{rounded.sm}"
    padding: 8px
  statusNeutral:
    backgroundColor: "{colors.surfaceMuted}"
    textColor: "{colors.primary}"
    rounded: "{rounded.sm}"
    padding: 8px
---

## Overview

Clockworx is an enterprise operations suite, not a marketing site first. The visual language should feel reliable, dense, and calm. Prioritize clarity over decoration and operational speed over visual noise.

Public landing surfaces can be a little more expressive, but authenticated app pages should read like a control room: structured, legible, and fast to scan.

## Colors

Use a restrained corporate palette with one clear action color.

- **Primary** is for core text, navigation, and the strongest structural elements.
- **Accent** is for primary actions and selected states, not decoration.
- **Success, warning, and danger** are reserved for status and risk signaling.
- **Background, surface, and border** should create separation through contrast and spacing before shadows or effects.

Do not spread gradients across whole pages. Use them sparingly, if at all, and only when they support hierarchy.

## Typography

Keep typography quiet and professional. Use Poppins consistently across the app, with tabular numbers for metrics and payroll figures.

- Headings should be bold, compact, and sentence case.
- Body text should stay readable at small sizes and avoid overly light gray tones.
- Labels should be clear and functional, not decorative.
- Numbers in dashboards and tables should align cleanly.

Avoid mixed font families unless a component has a specific functional reason.

## Layout & Spacing

Use a dense but breathable 8px rhythm. Keep controls, tables, filters, and cards aligned to the same spacing scale.

- App shells should favor a wide working area with a stable navigation column or top-level strip.
- Dashboard pages should surface the main numbers and actions near the top of the viewport.
- Tables and forms should stretch to the available content width instead of floating in narrow centered columns.
- Page sections should be full-width bands or clear structural regions, not nested cards inside cards.
- Sticky filters and sticky table headers are preferred on data-heavy views.

The public landing page can be more open, but internal modules should stay compact and information-rich.

## Elevation & Depth

Keep depth subtle. Use borders and surface contrast first, shadow second.

- Panels may have a soft shadow, but it should never become the main visual event.
- Overlays, dialogs, and menus should sit clearly above the page without heavy blur effects.
- Avoid decorative orbs, large background glows, and floating gradient bubbles.

## Shapes

Use restrained corner radii.

- Controls should stay close to 8px.
- Panels can move up to 12-16px when the surface needs more separation.
- Pill shapes are for status chips and compact labels, not every button.
- Circular shapes should be reserved for avatars, icons, and indicators.

Do not combine multiple shape languages on the same surface unless the hierarchy demands it.

## Components

Treat components as operational tools.

- Page headers should keep title, summary, and primary action together.
- KPI cards should show one dominant metric and one supporting line, not several competing visuals.
- Tables should prioritize scanning, filtering, and row actions.
- Forms should use compact labels, predictable grid alignment, and obvious validation states.
- Badges should carry state, not personality.
- Empty states should explain the next action without turning into a landing page.

The strongest pattern for this app is a data-dense panel layout with a clear header band, a working filter row, a compact content grid, and explicit state feedback.

## Do's and Don'ts

Do:
- use clear hierarchy and calm contrast
- keep spacing consistent across pages
- align charts, cards, and tables to a shared grid
- keep actions visible and predictable
- make states obvious without extra decoration

Don't:
- add decorative gradients or blurred orbs
- center everything in narrow marketing columns
- mix card styles across the same page
- use low-contrast gray text for important data
- rely on scale transforms that shift layout on hover
- duplicate the same visual rule in multiple places without a source of truth
