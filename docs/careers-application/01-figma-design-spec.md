# Figma design spec — Job application (`784:2889`)

Extracted via Figma MCP (`get_design_context`).

## Page structure

1. **Page header** (top band, ~96px horizontal padding, 64px vertical)
   - H1: `{Job title}: Application form`
   - Accent divider: 32×4px, `#00ACD8` (Blue/100)
   - Intro paragraph: 18px regular, `#1D2939` (Gray/800)

2. **Form section** (white background, 64px horizontal padding, 80px bottom)
   - Outer card: 4px border `#CBE9E1` (Blue/50), 16px radius, 72px × 56px inner padding
   - Two columns, 64px gap:
     - **Left:** form fields, max ~378px
     - **Right:** hero image, flex grow, ~973px height desktop, 8px radius

## Typography

| Element | Font | Size | Weight | Line height | Color |
|---------|------|------|--------|-------------|-------|
| Page title | Red Hat Display | 36px | 700 | 44px (-0.72px tracking) | `#262262` |
| Intro | Red Hat Text | 18px | 400 | 24px | `#1D2939` |
| Field label | Red Hat Text | 16px | 500 | 22px | `#344054` |
| Input text / placeholder | Red Hat Text | 16px | 400 | 20px | `#667085` |
| CV helper | Red Hat Text | 16px | 400 | 20px | `#667085` |
| Privacy label | Red Hat Text | 14px | 400 | 20px | `#475467` |
| Submit button | Red Hat Text | 18px | 500 | 24px | `#FFFFFF` |

Tailwind mapping: `font-red-hat-display`, `font-red-hat-text`.

## Form fields (in order)

| Field | Required | Control |
|-------|----------|---------|
| Full name | Yes | Text |
| Surname | Yes | Text |
| Email | Yes | Email |
| Phone number | Yes | Tel |
| City | Yes | Text |
| Country | Yes | Select (default Ireland) |
| CV | Yes | File drop zone |
| Cover letter | No | Textarea |
| Privacy policy | Yes | Checkbox + link |
| Submit | — | Primary button label: **Apply now** |

## CV upload zone

- Dashed/bordered box, 32×48px padding, 8px radius
- Icon + “Drop your C.V here, or upload”
- Helper: “Info: Max size: {n}mb” (configurable in Theme Options)

## Primary button

- Full width, 52px height, pill (`rounded-[100px]`)
- Gradient: `#2B3990` → `#006EC8`
- Hover (theme pattern): reverse gradient direction

## Design tokens reference

- Blue/500 `#262262` — headings
- Blue/300 `#2B3990` — accents
- Blue/100 `#00ACD8` — divider
- Blue/50 `#CBE9E1` — card border
- Gray/700 `#344054` — labels
- Gray/600 `#475467` — privacy text
- Gray/500 `#667085` — borders / placeholders
- Base/White `#FFFFFF`

## Assets (Figma MCP, 7-day URLs)

Side image reference from node `265:2519` — replace with Theme Options / per-job ACF image in production.
