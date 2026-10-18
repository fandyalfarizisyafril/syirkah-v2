import '../css/brand-video.css';

export function initBrandVideo(root = document) {
    root.querySelectorAll('[data-brand-video-player] video').forEach(video => {
        if (video.dataset.videoReady) return;
        video.dataset.videoReady = 'true';
        const player = video.closest('[data-brand-video-player]');
        const size = () => {
            if (!video.videoWidth || !video.videoHeight) return;
            player.style.setProperty('--video-ratio', `${video.videoWidth} / ${video.videoHeight}`);
            player.style.setProperty('--video-width', `${Math.min(720, 560 * video.videoWidth / video.videoHeight)}px`);
        };
        video.addEventListener('loadedmetadata', size);
        const error = () => {
            const message = player.querySelector('[data-video-playback-error]');
            if (message) message.hidden = false;
        };
        video.addEventListener('error', error);
        video.querySelector('source')?.addEventListener('error', error);
        size();
    });
}

initBrandVideo();
