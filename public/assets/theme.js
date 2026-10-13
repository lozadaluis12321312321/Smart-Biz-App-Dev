// Theme toggle: remembers the choice, follows the system preference the first time.
(()=>{
 const root=document.documentElement;
 const stored=localStorage.getItem('sb-theme');
 const prefersDark=matchMedia('(prefers-color-scheme: dark)');
 const apply=theme=>{root.dataset.theme=theme;root.style.colorScheme=theme;};
 apply(stored||(prefersDark.matches?'dark':'light'));
 prefersDark.addEventListener('change',e=>{if(!localStorage.getItem('sb-theme'))apply(e.matches?'dark':'light');});
 const button=document.createElement('button');
 button.type='button';button.className='theme-toggle';button.setAttribute('aria-label','Toggle dark mode');button.title='Toggle dark mode';
 button.innerHTML='<svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.9 4.9l1.4 1.4m11.4 11.4 1.4 1.4M2 12h2m16 0h2M4.9 19.1l1.4-1.4m11.4-11.4 1.4-1.4"/></svg><svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z"/></svg>';
 button.addEventListener('click',()=>{const next=root.dataset.theme==='dark'?'light':'dark';apply(next);localStorage.setItem('sb-theme',next);});
 document.body.append(button);
})();

// Hero parallax: the orb, glasses and chips ease toward the pointer at different depths (mouse devices only).
(()=>{
 const hero=document.querySelector('.hero-modern'),visual=hero?.querySelector('.hero-visual');
 if(!hero||!visual||matchMedia('(prefers-reduced-motion: reduce)').matches||!matchMedia('(hover: hover) and (pointer: fine)').matches)return;
 let tx=0,ty=0,x=0,y=0,raf=0;
 const step=()=>{
  x+=(tx-x)*.1;y+=(ty-y)*.1;
  visual.style.setProperty('--px',x.toFixed(3));visual.style.setProperty('--py',y.toFixed(3));
  raf=(Math.abs(tx-x)>.001||Math.abs(ty-y)>.001)?requestAnimationFrame(step):0;
 };
 const kick=()=>{if(!raf)raf=requestAnimationFrame(step);};
 hero.addEventListener('pointermove',e=>{const r=hero.getBoundingClientRect();tx=((e.clientX-r.left)/r.width)*2-1;ty=((e.clientY-r.top)/r.height)*2-1;kick();},{passive:true});
 hero.addEventListener('pointerleave',()=>{tx=0;ty=0;kick();});
})();

// Reveal sections as they scroll into view.
(()=>{
 const items=document.querySelectorAll('.reveal');
 if(!items.length)return;
 if(!('IntersectionObserver' in window)){items.forEach(el=>el.classList.add('in'));return;}
 const io=new IntersectionObserver(entries=>entries.forEach(entry=>{if(entry.isIntersecting){entry.target.classList.add('in');io.unobserve(entry.target);}}),{threshold:.12});
 items.forEach(el=>io.observe(el));
})();
