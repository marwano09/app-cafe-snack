import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
import '../css/app.css';

Alpine.start();
 import './bootstrap';

// Dark mode toggle (بسيط)
(function(){
  const key='theme';
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const saved = localStorage.getItem(key);
  const isDark = saved ? saved==='dark' : prefersDark;
  document.documentElement.classList.toggle('dark', isDark);
  window.toggleTheme = () => {
    const d = document.documentElement.classList.toggle('dark');
    localStorage.setItem(key, d ? 'dark' : 'light');
  }
})();
