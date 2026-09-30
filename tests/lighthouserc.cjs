/** @type {import('@lhci/cli').LHCI.ServerCommand.Options} */
module.exports = {
  ci: {
    collect: {
      url: [
        process.env.BASE_URL ? `${process.env.BASE_URL}/` : "http://localhost:10054/",
        process.env.BASE_URL
          ? `${process.env.BASE_URL}/heat-load-testing-frankfurt-9/`
          : "http://localhost:10054/heat-load-testing-frankfurt-9/",
      ],
      numberOfRuns: 1,
      settings: {
        onlyCategories: ["accessibility", "best-practices"],
        preset: "desktop",
      },
    },
    assert: {
      assertions: {
        "categories:accessibility": ["error", { minScore: 0.8 }],
        "categories:best-practices": ["warn", { minScore: 0.8 }],
      },
    },
    upload: {
      target: "filesystem",
      outputDir: "./tests/lhci-report",
    },
  },
};
