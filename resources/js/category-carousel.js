export function initCategoryCarousel(carousel) {
    if (carousel.classList.contains('is-ready')) return;
    const track = carousel.querySelector('.home-category-track');
    const cards = [...track.children];
    const previous = carousel.querySelector('[data-category-prev]');
    const next = carousel.querySelector('[data-category-next]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const behavior = () => reducedMotion.matches ? 'instant' : 'smooth';
    const stepSize = () => cards.length > 1 ? cards[1].offsetLeft - cards[0].offsetLeft : track.clientWidth;
    const clamp = left => Math.max(0, Math.min(left, track.scrollWidth - track.clientWidth));
    let dragFrame = 0;
    let settleFrame = 0;
    const stopSettling = (restoreSnap = true) => {
        cancelAnimationFrame(settleFrame);
        settleFrame = 0;
        if (restoreSnap) track.classList.remove('is-settling');
    };
    const settle = left => {
        stopSettling(false);
        const start = track.scrollLeft;
        const target = clamp(left);
        const distance = target - start;
        const duration = Math.min(460, 280 + Math.abs(distance) * .35);
        const started = performance.now();
        // Native snapping stays off until the exact resting position is reached.
        track.classList.add('is-settling');
        const frame = now => {
            const progress = reducedMotion.matches ? 1 : Math.min(1, (now - started) / duration);
            const eased = 1 - (1 - progress) ** 3;
            track.scrollTo({ left: start + distance * eased, behavior: 'instant' });
            if (progress < 1) settleFrame = requestAnimationFrame(frame);
            else { stopSettling(); update(); }
        };
        frame(started);
    };
    const update = () => {
        previous.disabled = track.scrollLeft <= 2;
        next.disabled = track.scrollWidth - track.clientWidth - track.scrollLeft <= 2;
    };
    const move = direction => {
        const step = stepSize();
        const index = Math.round(track.scrollLeft / step);
        stopSettling();
        track.scrollTo({ left: (index + direction) * step, behavior: behavior() });
    };
    const collapse = card => {
        card.classList.remove('is-expanded');
        card.querySelector('[data-category-info]').setAttribute('aria-expanded', 'false');
    };

    let drag = null;
    let suppressClick = false;
    const finishDrag = event => {
        if (!drag) return;
        const { pointerId, active, target, velocity, lastTime } = drag;
        cancelAnimationFrame(dragFrame);
        dragFrame = 0;
        if (active) track.scrollTo({ left: target, behavior: 'instant' });
        const step = stepSize();
        const recentRelease = event?.type === 'pointerup' && performance.now() - lastTime < 90;
        const momentum = recentRelease && !reducedMotion.matches ? Math.max(-step * .4, Math.min(step * .4, velocity * 140)) : 0;
        const snapLeft = Math.round(clamp(track.scrollLeft + momentum) / step) * step;
        drag = null;
        window.removeEventListener('pointermove', dragMove);
        window.removeEventListener('pointerup', finishDrag);
        window.removeEventListener('pointercancel', finishDrag);
        window.removeEventListener('blur', finishDrag);
        window.removeEventListener('pagehide', finishDrag);
        if (track.hasPointerCapture(pointerId)) track.releasePointerCapture(pointerId);
        if (active) {
            suppressClick = true;
            settle(snapLeft);
        } else stopSettling();
        track.classList.remove('is-dragging');
        update();
    };
    const dragMove = event => {
        if (!drag || event.pointerId !== drag.pointerId) return;
        if (!(event.buttons & 1)) { finishDrag(); return; }
        const distance = event.clientX - drag.x;
        if (!drag.active) {
            if (Math.abs(distance) < 7) return;
            drag.active = true;
            track.classList.add('is-dragging');
            track.classList.remove('is-settling');
            track.setPointerCapture(event.pointerId);
        }
        event.preventDefault();
        const now = performance.now();
        const target = clamp(drag.left - distance);
        const elapsed = Math.max(1, now - drag.lastTime);
        const speed = (target - drag.target) / elapsed;
        drag.velocity = elapsed > 90 ? speed : drag.velocity * .35 + speed * .65;
        drag.target = target;
        drag.lastTime = now;
        // Coalesce pointer events into one scroll update per display frame, without input lag.
        if (!dragFrame) dragFrame = requestAnimationFrame(() => {
            dragFrame = 0;
            if (drag) track.scrollTo({ left: drag.target, behavior: 'instant' });
        });
    };
    track.addEventListener('pointerdown', event => {
        suppressClick = false;
        // Touch/pen retain native momentum scrolling; information buttons remain normal controls.
        if (event.pointerType !== 'mouse' || event.button !== 0 || event.target.closest('button') || track.scrollWidth <= track.clientWidth) return;
        stopSettling(false);
        drag = { pointerId: event.pointerId, x: event.clientX, left: track.scrollLeft, target: track.scrollLeft, velocity: 0, lastTime: performance.now(), active: false };
        window.addEventListener('pointermove', dragMove, { passive: false });
        window.addEventListener('pointerup', finishDrag);
        window.addEventListener('pointercancel', finishDrag);
        window.addEventListener('blur', finishDrag);
        window.addEventListener('pagehide', finishDrag);
    });
    track.addEventListener('lostpointercapture', finishDrag);
    track.addEventListener('dragstart', event => event.preventDefault());
    track.addEventListener('selectstart', event => { if (drag) event.preventDefault(); });
    track.addEventListener('click', event => {
        if (suppressClick && event.detail > 0) {
            event.preventDefault();
            event.stopImmediatePropagation();
            suppressClick = false;
        }
    }, true);
    track.addEventListener('wheel', event => {
        stopSettling();
        // Native deltaX handles trackpads. Shift + a vertical wheel opts into horizontal navigation.
        if (!event.shiftKey || event.ctrlKey || event.metaKey || event.altKey || Math.abs(event.deltaX) >= Math.abs(event.deltaY)) return;
        const direction = Math.sign(event.deltaY);
        const remaining = track.scrollWidth - track.clientWidth - track.scrollLeft;
        if ((direction > 0 && remaining <= 2) || (direction < 0 && track.scrollLeft <= 2)) return;
        event.preventDefault();
        move(direction);
    }, { passive: false });

    previous.addEventListener('click', () => move(-1));
    next.addEventListener('click', () => move(1));
    track.addEventListener('scroll', update, { passive: true });
    track.addEventListener('focusin', event => {
        if (drag) return;
        stopSettling();
        const card = event.target.closest('.home-category-card');
        card?.scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: behavior() });
    });
    cards.forEach(card => {
        const button = card.querySelector('[data-category-info]');
        button.hidden = false;
        button.addEventListener('click', () => {
            const expanded = !card.classList.contains('is-expanded');
            cards.forEach(collapse);
            card.classList.toggle('is-expanded', expanded);
            button.setAttribute('aria-expanded', String(expanded));
        });
        card.addEventListener('keydown', event => {
            if (event.key === 'Escape') collapse(card);
        });
        card.addEventListener('focusout', event => {
            if (!card.contains(event.relatedTarget)) collapse(card);
        });
    });
    const observer = new ResizeObserver(update);
    observer.observe(track);
    window.addEventListener('pagehide', () => {
        finishDrag();
        cancelAnimationFrame(dragFrame);
        stopSettling();
    });
    carousel.querySelector('.home-category-controls').hidden = cards.length < 2;
    carousel.classList.add('is-ready');
    update();
}
