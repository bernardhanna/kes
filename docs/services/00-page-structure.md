# Service page content guide

Each **Services** CPT page uses four flexible content blocks in this order. Copy each section into the matching block in WordPress (or rely on theme sync from `inc/service-content.php`).

| Order | ACF block | Role on the page |
|------|-----------|------------------|
| 1 | **Content Four** | Page title (H1), hero-style intro paragraph, right-hand image |
| 2 | **Content Block 006** | Gradient band: section heading, short intro, bullet list (arrow items), optional brochure download |
| 3 | **Content Section Two** | White section: heading, 18px intro, 16px detail (process / workflow), side image, optional CTAs |
| 4 | **Content Section Three** | Grey band: heading, 24px lead line, two columns of supporting copy |

## Per-service files

- [Flushing and Water Treatment](./Flushing%20and%20Water%20Treatment.md) → slug `flushing-and-water-treatment`
- [Commissioning (TAB)](./Commissioning_%20Testing,%20Adjusting,%20and%20Balancing%20(TAB).md) → slug `commissioning`
- [Large-Scale Heat Load Testing](./_Large-Scale%20Heat%20Load%20Testing.md) → slug `heat-load-testing`

## Editorial notes

- Keep block 1 H1 aligned with the service name shown in navigation and breadcrumbs.
- Block 2 list items are single lines (benefit title + short explanation).
- Block 3 is the best place for numbered steps or a longer narrative.
- Images are managed in WordPress; markdown only notes suggested subjects.
- After changing copy here, bump `MATRIX_STARTER_SERVICE_CONTENT_VERSION` in `inc/service-content.php` to re-sync, or edit the CPT directly in admin.
