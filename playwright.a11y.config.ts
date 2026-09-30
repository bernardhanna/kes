import { defineConfig } from "@playwright/test";
import * as dotenv from "dotenv";

dotenv.config({ path: ".env" });

export default defineConfig({
  testDir: "tests/a11y",
  outputDir: "tests/pw-artifacts-a11y",
  reporter: [["list"]],
  use: {
    baseURL:
      process.env.BASE_URL || process.env.WP_HOME || "http://localhost:10054",
  },
  projects: [{ name: "chromium", use: { browserName: "chromium" } }],
});
