import { test, expect } from "@playwright/test";
import AxeBuilder from "@axe-core/playwright";

const PAGES = [
  "/",
  "/news/",
  "/heat-load-testing-frankfurt-9/",
  "/sitemap/",
];

function pageUrl(path: string): string {
  const base = process.env.BASE_URL || process.env.WP_HOME || "http://localhost:10054";
  const root = base.endsWith("/") ? base : `${base}/`;
  return new URL(path.replace(/^\//, ""), root).href;
}

function formatViolations(
  violations: { id: string; impact?: string; description: string; nodes: { html: string }[] }[]
): string {
  return violations
    .map(
      (v) =>
        `[${v.impact}] ${v.id}: ${v.description}\n  ${v.nodes
          .slice(0, 3)
          .map((n) => n.html)
          .join("\n  ")}`
    )
    .join("\n\n");
}

for (const path of PAGES) {
  test(`axe WCAG — ${path}`, async ({ page }) => {
    await page.goto(pageUrl(path), { waitUntil: "networkidle" });

    const results = await new AxeBuilder({ page })
      .withTags(["wcag2a", "wcag2aa", "wcag21aa"])
      .analyze();

    const blocking = results.violations.filter((v) =>
      ["critical", "serious"].includes(v.impact ?? "")
    );

    expect(
      blocking,
      blocking.length
        ? `Critical/serious a11y violations on ${path}:\n${formatViolations(blocking)}`
        : undefined
    ).toEqual([]);
  });

  test(`skip link — ${path}`, async ({ page }) => {
    await page.goto(pageUrl(path));
    const skip = page.locator('a.skip-link[href="#main-content"]');
    await expect(skip).toHaveCount(1);
    await skip.focus();
    await expect(page.locator("#main-content")).toBeAttached();
  });
}
