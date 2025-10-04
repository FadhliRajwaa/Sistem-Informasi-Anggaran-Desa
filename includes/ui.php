<?php
// Global loading overlay injector
?>
<script>
(function(){
  document.addEventListener('DOMContentLoaded', function(){
    // Create overlay element
    const overlay = document.createElement('div');
    overlay.id = 'global-loading-overlay';
    overlay.className = 'fixed inset-0 hidden z-[200] flex items-center justify-center bg-black/40';
    overlay.innerHTML = `
      <div class="flex flex-col items-center gap-3">
        <div class="w-12 h-12 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
        <div class="text-white text-sm">Memproses...</div>
      </div>
    `;
    document.body.appendChild(overlay);

    function showLoading(){ overlay.classList.remove('hidden'); }
    function hideLoading(){ overlay.classList.add('hidden'); }

    // Show on any form submit
    document.querySelectorAll('form').forEach(function(f){
      f.addEventListener('submit', function(){ showLoading(); });
    });
    // Show on elements with class js-loading
    document.querySelectorAll('a.js-loading, button.js-loading').forEach(function(el){
      el.addEventListener('click', function(){ showLoading(); });
    });

    // Hide when page restored from bfcache
    window.addEventListener('pageshow', function(e){ if (e.persisted) hideLoading(); });
  });
})();
</script>
