let mix = require('laravel-mix');

const moduleDir = __dirname;
const resPath = moduleDir + '/Resources/assets';
const compileTo = moduleDir + '/Assets';

mix.setPublicPath('.')

mix.js(resPath + '/js/campaigns_index.js', compileTo + '/admin/js/campaigns_index.js');
mix.js(resPath + '/js/campaigns_form.js', compileTo + '/admin/js/campaigns_form.js');
mix.js(resPath + '/js/automated_emails_index.js', compileTo + '/admin/js/automated_emails_index.js');
mix.js(resPath + '/js/automated_emails_form.js', compileTo + '/admin/js/automated_emails_form.js');
mix.js(resPath + '/js/subscribers_index.js', compileTo + '/admin/js/subscribers_index.js');
