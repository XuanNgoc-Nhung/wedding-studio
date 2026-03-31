    <script>
        /* ═══════════ NAVIGATION ═══════════ */
        const PAGES = ['home', 'services', 'concept', 'portfolio', 'studio', 'booking', 'blog'];

        function go(id) {
            // Hide all
            PAGES.forEach(p => {
                const el = document.getElementById('page-' + p);
                if (el) el.classList.remove('active');
            });

            // Show target
            const target = document.getElementById('page-' + id);
            if (target) target.classList.add('active');

            // Update nav links
            document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'));
            const nl = document.getElementById('nl-' + id);
            if (nl) nl.classList.add('active');

            // Scroll top
            window.scrollTo(0, 0);

            // Re-trigger reveal
            setTimeout(runReveal, 80);
        }

        /* ═══════════ NAV SHADOW ═══════════ */
        window.addEventListener('scroll', () => {
            document.getElementById('main-nav').classList.toggle('scrolled', window.scrollY > 20);
        }, {
            passive: true
        });

        /* ═══════════ PORTFOLIO FILTER ═══════════ */
        document.getElementById('pf-filters').addEventListener('click', function (e) {
            const btn = e.target.closest('.pf-btn');
            if (!btn) return;

            document.querySelectorAll('.pf-btn').forEach(b => b.classList.remove('on'));
            btn.classList.add('on');

            const filter = btn.dataset.filter;
            document.querySelectorAll('#pf-grid .pf-card').forEach(card => {
                const show = filter === 'all' || card.dataset.cat === filter;
                card.style.display = show ? '' : 'none';
                card.style.opacity = show ? '1' : '0';
            });
        });

        /* ═══════════ FILMSET TABS (home page) ═══════════ */
        function switchTab(id) {
            document.querySelectorAll('#filmset-tabs .tab-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.tab === id);
            });
            document.querySelectorAll('#filmset-tabs ~ .tab-panel').forEach(p => p.classList.remove('active'));
            const t = document.getElementById('tab-' + id);
            if (t) {
                t.classList.add('active');
                setTimeout(runReveal, 50);
            }
        }

        /* ═══════════ CONCEPT PAGE TABS ═══════════ */
        function switchConceptTab(id) {
            document.querySelectorAll('#concept-tabs .tab-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.tab === id);
            });
            document.querySelectorAll('#tab-ct-biet-thu, #tab-ct-rooftop').forEach(p => p.classList.remove('active'));
            const t = document.getElementById('tab-' + id);
            if (t) {
                t.classList.add('active');
                setTimeout(runReveal, 50);
            }
        }

        /* ═══════════ DRAG SCROLL (concept strip) ═══════════ */
        (function () {
            const el = document.getElementById('concept-scroll');
            if (!el) return;
            let isDown = false,
                startX, scrollLeft;

            el.addEventListener('mousedown', e => {
                isDown = true;
                startX = e.pageX - el.offsetLeft;
                scrollLeft = el.scrollLeft;
            });

            el.addEventListener('mouseleave', () => {
                isDown = false;
            });
            el.addEventListener('mouseup', () => {
                isDown = false;
            });

            el.addEventListener('mousemove', e => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - el.offsetLeft;
                el.scrollLeft = scrollLeft - (x - startX) * 1.2;
            });
        })();

        /* ═══════════ HAMBURGER MENU ═══════════ */
        function toggleMenu() {
            const hb = document.getElementById('hamburger');
            const mm = document.getElementById('mobile-menu');
            hb.classList.toggle('open');
            mm.classList.toggle('open');
            document.body.style.overflow = mm.classList.contains('open') ? 'hidden' : '';
        }

        function closeMenu() {
            document.getElementById('hamburger').classList.remove('open');
            document.getElementById('mobile-menu').classList.remove('open');
            document.body.style.overflow = '';
        }

        /* Close menu on outside tap */
        document.getElementById('mobile-menu').addEventListener('click', function (e) {
            if (e.target === this) closeMenu();
        });

        /* ═══════════ SCROLL REVEAL ═══════════ */
        function runReveal() {
            const io = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('v');
                        io.unobserve(e.target);
                    }
                });
            }, {
                threshold: 0.08
            });
            document.querySelectorAll('.r:not(.v)').forEach(el => io.observe(el));
        }

        runReveal();

    </script>
