import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo (Reverb/Pusher) is a separate Vite entry (resources/js/echo.js) loaded
 * only in authenticated layouts, so public/SEO pages don't download it.
 */
