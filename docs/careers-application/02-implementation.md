# Implementation notes

## Routing

- CPT: `jobs`, rewrite slug `jobs`
- Template: `single-jobs.php` (WordPress template hierarchy)
- Anchor: `#apply` on the form `<section>` for deep links from the careers accordion

## Apply now behaviour

**Before:** If Theme Options → Careers → “Job applications email” was set, `jobs.php` used `mailto:`.

**After:** Apply always links to `get_permalink( $job ) . '#apply'`. The email field in Theme Options is only the **form submission recipient**.

## Configuration layers

1. **Theme Options → Careers** — defaults for all jobs:
   - Applications email (to address)
   - Default intro copy
   - Default side image
   - Privacy policy URL
   - CV max size (MB)
   - Email subject template (`{job_title}` placeholder)

2. **Job post → Application page** (ACF sidebar) — optional overrides:
   - Intro text
   - Side image

Helper: `matrix_job_application_config( int $job_id ): array`

## Form fields → POST keys

| UI label | `name` attribute |
|----------|------------------|
| Full name | `fullname` |
| Surname | `surname` |
| Email | `email` |
| Phone | `phone` |
| City | `city` |
| Country | `country` |
| CV file | `cv` |
| Cover letter | `cover_letter` |
| Privacy | `privacy-policy` |
| Job reference | `job_position` (hidden) |
| Job ID | `job_id` (hidden) |

Hidden Theme Forms keys: `_theme_form_name`, `_cfg_to`, `_cfg_subject`, `_theme_save_to_db`, etc.

## Files to touch when changing the design

- `template-parts/jobs/application-form.php` — markup + Tailwind
- `assets/css/app.css` — only if new component classes are needed
- `docs/careers-application/01-figma-design-spec.md` — keep in sync with Figma

## Testing checklist

- [ ] Careers page: Apply now opens job URL with `#apply` scrolled into view
- [ ] All required fields validate (native + server)
- [ ] CV upload sends attachment on notification email
- [ ] Submission stored when “Save to DB” enabled in options
- [ ] Privacy link opens correct URL
- [ ] Per-job image / intro overrides render
- [ ] Mobile: image stacks below form
