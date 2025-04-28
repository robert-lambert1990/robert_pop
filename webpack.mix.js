const mix = require('laravel-mix');
const fg = require('fast-glob');
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

const themeDirectory = './web/themes/custom/music_provider';
const themeAssets = `${themeDirectory}/assets`;
const hostname = 'bartmann.ddev.site'

mix.setPublicPath(`${themeDirectory}/dist`);
mix.disableSuccessNotifications();

mix.options({
  processCssUrls: false,
  autoprefixer: { remove: false },
});

if (mix.inProduction()) {
  mix.version();
  mix.disableNotifications();
} else {
  mix.sourceMaps();
  mix.webpackConfig({ devtool: 'inline-source-map' });
}

mix.webpackConfig({
  plugins: [
    new BrowserSyncPlugin(
      {
        proxy: '${hostname}',
        files: [
          `${themeDirectory}/**/*.php`,
          `${themeDirectory}/dist/css/**/*.css`,
          `${themeDirectory}/dist/js/**/*.js`,
          `${themeDirectory}/templates/**/*.twig`,
        ],
        open: false,
        notify: false,
      },
      { reload: true }
    ),
  ],
});

mix.webpackConfig({
  stats: 'errors-warnings',
});

fg.sync(`${themeAssets}/scss/*.scss`).forEach(file => {
  mix.sass(file, 'css');
});

fg.sync(`${themeAssets}/js/*.js`).forEach(file => {
  mix.js(file, 'js');
});

mix.then(() => {
  mix.minify(`${themeDirectory}/dist/js/main.js`);
  mix.minify(`${themeDirectory}/dist/css/style.css`);
});
