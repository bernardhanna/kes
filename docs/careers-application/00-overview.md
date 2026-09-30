# Careers job application page

## Goal

When a visitor clicks **Apply now** on the careers listing, they land on the individual **Job** post (`jobs` CPT) and complete an on-site application form — not a `mailto:` link.

## Figma source

- **File:** [KES — External file](https://www.figma.com/design/AK7VklSCuJ6T6ZgdKU8uNE/KES---External-file)
- **Node:** `784:2889` — Job application screen (title + bordered form card + side image)

## Implementation map

| Area | Path |
|------|------|
| Design tokens & layout | `01-figma-design-spec.md` |
| PHP / ACF / forms | `02-implementation.md` |
| Single job template | `single-jobs.php` |
| Form markup | `template-parts/jobs/application-form.php` |
| Config helper | `inc/jobs-application.php` |
| Job overrides (ACF) | `acf-fields/partials/jobs-application.php` |
| Global defaults (ACF) | `inc/theme-options/careers.php` |
| Careers list CTA | `template-parts/flexi/jobs.php` → `{permalink}#apply` |

## Form handler

Submissions use the existing **Theme Forms** pipeline (`data-theme-form`, `admin-post.php?action=theme_form_submit`) with `multipart/form-data` for CV upload.
