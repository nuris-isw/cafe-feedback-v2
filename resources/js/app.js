import './bootstrap';

import Alpine from 'alpinejs';
import initCamera from './camera';

window.Alpine = Alpine;

Alpine.start();

// Jalankan fitur kamera saat DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initCamera();
});