import '../css/gallery.css';
import { createIcons, Play, Video, Image, X, ArrowDown, ExternalLink } from 'lucide';

// Decode one still frame without playback; release the decoder/network on every exit.
const capturePreview = (url, signal) => new Promise(resolve => {
    const video = document.createElement('video');
    video.muted = true;
    video.playsInline = true;
    video.preload = 'metadata';
    let settled = false;
    let capturing = false;
    let seekTime;
    const finish = blob => {
        if (settled) return;
        settled = true;
        clearTimeout(timeout);
        signal.removeEventListener('abort', abort);
        video.onloadedmetadata = video.onloadeddata = video.onseeked = video.onerror = null;
        video.pause();
        video.removeAttribute('src');
        video.load();
        resolve(blob);
    };
    const abort = () => finish(null);
    const timeout = setTimeout(abort, 12000);
    const capture = () => {
        if (settled || capturing || video.readyState < 2 || seekTime === undefined || video.seeking || Math.abs(video.currentTime - seekTime) > .05) return;
        capturing = true;
        try {
            const scale = Math.min(1, 640 / video.videoWidth, 640 / video.videoHeight);
            const canvas = document.createElement('canvas');
            canvas.width = Math.max(1, Math.round(video.videoWidth * scale));
            canvas.height = Math.max(1, Math.round(video.videoHeight * scale));
            const context = canvas.getContext('2d');
            if (!context) return abort();
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            canvas.toBlob(finish, 'image/jpeg', .8);
        } catch { abort(); }
    };
    video.onloadedmetadata = () => {
        if (!Number.isFinite(video.duration) || video.duration <= 0 || !video.videoWidth || !video.videoHeight || video.videoWidth * video.videoHeight > 33554432) return abort();
        seekTime = Math.min(1, video.duration / 2);
        video.currentTime = seekTime;
        capture();
    };
    video.onloadeddata = video.onseeked = capture;
    video.onerror = abort;
    signal.addEventListener('abort', abort, { once: true });
    if (signal.aborted) return abort();
    video.src = url;
});

const section = document.querySelector('[data-gallery]');
if (section) {
    const icons = () => createIcons({ icons: { Play, Video, Image, X, ArrowDown, ExternalLink }, root: section });
    const grid = section.querySelector('[data-gallery-grid]');
    const dialog = section.querySelector('[data-gallery-dialog]');
    const viewer = dialog.querySelector('[data-gallery-viewer]');
    let frame;
    let trigger;
    let controller;
    const layout = () => {
        if (!grid) return;
        const style = getComputedStyle(grid);
        const columns = parseInt(style.getPropertyValue('--gallery-columns'), 10) || 1;
        const gap = parseFloat(style.getPropertyValue('--gallery-gap')) || 24;
        const width = (grid.clientWidth - (columns - 1) * gap) / columns;
        const cards = [...grid.children];
        const heights = cards.map(card => card.offsetHeight);
        const bottoms = Array(columns).fill(0);
        cards.forEach((card, index) => {
            const column = bottoms.indexOf(Math.min(...bottoms));
            card.style.left = `${column * (width + gap)}px`;
            card.style.top = `${bottoms[column]}px`;
            bottoms[column] += heights[index] + gap;
        });
        grid.style.height = `${Math.max(0, ...bottoms) - (cards.length ? gap : 0)}px`;
    };
    const schedule = () => { cancelAnimationFrame(frame); frame = requestAnimationFrame(layout); };
    const sizing = 'ResizeObserver' in window ? new ResizeObserver(schedule) : null;
    const previewed = new WeakSet();
    const pendingPreviews = new Map();
    const visiblePreviews = new Set();
    const previewUrls = new Set();
    let activePreview;
    let suspended = false;
    const previewsAllowed = () => !navigator.connection?.saveData && !['slow-2g', '2g'].includes(navigator.connection?.effectiveType);
    const pumpPreviews = async () => {
        if (activePreview || suspended || document.hidden || dialog.open || !previewsAllowed()) return;
        const next = [...pendingPreviews].find(([target]) => visiblePreviews.has(target));
        if (!next) return;
        const [target, item] = next;
        pendingPreviews.delete(target);
        visiblePreviews.delete(target);
        previewObserver.unobserve(target);
        const job = new AbortController();
        activePreview = job;
        target.dataset.frameState = 'loading';
        try {
            const blob = await capturePreview(item.url, job.signal);
            if (!blob || job.signal.aborted) return;
            const url = URL.createObjectURL(blob);
            previewUrls.add(url);
            const img = document.createElement('img');
            img.alt = '';
            img.src = url;
            await img.decode();
            if (job.signal.aborted || !target.isConnected) {
                URL.revokeObjectURL(url);
                previewUrls.delete(url);
                return;
            }
            // Keep the existing card footprint even if stored dimensions differ from the file.
            target.dataset.framePreview = '';
            img.dataset.galleryFrame = '';
            target.insertBefore(img, target.querySelector('.gallery-play'));
            target.dataset.frameState = 'ready';
        } catch {
            // Missing files, unsupported codecs, and blocked canvas reads retain the icon.
        } finally {
            if (job.signal.aborted) previewed.delete(target);
            if (target.dataset.frameState !== 'ready') target.dataset.frameState = 'fallback';
            activePreview = null;
            pumpPreviews();
        }
    };
    const previewObserver = 'IntersectionObserver' in window ? new IntersectionObserver(entries => {
        for (const entry of entries) {
            if (entry.isIntersecting) visiblePreviews.add(entry.target);
            else visiblePreviews.delete(entry.target);
        }
        pumpPreviews();
    }, { rootMargin: '0px', threshold: .01 }) : null;
    const watchPreview = link => {
        if (!previewObserver || !previewsAllowed()) return;
        const target = link.querySelector('.gallery-thumbnail');
        if (!target || previewed.has(target) || target.querySelector('img:not([hidden])')) return;
        const item = JSON.parse(link.dataset.galleryItem);
        if (item.source !== 'mp4' || new URL(item.url, location.href).origin !== location.origin) return;
        previewed.add(target);
        pendingPreviews.set(target, item);
        previewObserver.observe(target);
    };
    const prepare = () => {
        icons();
        grid?.querySelectorAll('[data-gallery-thumbnail]').forEach(img => {
            if (img.dataset.ready) return;
            img.dataset.ready = '1';
            const failed = () => { img.hidden = true; schedule(); watchPreview(img.closest('[data-gallery-item]')); };
            img.addEventListener('error', failed);
            img.addEventListener('load', schedule);
            if (img.complete && !img.naturalWidth) failed();
        });
        if (grid && sizing) {
            grid.classList.add('is-masonry');
            sizing.observe(grid);
            [...grid.children].forEach(card => sizing.observe(card));
            layout();
        }
        grid?.querySelectorAll('[data-gallery-item]').forEach(watchPreview);
    };
    prepare();
    section.addEventListener('click', event => {
        const link = event.target.closest('[data-gallery-item]');
        if (!link || !dialog.showModal || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
        const item = JSON.parse(link.dataset.galleryItem);
        event.preventDefault();
        trigger = link;
        dialog.querySelector('#gallery-dialog-title').textContent = item.title;
        dialog.querySelector('[data-gallery-description]').textContent = item.description || '';
        dialog.querySelector('[data-gallery-original]').href = item.url;
        viewer.replaceChildren();
        const ratio = Math.max(.05, Math.min(20, Number(item.width) / Number(item.height) || 16 / 9));
        viewer.style.setProperty('--viewer-ratio', String(ratio));
        viewer.style.setProperty('--viewer-width', `${Math.min(900, innerHeight * .7 * ratio)}px`);
        if (item.source === 'photo') {
            const img = document.createElement('img');
            img.src = item.url;
            img.alt = item.title;
            viewer.append(img);
        } else if (item.source === 'mp4') {
            const video = document.createElement('video');
            video.controls = true;
            video.playsInline = true;
            video.preload = 'none';
            video.setAttribute('aria-label', item.title);
            if (item.thumbnail) video.poster = item.thumbnail;
            video.src = item.url;
            video.addEventListener('loadedmetadata', () => {
                if (!video.videoWidth || !video.videoHeight) return;
                const actual = video.videoWidth / video.videoHeight;
                viewer.style.setProperty('--viewer-ratio', String(actual));
                viewer.style.setProperty('--viewer-width', `${Math.min(900, innerHeight * .7 * actual)}px`);
            });
            viewer.append(video);
        } else if (item.source === 'youtube' && /^[\w-]{11}$/.test(item.youtube_id)) {
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube-nocookie.com/embed/${item.youtube_id}?playsinline=1&rel=0`;
            iframe.title = item.title;
            iframe.allow = 'encrypted-media; fullscreen; picture-in-picture';
            iframe.allowFullscreen = true;
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';
            viewer.append(iframe);
        }
        dialog.showModal();
    });
    dialog.querySelector('[data-gallery-close]').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => {
        const r = dialog.getBoundingClientRect();
        if (event.target === dialog && (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom)) dialog.close();
    });
    dialog.addEventListener('close', () => {
        const video = viewer.querySelector('video');
        if (video) { video.pause(); video.removeAttribute('src'); video.load(); }
        viewer.replaceChildren();
        trigger?.focus({ preventScroll: true });
        pumpPreviews();
    });
    const more = section.querySelector('[data-gallery-more]');
    more?.addEventListener('click', async event => {
        event.preventDefault();
        if (more.getAttribute('aria-disabled') === 'true') return;
        const status = section.querySelector('[data-gallery-status]');
        more.setAttribute('aria-disabled', 'true');
        grid?.setAttribute('aria-busy', 'true');
        status.textContent = 'Memuat media...';
        controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 15000);
        try {
            const response = await fetch(more.dataset.next, { headers: { Accept: 'application/json' }, signal: controller.signal });
            if (!response.ok) throw new Error('Media request failed');
            const data = await response.json();
            const template = document.createElement('template');
            template.innerHTML = data.html;
            const keys = new Set([...grid.children].map(card => card.dataset.galleryKey));
            const added = [...template.content.children].filter(card => !keys.has(card.dataset.galleryKey));
            added.forEach(card => grid.append(card));
            if (grid.children.length) section.querySelector('[data-gallery-empty]')?.remove();
            prepare();
            status.textContent = added.length ? `${added.length} media ditampilkan.` : 'Tidak ada media tambahan pada halaman ini.';
            if (data.next) more.dataset.next = data.next;
            else more.hidden = true;
            if (added.length) added[0].querySelector('a').focus({ preventScroll: true });
            else if (!data.next) { status.tabIndex = -1; status.focus({ preventScroll: true }); }
        } catch {
            status.textContent = 'Media belum dapat dimuat. Silakan coba kembali.';
        } finally {
            clearTimeout(timeout);
            more.removeAttribute('aria-disabled');
            grid?.removeAttribute('aria-busy');
        }
    });
    document.addEventListener('visibilitychange', () => { if (!document.hidden) pumpPreviews(); });
    window.addEventListener('pagehide', event => {
        suspended = true;
        activePreview?.abort();
        previewObserver?.disconnect();
        for (const target of pendingPreviews.keys()) previewed.delete(target);
        pendingPreviews.clear();
        visiblePreviews.clear();
        if (!event.persisted) { previewUrls.forEach(url => URL.revokeObjectURL(url)); previewUrls.clear(); }
        sizing?.disconnect(); cancelAnimationFrame(frame); controller?.abort();
        if (dialog.open) dialog.close();
    });
    window.addEventListener('pageshow', event => { if (event.persisted) { suspended = false; prepare(); } });
}
