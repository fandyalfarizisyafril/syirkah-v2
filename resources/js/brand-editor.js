import { createIcons, Plus, Trash2 } from 'lucide';
import { initBrandVideo } from './brand-video';

const editor = document.querySelector('[data-brand-technologies]');
if (editor) {
    const rows = editor.querySelector('[data-technology-rows]');
    const add = editor.querySelector('[data-add-technology]');
    let index = Math.max(-1, ...Array.from(rows.querySelectorAll('input'), input => Number(input.name.match(/\[(\d+)\]/)?.[1] ?? -1))) + 1;
    const sync = () => { add.disabled = rows.children.length >= 12; };
    add.addEventListener('click', () => {
        if (rows.children.length >= 12) return;
        const row = editor.querySelector('template').content.firstElementChild.cloneNode(true);
        row.querySelectorAll('[data-technology-field]').forEach(field => {
            const key = field.dataset.technologyField;
            field.id = `technology-${key}-${index}`;
            field.name = `technology_details[${index}][${key}]`;
            row.querySelector(`[data-technology-label="${key}"]`).htmlFor = field.id;
        });
        rows.append(row);
        index++;
        createIcons({ icons: { Plus, Trash2 }, root: editor });
        sync();
        row.querySelector('input').focus();
    });
    rows.addEventListener('click', event => {
        const button = event.target.closest('[data-remove-technology]');
        if (!button) return;
        button.closest('[data-technology-row]').remove();
        sync();
        add.focus();
    });
    sync();
}

const videoEditor = document.querySelector('[data-brand-video-editor]');
if (videoEditor) {
    const source = videoEditor.querySelector('#video_source');
    const file = videoEditor.querySelector('#video_file');
    const url = videoEditor.querySelector('#video_youtube_url');
    const poster = videoEditor.querySelector('#video_poster');
    const preview = videoEditor.querySelector('[data-video-preview]');
    const original = preview.innerHTML;
    const originalSource = source.value;
    const error = videoEditor.querySelector('[data-video-error]');
    let objectUrl;
    let posterUrl;
    const revoke = () => {
        if (objectUrl) URL.revokeObjectURL(objectUrl);
        objectUrl = null;
    };
    const youtubeId = value => {
        try {
            const u = new URL(value);
            if (u.protocol !== 'https:' || u.username || u.password || u.port) return null;
            let id;
            if (u.hostname === 'youtu.be') id = u.pathname.slice(1);
            else if (['youtube.com', 'www.youtube.com', 'm.youtube.com', 'www.youtube-nocookie.com'].includes(u.hostname)) {
                id = u.pathname === '/watch' ? u.searchParams.get('v') : u.pathname.match(/^\/(?:shorts|embed|live)\/([\w-]{11})\/?$/)?.[1];
            }
            return /^[\w-]{11}$/.test(id ?? '') ? id : null;
        } catch { return null; }
    };
    const render = () => {
        revoke();
        preview.replaceChildren();
        error.hidden = true;
        videoEditor.querySelector('[data-video-mp4]').hidden = source.value !== 'mp4';
        videoEditor.querySelector('[data-video-youtube]').hidden = source.value !== 'youtube';
        videoEditor.querySelector('[data-video-fields]').hidden = source.value === 'none';
        file.disabled = source.value !== 'mp4';
        url.disabled = source.value !== 'youtube';
        const player = document.createElement('div');
        player.className = 'brand-video-player';
        player.dataset.brandVideoPlayer = '';
        preview.append(player);
        if (source.value === 'mp4' && file.files[0]) {
            const uploaded = file.files[0];
            if (!/\.mp4$/i.test(uploaded.name) || uploaded.size > 50 * 1024 * 1024) {
                error.textContent = 'Pilih video MP4 berukuran maksimal 50 MB.';
                error.hidden = false;
                return;
            }
            const video = document.createElement('video');
            objectUrl = URL.createObjectURL(uploaded);
            video.src = objectUrl;
            video.controls = true;
            video.playsInline = true;
            video.preload = 'metadata';
            video.setAttribute('aria-label', 'Preview video brand');
            video.addEventListener('loadedmetadata', () => {
                videoEditor.querySelector('[name="video_width"]').value = video.videoWidth;
                videoEditor.querySelector('[name="video_height"]').value = video.videoHeight;
            });
            video.addEventListener('error', () => {
                error.textContent = 'Video tidak dapat dipreview. Gunakan MP4 dengan codec yang didukung browser, seperti H.264/AAC.';
                error.hidden = false;
            });
            player.append(video);
        } else if (source.value === 'youtube' && url.value) {
            const id = youtubeId(url.value);
            if (!id) {
                error.textContent = 'URL YouTube tidak valid.';
                error.hidden = false;
                return;
            }
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube-nocookie.com/embed/${id}?playsinline=1&rel=0`;
            iframe.title = 'Preview video YouTube';
            iframe.allowFullscreen = true;
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';
            iframe.allow = 'encrypted-media; fullscreen; picture-in-picture';
            player.append(iframe);
        } else if (source.value === originalSource) {
            preview.innerHTML = original;
        }
        if (posterUrl && preview.querySelector('video')) preview.querySelector('video').poster = posterUrl;
        initBrandVideo(preview);
    };
    source.addEventListener('change', render);
    file.addEventListener('change', render);
    url.addEventListener('change', render);
    poster.addEventListener('change', () => {
        if (posterUrl) URL.revokeObjectURL(posterUrl);
        posterUrl = poster.files[0] ? URL.createObjectURL(poster.files[0]) : null;
        const video = preview.querySelector('video');
        if (video) {
            if (posterUrl) video.poster = posterUrl;
            else video.removeAttribute('poster');
        }
    });
    window.addEventListener('pagehide', () => { revoke(); if (posterUrl) URL.revokeObjectURL(posterUrl); });
    render();
}
