(function(){
  function ready(fn){
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn);
    } else {
      fn();
    }
  }

  function clamp(v, min, max){ return Math.min(max, Math.max(min, v)); }

  function drawCover(ctx, img, w, h){
    var scale = Math.max(w / img.width, h / img.height);
    var sw = w / scale, sh = h / scale;
    var sx = (img.width - sw) / 2, sy = (img.height - sh) / 2;
    ctx.drawImage(img, sx, sy, sw, sh, 0, 0, w, h);
  }

  function drawTransformed(ctx, img, w, h, t){
    var rad = t.rotation * Math.PI / 180;
    var cos = Math.abs(Math.cos(rad)), sin = Math.abs(Math.sin(rad));
    var effW = img.width * cos + img.height * sin;
    var effH = img.width * sin + img.height * cos;
    var baseScale = Math.max(w / effW, h / effH);
    var scale = baseScale * t.scale;

    var boundW = effW * scale, boundH = effH * scale;
    var maxOffsetX = Math.max(0, (boundW - w) / 2);
    var maxOffsetY = Math.max(0, (boundH - h) / 2);
    t.offsetX = clamp(t.offsetX, -maxOffsetX, maxOffsetX);
    t.offsetY = clamp(t.offsetY, -maxOffsetY, maxOffsetY);

    ctx.save();
    ctx.translate(w / 2 + t.offsetX, h / 2 + t.offsetY);
    ctx.rotate(rad);
    ctx.scale(scale, scale);
    ctx.drawImage(img, -img.width / 2, -img.height / 2);
    ctx.restore();
  }

  function initGenerator(card){
    var w = parseInt(card.getAttribute('data-width'), 10);
    var h = parseInt(card.getAttribute('data-height'), 10);
    var framePath = card.getAttribute('data-frame');
    var placeholderPath = card.getAttribute('data-placeholder');
    var canvas = card.querySelector('.atn-canvas');
    var ctx = canvas.getContext('2d');
    var fileInput = card.querySelector('.atn-file-input');
    var downloadBtn = card.querySelector('.atn-download-btn');
    var note = card.querySelector('.atn-generator-note');
    var adjustHint = card.querySelector('.atn-adjust-hint');
    var zoomInBtn = card.querySelector('.atn-zoom-in');
    var zoomOutBtn = card.querySelector('.atn-zoom-out');
    var rotateLeftBtn = card.querySelector('.atn-rotate-left');
    var rotateRightBtn = card.querySelector('.atn-rotate-right');
    var adjustButtons = [zoomInBtn, zoomOutBtn, rotateLeftBtn, rotateRightBtn];

    var frameImg = null;
    var frameFailed = false;
    var placeholderImg = null;
    var photoImg = null;
    var transform = { scale: 1, offsetX: 0, offsetY: 0, rotation: 0 };

    function render(){
      ctx.clearRect(0, 0, w, h);
      if (photoImg){
        drawTransformed(ctx, photoImg, w, h, transform);
      } else if (placeholderImg){
        drawCover(ctx, placeholderImg, w, h);
      } else {
        ctx.fillStyle = '#e8e8f0';
        ctx.fillRect(0, 0, w, h);
      }
      if (frameImg){
        ctx.drawImage(frameImg, 0, 0, w, h);
      }
    }

    function setAdjustEnabled(enabled){
      for (var i = 0; i < adjustButtons.length; i++) adjustButtons[i].disabled = !enabled;
      if (adjustHint) adjustHint.classList.toggle('is-visible', enabled);
    }

    var preload = new Image();
    preload.crossOrigin = 'anonymous';
    preload.onload = function(){ frameImg = preload; render(); };
    preload.onerror = function(){
      frameFailed = true;
      if (note) note.classList.add('is-visible');
      render();
    };
    preload.src = framePath;

    if (placeholderPath){
      var placeholderPreload = new Image();
      placeholderPreload.crossOrigin = 'anonymous';
      placeholderPreload.onload = function(){ placeholderImg = placeholderPreload; render(); };
      placeholderPreload.src = placeholderPath;
    }

    fileInput.addEventListener('change', function(e){
      var file = e.target.files && e.target.files[0];
      if (!file) return;
      var url = URL.createObjectURL(file);
      var img = new Image();
      img.onload = function(){
        photoImg = img;
        transform = { scale: 1, offsetX: 0, offsetY: 0, rotation: 0 };
        setAdjustEnabled(true);
        render();
        downloadBtn.disabled = false;
        URL.revokeObjectURL(url);
      };
      img.onerror = function(){ URL.revokeObjectURL(url); };
      img.src = url;
    });

    var dragging = false, lastX = 0, lastY = 0;

    canvas.addEventListener('pointerdown', function(e){
      if (!photoImg) return;
      dragging = true;
      lastX = e.clientX;
      lastY = e.clientY;
      canvas.classList.add('is-dragging');
      try { canvas.setPointerCapture(e.pointerId); } catch (err) {}
    });

    canvas.addEventListener('pointermove', function(e){
      if (!dragging) return;
      var rect = canvas.getBoundingClientRect();
      var scaleX = canvas.width / rect.width, scaleY = canvas.height / rect.height;
      var dx = (e.clientX - lastX) * scaleX, dy = (e.clientY - lastY) * scaleY;
      lastX = e.clientX;
      lastY = e.clientY;
      transform.offsetX += dx;
      transform.offsetY += dy;
      render();
    });

    function endDrag(e){
      if (!dragging) return;
      dragging = false;
      canvas.classList.remove('is-dragging');
      try { canvas.releasePointerCapture(e.pointerId); } catch (err) {}
    }
    canvas.addEventListener('pointerup', endDrag);
    canvas.addEventListener('pointerleave', endDrag);
    canvas.addEventListener('pointercancel', endDrag);

    canvas.addEventListener('wheel', function(e){
      if (!photoImg) return;
      e.preventDefault();
      var factor = e.deltaY < 0 ? 1.08 : 1 / 1.08;
      transform.scale = clamp(transform.scale * factor, 1, 4);
      render();
    }, { passive: false });

    zoomInBtn.addEventListener('click', function(){
      if (!photoImg) return;
      transform.scale = clamp(transform.scale * 1.15, 1, 4);
      render();
    });
    zoomOutBtn.addEventListener('click', function(){
      if (!photoImg) return;
      transform.scale = clamp(transform.scale / 1.15, 1, 4);
      render();
    });
    rotateLeftBtn.addEventListener('click', function(){
      if (!photoImg) return;
      transform.rotation = ((transform.rotation - 90) % 360 + 360) % 360;
      render();
    });
    rotateRightBtn.addEventListener('click', function(){
      if (!photoImg) return;
      transform.rotation = (transform.rotation + 90) % 360;
      render();
    });

    downloadBtn.addEventListener('click', function(){
      canvas.toBlob(function(blob){
        if (!blob) return;
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'AllThingsNew_' + w + 'x' + h + '.png';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(a.href);
      }, 'image/png');
    });
  }

  ready(function(){
    var cards = document.querySelectorAll('.atn-wrap .atn-generator');
    for (var i = 0; i < cards.length; i++) initGenerator(cards[i]);
  });
})();
