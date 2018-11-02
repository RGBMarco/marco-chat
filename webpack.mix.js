let mix = require('laravel-mix');
mix.js("build/app.js","public/js");
mix.js("build/index.js","public/js");
mix.sass("build/app.scss","public/css");
mix.sass("build/index.scss","public/css");