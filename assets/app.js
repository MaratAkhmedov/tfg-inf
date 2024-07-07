import './bootstrap.js';
import jquery from './vendor/jquery/jquery.index.js';
import './vendor/bootstrap/bootstrap.index.js';

const $ = jquery;
window.$ = window.jQuery = $;

// import 'bootstrap/dist/css/bootstrap.min.css'; it will be imported with sass

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
