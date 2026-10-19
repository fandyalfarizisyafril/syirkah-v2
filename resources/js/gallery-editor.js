import '../css/gallery.css';

const form = document.querySelector('[data-gallery-editor]');
if (form) {
    const source = form.querySelector('#source');
    const file = form.querySelector('#media_file');
    const youtube = form.querySelector('#youtube_url');
    const poster = form.querySelector('#poster');
    const posterPreview = form.querySelector('[data-gallery-poster-preview]');
    const savedPoster = posterPreview.getAttribute('src');
    const savedYoutube = youtube.value;
    const removePoster = form.querySelector('[name=remove_poster]');
    const preview = form.querySelector('[data-gallery-preview]');
    const error = form.querySelector('[data-gallery-preview-error]');
    const saved = preview.innerHTML;
    let fileUrl;
    let posterUrl;
    const idFromUrl = value => {
        try {
            const url = new URL(value);
            if (url.protocol !== 'https:' || url.username || url.password || url.port) return null;
            let id;
            if (url.hostname === 'youtu.be') id = url.pathname.slice(1);
            else if (['youtube.com', 'www.youtube.com', 'm.youtube.com', 'www.youtube-nocookie.com'].includes(url.hostname)) {
                id = url.pathname === '/watch' ? url.searchParams.get('v') : url.pathname.match(/^\/(?:embed|shorts|live)\/([\w-]{11})\/?$/)?.[1];
            }
            return /^[\w-]{11}$/.test(id ?? '') ? id : null;
        } catch { return null; }
    };
    const renderPoster = () => {
        const unchanged = source.value === form.dataset.savedSource && !file.files[0] && youtube.value === savedYoutube;
        const id = source.value === 'youtube' ? idFromUrl(youtube.value) : null;
        const src = posterUrl || (!removePoster?.checked && unchanged ? savedPoster : null) || (id ? `https://i.ytimg.com/vi/${id}/hqdefault.jpg` : null);
        posterPreview.hidden = !src;
        if (src) posterPreview.src = src;
        else posterPreview.removeAttribute('src');
        const video = preview.querySelector('video');
        if (video) {
            if (src) video.poster = src;
            else video.removeAttribute('poster');
        }
    };
    posterPreview.addEventListener('error', () => { posterPreview.hidden = true; });
    removePoster?.addEventListener('change', renderPoster);
    const render = () => {
        preview.querySelector('video')?.pause();
        if (fileUrl) URL.revokeObjectURL(fileUrl);
        fileUrl = null;
        preview.replaceChildren();
        renderPoster();
        error.hidden = true;
        file.disabled = source.value === 'youtube';
        youtube.disabled = source.value !== 'youtube';
        poster.disabled = source.value === 'photo';
        form.querySelector('[data-gallery-file]').hidden = file.disabled;
        form.querySelector('[data-gallery-youtube]').hidden = youtube.disabled;
        form.querySelector('[data-gallery-poster]').hidden = poster.disabled;
        file.accept = source.value === 'mp4' ? '.mp4,video/mp4' : '.jpg,.jpeg,.png,.webp';
        if (source.value === 'youtube') {
            if (!youtube.value) return;
            const id = idFromUrl(youtube.value);
            if (!id) { error.textContent = 'URL YouTube tidak valid.'; error.hidden = false; return; }
            const iframe = document.createElement('iframe');
            iframe.src = `https://www.youtube-nocookie.com/embed/${id}?playsinline=1&rel=0`;
            iframe.title = 'Preview video YouTube';
            iframe.allowFullscreen = true;
            iframe.referrerPolicy = 'strict-origin-when-cross-origin';
            iframe.allow = 'encrypted-media; fullscreen; picture-in-picture';
            preview.append(iframe);
        } else if (file.files[0]) {
            const selected = file.files[0];
            const mp4 = source.value === 'mp4';
            if (selected.size > (mp4 ? 50 : 8) * 1024 * 1024 || !(mp4 ? /\.mp4$/i : /\.(jpg|jpeg|png|webp)$/i).test(selected.name)) {
                error.textContent = 'Jenis atau ukuran file tidak sesuai dengan media yang dipilih.';
                error.hidden = false;
                return;
            }
            const media = document.createElement(mp4 ? 'video' : 'img');
            fileUrl = URL.createObjectURL(selected);
            media.src = fileUrl;
            if (mp4) { media.controls = true; media.playsInline = true; media.preload = 'metadata'; if (posterUrl) media.poster = posterUrl; }
            else media.alt = 'Preview foto';
            media.addEventListener(mp4 ? 'loadedmetadata' : 'load', () => {
                form.querySelector('[name=width]').value = mp4 ? media.videoWidth : media.naturalWidth;
                form.querySelector('[name=height]').value = mp4 ? media.videoHeight : media.naturalHeight;
            });
            media.addEventListener('error', () => { error.textContent = 'File tidak dapat dipreview oleh browser.'; error.hidden = false; });
            preview.append(media);
        } else if (source.value === form.dataset.savedSource) {
            preview.innerHTML = saved;
        }
        renderPoster();
    };
    source.addEventListener('change', render);
    file.addEventListener('change', render);
    youtube.addEventListener('change', render);
    poster.addEventListener('change', () => {
        if (posterUrl) URL.revokeObjectURL(posterUrl);
        posterUrl = poster.files[0] ? URL.createObjectURL(poster.files[0]) : null;
        renderPoster();
    });
    window.addEventListener('pagehide', () => { if (fileUrl) URL.revokeObjectURL(fileUrl); if (posterUrl) URL.revokeObjectURL(posterUrl); });
    render();
}
