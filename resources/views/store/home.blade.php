@extends('layouts.app')
@section('title','Eyewear for your everyday')
@section('content')
<section class="hero hero-modern"><div class="container hero-grid"><div class="hero-copy"><span class="eyebrow">ONE OPTICS CLINIC · MANILA</span><h1>Eye care and frames,<br><em>under one roof.</em></h1><p class="hero-lead">Clear vision, brighter tomorrow. Browse premium eyewear online, then visit our clinic for a professional fitting by our opticians. Pickup or delivery, your choice.</p><div class="hero-actions"><a class="btn btn-primary btn-lg" href="{{ route('catalog') }}">Browse eyewear <span class="arrow" aria-hidden="true">→</span></a><a class="btn btn-outline-dark btn-lg" href="{{ route('info','contact') }}">Visit our clinic</a></div><div class="hero-trust"><span><x-icon name="check"/> Professional opticians</span><span><x-icon name="check"/> Frame & lens fitting</span><span><x-icon name="check"/> Online ordering</span></div>
<button class="watch-story-btn" id="watchStoryBtn" aria-label="Watch our story video" type="button">
  <span class="wsb-play" aria-hidden="true">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="5,3 19,12 5,21"/></svg>
  </span>
  Watch our story
</button>
</div>
<div class="hero-visual" aria-hidden="true"><div class="hv-orb"><div class="hv-ring"></div></div>
<div class="hv-glasses"><svg viewBox="0 0 400 200" fill="none"><defs><linearGradient id="hv-lens" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#fff" stop-opacity=".8"/><stop offset="1" stop-color="#176ec4" stop-opacity=".18"/></linearGradient></defs><path d="M33 100L10 38M367 100L390 38" stroke="#10539a" stroke-width="9" stroke-linecap="round"/><path d="M177 100q23-26 46 0" stroke="#10539a" stroke-width="9" stroke-linecap="round"/><circle cx="105" cy="112" r="72" fill="url(#hv-lens)" stroke="#176ec4" stroke-width="10"/><circle cx="295" cy="112" r="72" fill="url(#hv-lens)" stroke="#176ec4" stroke-width="10"/><g font-family="'Segoe UI',Arial,sans-serif" font-weight="800" fill="#163653" fill-opacity=".85" text-anchor="middle"><text x="105" y="140" font-size="92">E</text><text x="295" y="108" font-size="40" letter-spacing="6">F P</text><text x="295" y="140" font-size="26" letter-spacing="5">T O Z</text></g></svg></div>
<div class="hv-chip c1"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>Professional eye exams</div>
<div class="hv-chip c2"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>Opens 10 AM</div>
<div class="hv-chip c3"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17l-6.1 3.6 1.4-6.8L2.2 9.1l6.9-.8z"/></svg>Premium frames</div>
</div></div><span class="hero-scroll"></span></section>
<div class="service-strip reveal"><div class="container service-strip-inner"><div><x-icon name="glasses"/><span><strong>Frames that feel like you</strong><small>Find your everyday fit</small></span></div><div><x-icon name="building"/><span><strong>Your neighborhood clinic</strong><small>Visit us in Manila</small></span></div><div><x-icon name="delivery"/><span><strong>Pickup or delivery</strong><small>Choose what works for you</small></span></div></div></div><section class="container section-space reveal"><div class="section-heading"><div><span class="eyebrow">FIND YOUR FIT</span><h2>A frame for every perspective.</h2></div><a class="text-link" href="{{ route('catalog') }}">View all eyewear ↗</a></div><div class="category-grid">@foreach(['Round'=>'Soft curves, timeless appeal','Rectangle'=>'Clean lines, everyday confidence','Cat Eye'=>'A little lift. A lot of personality'] as $shape=>$copy)<a href="{{ route('catalog',['frame_shape'=>$shape]) }}"><span>{{ $shape }}</span><p>{{ $copy }}</p><b>↗</b><img src="{{ asset('assets/frames-'.($loop->index+1).'.svg') }}" alt="" loading="lazy"></a>@endforeach</div></section>
<section class="collection-section reveal"><div class="container section-space"><div class="section-heading"><div><span class="eyebrow">CURATED FOR YOU</span><h2>Your next everyday essential.</h2></div><a class="text-link" href="{{ route('catalog') }}">Shop the collection ↗</a></div><div class="product-grid">@forelse($products as $product)<x-product-card :product="$product"/>@empty<div class="empty-state">Our collection is being prepared. Please check back soon.</div>@endforelse</div></div></section>
<section class="container section-space reveal"><div class="clinic-banner"><div><span class="eyebrow">A CLEARER EVERYDAY STARTS HERE</span><h2>Your neighborhood<br>optical clinic in Manila.</h2><p>{{ $clinic['address'] ?? '' }}</p><a class="btn btn-light" href="{{ route('info','contact') }}">Plan your visit ↗</a></div><div class="clinic-promise"><span>01</span><h3>Choose your eyewear</h3><p>Browse frames and find your fit.</p><span>02</span><h3>We prepare your order</h3><p>Our team handles your order and billing.</p><span>03</span><h3>Pickup or delivery</h3><p>Receive your new everyday essential.</p></div></div></section>

{{-- ── Video Modal ── --}}
<div class="story-modal" id="storyModal" role="dialog" aria-modal="true" aria-label="Watch Our Story" hidden>
  <div class="story-modal-backdrop" id="storyBackdrop"></div>
  <div class="story-modal-box">
    <button class="story-modal-close" id="storyClose" aria-label="Close video" type="button">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="story-modal-label">ONE OPTICS CLINIC &mdash; OUR STORY</div>
    <div class="story-iframe-wrap">
      <video id="storyVideo" src="{{ asset('assets/SmartBiz-Showreel-2026.mp4') }}" controls playsinline preload="none" title="SmartBiz Showreel 2026" style="width:100%;height:100%;border-radius:10px;background:#000;"></video>
    </div>
    <p class="story-modal-caption">SmartBiz Showreel 2026 &middot; Click anywhere outside to close &middot; Press <kbd>Esc</kbd> to exit</p>
  </div>
</div>

@push('scripts')
<style>
/* ── Watch Our Story Button ── */
.watch-story-btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin-top: 22px;
  padding: 0;
  background: none;
  border: none;
  cursor: pointer;
  font-family: inherit;
  font-size: 13px;
  font-weight: 500;
  color: #2d6195;
  letter-spacing: .01em;
  transition: color .2s, transform .2s;
}
.watch-story-btn:hover { color: #176ec4; transform: translateX(2px); }
.watch-story-btn:hover .wsb-play { background: #176ec4; box-shadow: 0 0 0 6px #176ec420; }
.wsb-play {
  display: grid;
  place-items: center;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #2d6195;
  color: #fff;
  flex-shrink: 0;
  transition: background .2s, box-shadow .2s;
  box-shadow: 0 4px 14px #1a3f6825;
}
.wsb-play svg { margin-left: 2px; }

/* ── Modal Overlay ── */
.story-modal {
  position: fixed;
  inset: 0;
  z-index: 1080;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  opacity: 0;
  pointer-events: none;
  transition: opacity .3s ease;
}
.story-modal.is-open {
  opacity: 1;
  pointer-events: auto;
}
.story-modal-backdrop {
  position: absolute;
  inset: 0;
  background: rgba(8, 18, 30, 0.88);
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
}
.story-modal-box {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 880px;
  background: #0e1f30;
  border-radius: 16px;
  border: 1px solid #1e3d5a;
  box-shadow: 0 40px 120px rgba(0,0,0,.7);
  padding: 20px 20px 16px;
  transform: scale(.95) translateY(12px);
  transition: transform .35s cubic-bezier(.22,1,.36,1);
}
.story-modal.is-open .story-modal-box {
  transform: scale(1) translateY(0);
}
.story-modal-close {
  position: absolute;
  top: 14px;
  right: 14px;
  display: grid;
  place-items: center;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  border: 1px solid #2a4a66;
  background: #162638;
  color: #8ab0cc;
  cursor: pointer;
  transition: background .18s, color .18s, border-color .18s;
  z-index: 2;
}
.story-modal-close:hover { background: #1e3a56; color: #fff; border-color: #4a80aa; }
.story-modal-label {
  font-size: 9px;
  letter-spacing: .18em;
  color: #5b8ab0;
  text-align: center;
  margin-bottom: 14px;
  font-weight: 500;
}
.story-iframe-wrap {
  position: relative;
  width: 100%;
  aspect-ratio: 16/9;
  border-radius: 10px;
  overflow: hidden;
  background: #07131d;
  box-shadow: inset 0 0 0 1px #1c3d5a;
}
.story-iframe-wrap iframe {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  border: none;
}
.story-modal-caption {
  font-size: 10px;
  color: #3d6485;
  text-align: center;
  margin-top: 12px;
  line-height: 1.5;
}
.story-modal-caption kbd {
  display: inline-block;
  padding: 1px 5px;
  border: 1px solid #2a4a66;
  border-radius: 4px;
  font-size: 9px;
  font-family: inherit;
  background: #162638;
  color: #6a9ab8;
}
</style>

<script>
(function () {
  const btn      = document.getElementById('watchStoryBtn');
  const modal    = document.getElementById('storyModal');
  const backdrop = document.getElementById('storyBackdrop');
  const closeBtn = document.getElementById('storyClose');
  const video    = document.getElementById('storyVideo');

  function openModal() {
    modal.hidden = false;
    requestAnimationFrame(() => modal.classList.add('is-open'));
    video.currentTime = 0;
    video.play();
    document.body.style.overflow = 'hidden';
    closeBtn.focus();
  }

  function closeModal() {
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
    video.pause();
    video.currentTime = 0;
    // Wait for transition then hide
    modal.addEventListener('transitionend', function handler() {
      modal.hidden = true;
      modal.removeEventListener('transitionend', handler);
    });
    btn.focus();
  }

  btn.addEventListener('click', openModal);
  closeBtn.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !modal.hidden) closeModal();
  });
})();
</script>
@endpush

@endsection
