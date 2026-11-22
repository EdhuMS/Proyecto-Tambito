const { defineConfig, devices } = require('@playwright/test');

module.exports = defineConfig({
  testDir: './tests/e2e',
  timeout: 30000,
  retries: process.env.CI ? 1 : 0,
  reporter: process.env.CI ? 'github' : 'list',
  use: {
    baseURL: 'http://127.0.0.1:8770',
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
  projects: [
    { name: 'desktop', use: { ...devices['Desktop Chrome'] } },
    { name: 'mobile-360', use: { viewport: { width: 360, height: 780 }, browserName: 'chromium', isMobile: true, hasTouch: true } },
    { name: 'mobile-390', use: { ...devices['Pixel 7'] } },
    { name: 'tablet', use: { viewport: { width: 768, height: 1024 }, browserName: 'chromium' } },
  ],
  webServer: {
    command: 'php -S 127.0.0.1:8770 -t .',
    url: 'http://127.0.0.1:8770/index.php',
    reuseExistingServer: !process.env.CI,
    timeout: 20000,
  },
});
