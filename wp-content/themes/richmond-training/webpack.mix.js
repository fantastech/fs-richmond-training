const mix = require('laravel-mix');
require('palette-webpack-plugin/src/mix');

mix
  .setPublicPath('./assets');

mix
  .sass('resources/styles/style.scss', 'css')
  .options({ processCssUrls: false });

mix
  .js('resources/scripts/script.js', 'js');

mix
  .palette({
    output: 'palette.json',
    blacklist: [
      'inherit',
      'transparent',
    ],
    priority: 'sass',
    pretty: true,
    sass: {
      path: 'resources/styles/common',
      files: [
        '_variables.scss',
      ],
      variables: [
        'background-colors',
      ],
    },
  });

mix
 .copyDirectory('resources/images', 'assets/images');
