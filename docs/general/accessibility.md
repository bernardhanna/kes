# Accessibility (WCAG 2.1 AA)

Matrix Starter targets **WCAG 2.1 Level AA**. Accessibility is required for all new and updated markup, styles, and interactions—not an optional polish step.

## Theme requirements

### Buttons

Add the `.btn` class to **every** `<button>` so focus is visible and consistent:

```css
/* assets/css/app.css */
.btn {
  @apply focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-highlight-primary;
}
```

Do not remove focus outlines without replacing them. Prefer `:focus-visible` for mouse users where appropriate.

### Landmarks & structure

- One `<h1>` per page/view.
- Logical heading order (`h1` → `h2` → `h3`, no skipped levels without reason).
- Use semantic elements: `header`, `nav`, `main`, `footer`, `article`, `section`.
- Every page template must expose `<main id="main-content">` as the primary content landmark.
- A **skip link** (“Skip to main content”) is output via `matrix_starter_skip_link()` on `wp_body_open`.

### Images & media

- Informative images: descriptive `alt` text.
- Decorative images: `alt=""` and `aria-hidden="true"` where appropriate.
- Video/audio: captions or transcripts when content is essential.

### Links & controls

- Link text must describe the destination (“Read more about heat load testing”, not “Click here”).
- Icon-only controls: `aria-label` (and visible text where possible).
- External links: `target="_blank"` with `rel="noopener noreferrer"`; indicate in label if needed (“opens in new tab”).

### Forms

- Every input has a visible `<label>` or `aria-label` / `aria-labelledby`.
- Use `autocomplete` where applicable (`email`, `name`, `tel`, etc.).
- Errors: text + `aria-invalid` + `aria-describedby` pointing to error message.
- Status after submit: `role="status"` or `aria-live="polite"` for success/error summaries.

### ARIA

- Prefer native HTML over ARIA when possible.
- Landmarks: `aria-label` on `nav` / `region` when multiple of the same type exist.
- Dynamic updates: `aria-live` regions for toasts, copy-link feedback, form messages.
- Toggle buttons: `aria-expanded`, `aria-controls` as needed.

### Colour & contrast

- Normal text: **4.5:1** minimum against background.
- Large text (≥18px regular or ≥14px bold): **3:1** minimum.
- UI components and graphical objects: **3:1** for boundaries/icons where they convey meaning.
- Do not rely on colour alone (add text, icons, or patterns).

### Keyboard & focus

- All functionality operable via keyboard.
- No keyboard traps; modals must trap focus intentionally and restore on close.
- Focus order matches visual reading order.
- Focused elements must remain visible (not fully hidden by sticky headers/overlays).

### Motion & timing

- Respect `prefers-reduced-motion` for non-essential animation.
- Auto-advancing carousels: pause control; no content flashing >3 times per second.

## WCAG 2.1 checklist (summary)

| Principle | Key success criteria |
|-----------|-------------------|
| **1 Perceivable** | 1.1.1 Non-text Content; 1.3.1 Info and Relationships; 1.4.3 Contrast (Minimum); 1.4.4 Resize Text; 1.4.10 Reflow; 1.4.11 Non-text Contrast; 1.4.13 Content on Hover or Focus |
| **2 Operable** | 2.1.1 Keyboard; 2.1.2 No Keyboard Trap; 2.4.1 Bypass Blocks; 2.4.2 Page Titled; 2.4.3 Focus Order; 2.4.4 Link Purpose; 2.4.7 Focus Visible; 2.5.8 Target Size (Minimum) |
| **3 Understandable** | 3.1.1 Language of Page; 3.2.1 On Focus; 3.2.2 On Input; 3.3.1 Error Identification; 3.3.2 Labels or Instructions |
| **4 Robust** | 4.1.2 Name, Role, Value; 4.1.3 Status Messages |

Full criterion text: [WCAG 2.1](https://www.w3.org/TR/WCAG21/).

## Testing

### Automated (CI / local)

```bash
# Axe via Playwright (critical/serious violations must be zero)
BASE_URL=http://localhost:10054 npm run test:a11y

# Lighthouse CI (accessibility category ≥ 80)
BASE_URL=http://localhost:10054 npm run test:a11y:lh
```

Configure `BASE_URL` in `.env` for your Local/staging URL.

### Manual

- [ ] Keyboard-only pass: Tab through entire page; all actions reachable; focus visible.
- [ ] Screen reader spot-check (VoiceOver/NVDA): landmarks, headings, form labels, live regions.
- [ ] 200% browser zoom: no loss of content; no horizontal scroll on primary content.
- [ ] Colour contrast spot-check on new brand colours/components.

## PR checklist

- [ ] Semantic HTML and heading hierarchy
- [ ] `id="main-content"` on `<main>` (template updated if new layout)
- [ ] `.btn` on all `<button>` elements
- [ ] Alt text / `aria-label` on non-text controls
- [ ] Form labels, errors, and status messages announced
- [ ] `npm run test:a11y` passes
- [ ] No new critical/serious axe violations on affected URLs

## References

- Theme skip link & helpers: `inc/accessibility.php`
- Focus utility: `.btn` in `assets/css/app.css`
- Social/share patterns: `inc/social-icons.php`, `template-parts/single/author.php`
