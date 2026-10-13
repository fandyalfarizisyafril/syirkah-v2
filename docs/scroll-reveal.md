# Public Scroll Reveal

- Public layout loads `resources/js/scroll-reveal.js` and its scoped CSS via Vite. Admin layout does not load either asset.
- One IntersectionObserver handles the new reveal targets. The explicit selector allowlist lives in the utility, so CMS-rendered cards require no new data fields or markup wrappers.
- Fade up: 650ms / 20px (16px mobile). Fade: 600ms, no movement. Grid stagger: 600ms / 16px, 90ms steps capped at 360ms. Easing: cubic-bezier(.22,1,.36,1).
- Individual CSS `translate` composes with existing hover `transform`; existing transitions are retained while revealing and restored afterwards. No dimensions, spacing or content are changed.
- Content initially in or above the viewport is shown immediately. Only successfully observed off-screen targets are hidden. Revealed elements are unobserved and never hidden again.
- Keyboard focus immediately reveals its containing block. Reduced motion (including live preference changes), missing/failed observer initialization, printing, and back/forward cache restoration keep content visible.
- A batched MutationObserver registers newly inserted cards. Blade search/filter/pagination use full page navigation, so the public entry initializes normally on each response. There is no client-side router in this project.
- About and Industrial Partner retain their existing one-time entrance mechanism, with timing/easing aligned. Existing animated descendants are excluded from the new utility; About's photograph is a separate fade-only target. Initially visible legacy sections skip their entrance animation.
- Hero carousel, category carousel including its cards, marquee including its logos, gallery controls, PDF object/viewer, header, navigation, CTA and footer remain outside the reveal system. Empty states remain static. Publication preview animates only its text heading/notice, never the document viewer.
- No new dependencies, backend changes, CMS changes or database writes.

## Files

- New: `resources/js/scroll-reveal.js`, `resources/css/scroll-reveal.css`, `tests/Browser/scroll-reveal.spec.js`, and this document.
- Public entry integration: `resources/views/layouts/public.blade.php`, `vite.config.js`.
- Existing motion alignment only: `resources/js/industrial-partner.js`, `resources/css/industrial-partner.css`, `resources/css/about.css`, `resources/views/public/about.blade.php`.
- Tests: `tests/Browser/about.spec.js` follows the new 90ms stagger; `tests/Browser/brand-marquee.spec.js` reads current CMS logo names instead of the old five-logo fixture, follows the actual last link, and allows 0.01px floating-point rounding in the seamless-width assertion.

## Coverage

Home (static headings/content only), About (existing text animation plus photograph fade), product listing and categories, product detail, brand listing/detail, publication listing/preview text, Contact/Inquiry, Search, Privacy and existing industry detail use the public utility or retained section entrance.

The visual reference was [ISS Indonesia](https://www.id.issworld.com/id/); no reference-site layout, styles or content were copied.

## Checks

```sh
npm run build
php artisan test --compact
npx playwright test tests/Browser/scroll-reveal.spec.js
```

Browser coverage includes 1440/1280/768/390/375px, one-time transitions, layout dimensions, keyboard focus, dynamic cards, reduced motion, missing/failed/blocked JS enhancement, restored pages, public template coverage and Admin asset isolation. Existing carousel, marquee, catalog, publications and About suites provide interaction regression coverage.

Validation on 14 October 2026: Vite build, JavaScript syntax checks and `git diff --check` passed; Laravel tests: 91 passed / 2365 assertions. The 109-case selected browser regression run initially passed 102 cases; seven old marquee assertions failed because the CMS now has seven logos rather than five, its last link changed, and a width comparison differed by 0.000061px. After updating only those test expectations, all 10 marquee cases and all 9 reveal cases passed on rerun (19/19), covering all 109 selected scenarios successfully. Desktop/mobile screenshots were inspected. Vite retains the existing runtime-resolved Inter font warning. No application/CMS data or backend files were edited.
