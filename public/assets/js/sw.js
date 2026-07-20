const CACHE_NAME = 'cherynes-v1';
const ASSETS = [
    '/',
    '/bootstrap-local.css',
    '/style.css',
    '/images/logo.png',
    '/main.js'
];

self.addEventListener('install', e=>{
    e.waitUntil(caches.open(CACHE_NAME).then(cache => cache.addAll(ASSETS)));
});

const ASSETS = ['/', '/bootstrap-local.css', '/style.css', '/images/logo.png', '/main.js'];
