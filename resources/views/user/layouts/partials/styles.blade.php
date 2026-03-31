    <style>
        /* ═══════════════════════════════════════
   TOKENS — Warm Brown + Light Beige
═══════════════════════════════════════ */
        :root {
            --w: #ffffff;
            --b0: #faf7f3;
            /* page bg */
            --b1: #f4ede3;
            /* card bg */
            --b2: #ebe0d2;
            /* border-fill */
            --b3: #d9c9b5;
            /* muted border */
            --br: #a07850;
            /* brown accent */
            --br-d: #7a5a34;
            /* brown deep */
            --br-l: #c8a878;
            /* brown light */
            --br-p: #f5ede0;
            /* brown pale bg */
            --br-s: #faf3eb;
            /* brown super pale */
            --text: #2c2118;
            --text2: #7a6a58;
            --text3: #b0a090;
            --border: rgba(160, 120, 80, 0.12);
            --border2: rgba(160, 120, 80, 0.22);
            --shadow: rgba(120, 80, 40, 0.08);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--b0);
            color: var(--text);
            overflow-x: hidden;
            font-size: 16px;
            line-height: 1.7;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
        }

        /* ═══════════════════════════════════════
   NAVIGATION
═══════════════════════════════════════ */
        .nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 500;
            height: 64px;
            display: flex;
            align-items: center;
            padding: 0 48px;
            background: rgba(250, 247, 243, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            transition: box-shadow .3s;
        }

        .nav.scrolled {
            box-shadow: 0 4px 32px var(--shadow);
        }

        .nav-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 21px;
            font-weight: 400;
            color: var(--text);
            letter-spacing: 3px;
            text-transform: uppercase;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .nav-logo-mark {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: 1.5px solid var(--br-l);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-logo-mark svg {
            display: block;
        }

        .nav-links {
            display: flex;
            gap: 2px;
            margin: 0 auto;
        }

        .nav-link {
            padding: 7px 15px;
            font-size: 13px;
            font-weight: 400;
            color: var(--text2);
            cursor: pointer;
            border-radius: 8px;
            transition: all .18s;
            letter-spacing: .3px;
            user-select: none;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: var(--br-s);
            color: var(--br-d);
        }

        .nav-link.active {
            background: var(--br-p);
            color: var(--br-d);
            font-weight: 500;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .nav-tel {
            font-size: 13px;
            color: var(--text2);
            font-weight: 400;
        }

        .btn-nav {
            background: var(--text);
            color: var(--w);
            border: none;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .18s, transform .18s, box-shadow .18s;
            white-space: nowrap;
        }

        .btn-nav:hover {
            background: var(--br-d);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(122, 90, 52, 0.2);
        }

        /* ═══════════════════════════════════════
   PAGES
═══════════════════════════════════════ */
        .page {
            display: none;
            min-height: 100vh;
        }

        .page.active {
            display: block;
        }

        /* ═══════════════════════════════════════
   COMMON COMPONENTS
═══════════════════════════════════════ */
        .wrap {
            max-width: 1160px;
            margin: 0 auto;
            padding: 0 48px;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--br);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .eyebrow::before {
            content: '';
            width: 20px;
            height: 1px;
            background: var(--br-l);
        }

        .h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(42px, 5.5vw, 72px);
            font-weight: 300;
            line-height: 1.08;
            color: var(--text);
        }

        .h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(30px, 4vw, 50px);
            font-weight: 300;
            line-height: 1.15;
            color: var(--text);
        }

        .h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(22px, 2.5vw, 30px);
            font-weight: 400;
            line-height: 1.25;
            color: var(--text);
        }

        .em-text {
            font-style: italic;
            color: var(--br-d);
        }

        .lead {
            font-size: 16px;
            color: var(--text2);
            line-height: 1.85;
            font-weight: 300;
            max-width: 520px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            border: none;
            border-radius: 10px;
            padding: 13px 26px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .22s ease;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(122, 90, 52, 0.14);
        }

        .btn-dark {
            background: var(--text);
            color: var(--w);
        }

        .btn-dark:hover {
            background: var(--br-d);
        }

        .btn-brown {
            background: var(--br);
            color: var(--w);
        }

        .btn-brown:hover {
            background: var(--br-d);
        }

        .btn-outline {
            background: transparent;
            color: var(--text2);
            border: 1.5px solid var(--border2);
        }

        .btn-outline:hover {
            border-color: var(--br-l);
            color: var(--br-d);
            background: var(--br-s);
        }

        .btn-white {
            background: var(--w);
            color: var(--br-d);
        }

        .btn-white:hover {
            background: var(--b1);
        }

        /* Tags */
        .tag {
            display: inline-flex;
            align-items: center;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: .5px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .tag-br {
            background: var(--br-p);
            color: var(--br-d);
        }

        .tag-dark {
            background: var(--text);
            color: var(--w);
            font-size: 9px;
            letter-spacing: 1px;
        }

        .tag-muted {
            background: var(--b2);
            color: var(--text2);
        }

        /* Card */
        .card {
            background: var(--w);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        /* Section padding */
        .sec {
            padding: 96px 0;
        }

        .sec-sm {
            padding: 64px 0;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 0;
        }

        /* Reveal animation */
        .r {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .7s ease, transform .7s ease;
        }

        .r.v {
            opacity: 1;
            transform: none;
        }

        .r.d1 {
            transition-delay: .1s;
        }

        .r.d2 {
            transition-delay: .2s;
        }

        .r.d3 {
            transition-delay: .3s;
        }

        /* Photo placeholder */
        .ph {
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .ph-icon-wrap {
            position: absolute;
            opacity: .13;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Korean-warm gradients */
        .gk1 {
            background: linear-gradient(145deg, #e0d4c8, #cfc0b0);
        }

        .gk2 {
            background: linear-gradient(145deg, #d8cfc8, #c8bfb0);
        }

        .gk3 {
            background: linear-gradient(145deg, #dfd8cc, #ccc0b0);
        }

        .gk4 {
            background: linear-gradient(145deg, #d4d0c8, #c0bcb0);
        }

        .gk5 {
            background: linear-gradient(145deg, #e4dcd4, #d0c8bc);
        }

        .gk6 {
            background: linear(145deg, #d8d4cc, #c8c4b8);
            background: linear-gradient(145deg, #d8d4cc, #c8c4b8);
        }

        .gk7 {
            background: linear-gradient(145deg, #ddd8d0, #c8c4ba);
        }

        .gk8 {
            background: linear-gradient(145deg, #e0d8d0, #ccc4bc);
        }

        /* ═══════════════════════════════════════
   HOME PAGE
═══════════════════════════════════════ */

        /* ── Hero ── */
        .hero {
            min-height: 100vh;
            padding-top: 64px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        .hero-left {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 72px 56px 72px 64px;
            position: relative;
        }

        /* Decorative circle accent */
        .hero-deco {
            position: absolute;
            top: 50%;
            left: -120px;
            transform: translateY(-50%);
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 1px solid rgba(160, 120, 80, .08);
            pointer-events: none;
        }

        .hero-deco2 {
            position: absolute;
            top: 50%;
            left: -80px;
            transform: translateY(-50%);
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 1px solid rgba(160, 120, 80, .12);
            pointer-events: none;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--br-p);
            color: var(--br-d);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .5px;
            margin-bottom: 28px;
            width: fit-content;
            animation: fadeUp .6s .2s both;
        }

        .hero-tag::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--br);
        }

        .hero-h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(46px, 5.5vw, 74px);
            font-weight: 300;
            line-height: 1.06;
            color: var(--text);
            margin-bottom: 24px;
            animation: fadeUp .7s .35s both;
        }

        .hero-h1 em {
            font-style: italic;
            color: var(--br-d);
        }

        .hero-desc {
            font-size: 15px;
            color: var(--text2);
            line-height: 1.85;
            font-weight: 300;
            max-width: 400px;
            margin-bottom: 40px;
            animation: fadeUp .7s .5s both;
        }

        .hero-btns {
            display: flex;
            align-items: center;
            gap: 12px;
            animation: fadeUp .7s .65s both;
        }

        .hero-stats {
            display: flex;
            gap: 36px;
            margin-top: 52px;
            padding-top: 32px;
            border-top: 1px solid var(--border);
            animation: fadeUp .7s .8s both;
        }

        .hs-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 32px;
            font-weight: 400;
            color: var(--text);
            line-height: 1;
        }

        .hs-num em {
            font-style: normal;
            color: var(--br);
            font-size: 22px;
        }

        .hs-lbl {
            font-size: 11px;
            color: var(--text3);
            margin-top: 4px;
            font-weight: 300;
            letter-spacing: .3px;
        }

        /* Hero right */
        .hero-right {
            position: relative;
            background: var(--b1);
        }

        .hero-img-grid {
            position: absolute;
            inset: 0;
            display: grid;
            grid-template-columns: 3fr 2fr;
            grid-template-rows: 3fr 2fr;
            gap: 3px;
        }

        .hig-a {
            grid-row: 1 / 3;
            overflow: hidden;
        }

        .hig-b {
            overflow: hidden;
        }

        .hig-c {
            overflow: hidden;
        }

        .hig-a .ph,
        .hig-b .ph,
        .hig-c .ph {
            transition: transform .6s ease;
        }

        .hig-a:hover .ph {
            transform: scale(1.03);
        }

        .hig-b:hover .ph {
            transform: scale(1.04);
        }

        .hig-c:hover .ph {
            transform: scale(1.04);
        }

        /* Floating info card */
        .hero-card {
            position: absolute;
            bottom: 44px;
            left: -24px;
            background: var(--w);
            border-radius: 16px;
            padding: 16px 20px;
            box-shadow: 0 8px 48px var(--shadow);
            min-width: 175px;
            animation: fadeUp .7s 1s both;
            z-index: 2;
        }

        .hc-stars {
            color: var(--br-l);
            font-size: 12px;
            margin-bottom: 5px;
        }

        .hc-val {
            font-family: 'Cormorant Garamond', serif;
            font-size: 24px;
            font-weight: 400;
            color: var(--text);
        }

        .hc-lbl {
            font-size: 11px;
            color: var(--text3);
            font-weight: 300;
            margin-top: 2px;
        }

        /* ── Marquee ── */
        .marquee-wrap {
            overflow: hidden;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            background: var(--b1);
            padding: 14px 0;
        }

        .marquee-track {
            display: flex;
            width: max-content;
            animation: marquee 22s linear infinite;
        }

        .mi {
            display: flex;
            align-items: center;
            gap: 28px;
            padding: 0 28px;
            font-size: 11px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--text3);
            font-weight: 300;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .mi-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: var(--br-l);
            flex-shrink: 0;
        }

        /* ── About home ── */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .about-imgs {
            position: relative;
            height: 540px;
        }

        .about-main {
            position: absolute;
            top: 0;
            left: 0;
            width: 72%;
            height: 84%;
            border-radius: 3px;
            overflow: hidden;
        }

        .about-sub {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 47%;
            height: 48%;
            border-radius: 3px;
            overflow: hidden;
            box-shadow: 0 8px 40px var(--shadow);
            border: 5px solid var(--w);
        }

        /* Decorative rotated square */
        .about-deco {
            position: absolute;
            top: 38%;
            left: 65%;
            width: 72px;
            height: 72px;
            border: 1px solid var(--br-l);
            transform: rotate(15deg);
            opacity: .35;
            pointer-events: none;
        }

        .about-text {}

        .about-quote {
            font-family: 'Cormorant Garamond', serif;
            font-size: 19px;
            font-style: italic;
            color: var(--br-d);
            line-height: 1.65;
            padding-left: 20px;
            border-left: 2px solid var(--br-l);
            margin-bottom: 24px;
        }

        .about-body {
            font-size: 14px;
            color: var(--text2);
            line-height: 1.9;
            font-weight: 300;
            margin-bottom: 16px;
        }

        /* ── Service cards ── */
        .svc-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .svc-card {
            background: var(--w);
            border: 1px solid var(--border);
            border-radius: 18px;
            overflow: hidden;
            cursor: pointer;
            transition: box-shadow .25s, transform .25s;
        }

        .svc-card:hover {
            box-shadow: 0 16px 56px var(--shadow);
            transform: translateY(-5px);
        }

        .svc-img {
            height: 210px;
            overflow: hidden;
            position: relative;
        }

        .svc-img .ph {
            transition: transform .5s ease;
        }

        .svc-card:hover .svc-img .ph {
            transform: scale(1.05);
        }

        .svc-badge {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(255, 255, 255, .92);
            color: var(--br-d);
            font-size: 10px;
            font-weight: 500;
            letter-spacing: .8px;
            padding: 4px 10px;
            border-radius: 20px;
        }

        .svc-body {
            padding: 24px 24px 26px;
        }

        .svc-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            font-weight: 400;
            color: var(--text);
            margin-bottom: 8px;
        }

        .svc-desc {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.75;
            font-weight: 300;
            margin-bottom: 20px;
        }

        .svc-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .svc-price {
            font-size: 14px;
            font-weight: 500;
            color: var(--br-d);
        }

        .svc-arrow {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 1.5px solid var(--border2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--br-l);
            font-size: 15px;
            transition: all .2s;
        }

        .svc-card:hover .svc-arrow {
            background: var(--br-d);
            color: var(--w);
            border-color: var(--br-d);
        }

        /* ── Portfolio grid home ── */
        .port-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: 260px 260px;
            gap: 10px;
        }

        .port-item {
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
        }

        .port-item:nth-child(1) {
            grid-column: 1 / 3;
        }

        .port-item:nth-child(4) {
            grid-column: 3 / 5;
        }

        .port-item .ph {
            transition: transform .5s ease;
        }

        .port-item:hover .ph {
            transform: scale(1.04);
        }

        .port-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(44, 33, 24, .52) 0%, transparent 55%);
            opacity: 0;
            transition: opacity .3s;
        }

        .port-item:hover .port-overlay {
            opacity: 1;
        }

        .port-info {
            position: absolute;
            bottom: 16px;
            left: 18px;
            opacity: 0;
            transform: translateY(6px);
            transition: all .3s;
        }

        .port-item:hover .port-info {
            opacity: 1;
            transform: none;
        }

        .port-couple {
            font-family: 'Cormorant Garamond', serif;
            font-size: 16px;
            font-style: italic;
            color: var(--w);
        }

        .port-type {
            font-size: 10px;
            color: rgba(255, 255, 255, .65);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ── Process ── */
        .proc-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0;
            position: relative;
        }

        .proc-grid::before {
            content: '';
            position: absolute;
            top: 23px;
            left: calc(10% + 10px);
            right: calc(10% + 10px);
            height: 1px;
            border-top: 1px dashed var(--b3);
        }

        .proc-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 0 14px;
        }

        .proc-num {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--w);
            border: 1.5px solid var(--br-l);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 17px;
            color: var(--br-d);
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
            transition: background .2s, color .2s;
        }

        .proc-step:hover .proc-num {
            background: var(--br-d);
            color: var(--w);
            border-color: var(--br-d);
        }

        .proc-title {
            font-size: 13px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 7px;
        }

        .proc-desc {
            font-size: 11px;
            color: var(--text3);
            line-height: 1.7;
            font-weight: 300;
        }

        /* ── Reviews ── */
        .rev-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .rev-card {
            background: var(--w);
            border-radius: 16px;
            padding: 30px;
            border: 1px solid var(--border);
        }

        .rev-stars {
            color: var(--br-l);
            font-size: 13px;
            margin-bottom: 14px;
        }

        .rev-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 16px;
            font-style: italic;
            color: var(--text);
            line-height: 1.75;
            margin-bottom: 20px;
        }

        .rev-author {
            display: flex;
            align-items: center;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .rev-av {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--br-p);
            color: var(--br-d);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 500;
            flex-shrink: 0;
        }

        .rev-name {
            font-size: 12px;
            font-weight: 500;
            color: var(--text);
        }

        .rev-date {
            font-size: 11px;
            color: var(--text3);
            margin-top: 1px;
        }

        /* ── CTA Band ── */
        .cta-band {
            background: var(--text);
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        /* Decorative rings in CTA */
        .cta-band::before,
        .cta-band::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            border: 1px solid rgba(200, 168, 120, .07);
        }

        .cta-band::before {
            width: 500px;
            height: 500px;
            top: -200px;
            right: -150px;
        }

        .cta-band::after {
            width: 400px;
            height: 400px;
            bottom: -180px;
            left: -100px;
        }

        .cta-h {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(28px, 3.5vw, 44px);
            font-weight: 300;
            color: var(--w);
            margin-bottom: 12px;
        }

        .cta-h em {
            font-style: italic;
            color: var(--br-l);
        }

        .cta-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, .5);
            margin-bottom: 32px;
            font-weight: 300;
        }

        /* ═══════════════════════════════════════
   SERVICES PAGE
═══════════════════════════════════════ */
        .page-hero {
            padding-top: 64px;
            padding-bottom: 0;
        }

        .page-hero-inner {
            background: var(--b1);
            padding: 72px 48px 64px;
        }

        .pkg-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            padding: 60px 48px;
            max-width: 1160px;
            margin: 0 auto;
        }

        .pkg-card {
            background: var(--w);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            transition: box-shadow .25s, transform .25s;
            position: relative;
        }

        .pkg-card:hover {
            box-shadow: 0 20px 64px var(--shadow);
            transform: translateY(-6px);
        }

        .pkg-card.featured {
            border-color: var(--br-l);
            box-shadow: 0 8px 40px var(--shadow);
        }

        .pkg-ribbon {
            position: absolute;
            top: 0;
            right: 24px;
            background: var(--br-d);
            color: var(--w);
            font-size: 9px;
            font-weight: 500;
            letter-spacing: 1px;
            padding: 5px 14px;
            border-radius: 0 0 8px 8px;
            text-transform: uppercase;
        }

        .pkg-img {
            height: 190px;
            overflow: hidden;
        }

        .pkg-img .ph {
            transition: transform .5s;
        }

        .pkg-card:hover .pkg-img .ph {
            transform: scale(1.04);
        }

        .pkg-body {
            padding: 26px;
        }

        .pkg-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 24px;
            font-weight: 400;
            color: var(--text);
            margin-bottom: 3px;
        }

        .pkg-sub {
            font-size: 10px;
            color: var(--br);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .pkg-price {
            font-family: 'Cormorant Garamond', serif;
            font-size: 30px;
            font-weight: 300;
            color: var(--br-d);
            margin-bottom: 3px;
        }

        .pkg-note {
            font-size: 11px;
            color: var(--text3);
            margin-bottom: 20px;
        }

        .pkg-sep {
            height: 1px;
            background: var(--border);
            margin-bottom: 18px;
        }

        .pkg-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-bottom: 26px;
        }

        .pkg-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: var(--text2);
            font-weight: 300;
            line-height: 1.5;
        }

        .pkg-check {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--br-p);
            color: var(--br-d);
            font-size: 8px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .pkg-btn {
            width: 100%;
            padding: 13px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: all .2s;
            border: none;
        }

        .pkg-card.featured .pkg-btn {
            background: var(--br-d);
            color: var(--w);
        }

        .pkg-card.featured .pkg-btn:hover {
            background: var(--br);
        }

        .pkg-card:not(.featured) .pkg-btn {
            background: transparent;
            color: var(--br-d);
            border: 1.5px solid var(--border2);
        }

        .pkg-card:not(.featured) .pkg-btn:hover {
            background: var(--br-p);
            border-color: var(--br-l);
        }

        /* Addon section */
        .addon-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        .addon-card {
            background: var(--w);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
            transition: box-shadow .2s;
        }

        .addon-card:hover {
            box-shadow: 0 6px 32px var(--shadow);
        }

        .addon-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--br-p);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 14px;
        }

        .addon-name {
            font-size: 14px;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
        }

        .addon-desc {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.65;
            font-weight: 300;
            margin-bottom: 12px;
        }

        .addon-price {
            font-size: 13px;
            color: var(--br-d);
            font-weight: 500;
        }

        /* ═══════════════════════════════════════
   PORTFOLIO PAGE
═══════════════════════════════════════ */
        .pf-filters {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .pf-btn {
            padding: 7px 16px;
            border-radius: 20px;
            border: 1.5px solid var(--border2);
            font-size: 13px;
            color: var(--text2);
            cursor: pointer;
            transition: all .18s;
            font-family: 'DM Sans', sans-serif;
            background: var(--w);
        }

        .pf-btn:hover {
            border-color: var(--br-l);
            color: var(--br-d);
            background: var(--br-s);
        }

        .pf-btn.on {
            background: var(--br-d);
            color: var(--w);
            border-color: var(--br-d);
        }

        .pf-masonry {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding: 48px 0 80px;
        }

        .pf-card {
            border-radius: 14px;
            overflow: hidden;
            background: var(--b1);
            cursor: pointer;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .pf-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 40px var(--shadow);
        }

        .pf-card:nth-child(3n+2) {
            margin-top: 28px;
        }

        .pf-card .ph {
            transition: transform .5s;
        }

        .pf-card:hover .ph {
            transform: scale(1.04);
        }

        .pf-info {
            padding: 14px 16px;
            background: var(--w);
        }

        .pf-couple {
            font-family: 'Cormorant Garamond', serif;
            font-size: 16px;
            font-style: italic;
            color: var(--text);
        }

        .pf-type {
            font-size: 10px;
            color: var(--br);
            letter-spacing: 1.5px;
            margin-top: 3px;
        }

        /* ═══════════════════════════════════════
   STUDIO PAGE
═══════════════════════════════════════ */
        .studio-story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .studio-img-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 280px 180px;
            gap: 12px;
        }

        .sic-main {
            grid-column: 1 / 3;
            border-radius: 12px;
            overflow: hidden;
        }

        .sic-sub {
            border-radius: 12px;
            overflow: hidden;
        }

        .studio-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 28px;
        }

        .st-stat {
            background: var(--b1);
            border-radius: 12px;
            padding: 20px;
        }

        .st-val {
            font-family: 'Cormorant Garamond', serif;
            font-size: 34px;
            font-weight: 300;
            color: var(--br-d);
            line-height: 1;
        }

        .st-lbl {
            font-size: 11px;
            color: var(--text3);
            margin-top: 5px;
            font-weight: 300;
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .team-card {
            text-align: center;
        }

        .team-av {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .team-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            font-weight: 400;
            color: var(--text);
        }

        .team-role {
            font-size: 10px;
            color: var(--br);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .team-desc {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.7;
            margin-top: 10px;
            font-weight: 300;
        }

        /* ═══════════════════════════════════════
   BOOKING PAGE
═══════════════════════════════════════ */
        .booking-layout {
            padding-top: 64px;
            min-height: 100vh;
            background: var(--b0);
            display: grid;
            grid-template-columns: 1fr 1.1fr;
        }

        .booking-left {
            padding: 72px 56px;
            background: var(--b1);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bl-info {
            margin-top: 40px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .bli {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .bli-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--br-p);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .bli-lbl {
            font-size: 10px;
            color: var(--br);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .bli-val {
            font-size: 14px;
            color: var(--text);
            font-weight: 400;
            line-height: 1.5;
        }

        .booking-right {
            padding: 72px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bf-wrap {
            background: var(--w);
            border-radius: 20px;
            padding: 40px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 40px var(--shadow);
        }

        .bf-heading {
            font-family: 'Cormorant Garamond', serif;
            font-size: 26px;
            font-weight: 400;
            color: var(--text);
            margin-bottom: 6px;
        }

        .bf-sub {
            font-size: 13px;
            color: var(--text2);
            margin-bottom: 28px;
            font-weight: 300;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .fg {
            display: flex;
            flex-direction: column;
            gap: 7px;
            margin-bottom: 14px;
        }

        .fg.full {
            grid-column: 1 / -1;
        }

        .fg label {
            font-size: 11px;
            font-weight: 500;
            color: var(--text2);
            letter-spacing: .3px;
        }

        .fg input,
        .fg select,
        .fg textarea {
            padding: 11px 14px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            background: var(--b0);
            outline: none;
            transition: border-color .18s, background .18s;
            -webkit-appearance: none;
            appearance: none;
        }

        .fg input:focus,
        .fg select:focus,
        .fg textarea:focus {
            border-color: var(--br-l);
            background: var(--w);
        }

        .fg input::placeholder,
        .fg textarea::placeholder {
            color: var(--text3);
        }

        .fg textarea {
            resize: none;
            height: 88px;
            line-height: 1.6;
        }

        .btn-submit {
            width: 100%;
            background: var(--br-d);
            color: var(--w);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .2s;
            margin-top: 6px;
        }

        .btn-submit:hover {
            background: var(--br);
        }

        .form-note {
            font-size: 11px;
            color: var(--text3);
            text-align: center;
            margin-top: 12px;
        }

        /* ═══════════════════════════════════════
   BLOG PAGE
═══════════════════════════════════════ */
        .blog-layout {
            display: grid;
            grid-template-columns: 1fr 320px;
            gap: 48px;
            padding: 56px 0 80px;
        }

        .blog-featured {
            border-radius: 16px;
            overflow: hidden;
            background: var(--w);
            border: 1px solid var(--border);
            margin-bottom: 28px;
            cursor: pointer;
            transition: box-shadow .25s;
        }

        .blog-featured:hover {
            box-shadow: 0 8px 48px var(--shadow);
        }

        .bfeat-img {
            height: 280px;
            overflow: hidden;
        }

        .bfeat-img .ph {
            transition: transform .5s;
        }

        .blog-featured:hover .bfeat-img .ph {
            transform: scale(1.03);
        }

        .bfeat-body {
            padding: 28px;
        }

        .bfeat-cat {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--br);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bfeat-cat::before {
            content: '';
            width: 14px;
            height: 1px;
            background: var(--br-l);
        }

        .bfeat-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            font-weight: 400;
            color: var(--text);
            line-height: 1.4;
            margin-bottom: 10px;
        }

        .bfeat-excerpt {
            font-size: 13px;
            color: var(--text2);
            line-height: 1.8;
            font-weight: 300;
        }

        .bfeat-meta {
            font-size: 11px;
            color: var(--text3);
            margin-top: 16px;
        }

        .blog-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .blog-card {
            background: var(--w);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            display: flex;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .blog-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 24px var(--shadow);
        }

        .bc-img {
            width: 110px;
            flex-shrink: 0;
            overflow: hidden;
        }

        .bc-img .ph {
            transition: transform .4s;
            height: 100%;
        }

        .blog-card:hover .bc-img .ph {
            transform: scale(1.06);
        }

        .bc-body {
            padding: 16px;
            flex: 1;
        }

        .bc-cat {
            font-size: 10px;
            color: var(--br);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .bc-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 15px;
            font-style: italic;
            color: var(--text);
            line-height: 1.4;
            margin-bottom: 6px;
        }

        .bc-meta {
            font-size: 11px;
            color: var(--text3);
        }

        /* Blog sidebar */
        .bs-block {
            margin-bottom: 28px;
        }

        .bs-title {
            font-size: 12px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
        }

        .bs-tag {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            border: 1px solid var(--border2);
            font-size: 11px;
            color: var(--text2);
            margin: 3px;
            cursor: pointer;
            transition: all .18s;
        }

        .bs-tag:hover {
            background: var(--br-p);
            color: var(--br-d);
            border-color: var(--br-l);
        }

        .bs-newsletter {
            background: var(--br-p);
            border-radius: 14px;
            padding: 24px;
            border: 1px solid var(--border2);
        }

        .bs-nl-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 18px;
            font-style: italic;
            color: var(--br-d);
            margin-bottom: 10px;
        }

        .bs-nl-sub {
            font-size: 12px;
            color: var(--text2);
            line-height: 1.7;
            margin-bottom: 14px;
            font-weight: 300;
        }

        .bs-nl-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid var(--border2);
            border-radius: 8px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            margin-bottom: 10px;
            background: var(--w);
            transition: border-color .18s;
        }

        .bs-nl-input:focus {
            border-color: var(--br-l);
        }

        .bs-nl-btn {
            width: 100%;
            background: var(--br-d);
            color: var(--w);
            border: none;
            border-radius: 8px;
            padding: 10px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: background .2s;
        }

        .bs-nl-btn:hover {
            background: var(--br);
        }

        /* ═══════════════════════════════════════
   FOOTER
═══════════════════════════════════════ */
        footer {
            background: #201810;
            color: var(--w);
            padding: 72px 48px 32px;
        }

        .ft-grid {
            display: grid;
            grid-template-columns: 1.7fr 1fr 1fr 1fr;
            gap: 48px;
            max-width: 1160px;
            margin: 0 auto 52px;
        }

        .ft-brand-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 22px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--w);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .ft-logo-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--br-l);
            flex-shrink: 0;
        }

        .ft-tagline {
            font-size: 12px;
            color: rgba(255, 255, 255, .3);
            font-style: italic;
            margin-bottom: 26px;
        }

        .ft-ci {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: flex-start;
        }

        .ft-ci-l {
            font-size: 9px;
            color: var(--br-l);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            min-width: 58px;
            padding-top: 2px;
            flex-shrink: 0;
        }

        .ft-ci-v {
            font-size: 13px;
            color: rgba(255, 255, 255, .5);
            font-weight: 300;
            line-height: 1.5;
        }

        .ft-col-h {
            font-size: 9px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--br-l);
            margin-bottom: 18px;
        }

        .ft-links {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .ft-links a {
            font-size: 13px;
            color: rgba(255, 255, 255, .4);
            font-weight: 300;
            cursor: pointer;
            transition: color .18s;
            text-decoration: none;
            display: block;
        }

        .ft-links a:hover {
            color: var(--br-l);
        }

        .ft-bottom {
            border-top: 1px solid rgba(255, 255, 255, .07);
            padding-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1160px;
            margin: 0 auto;
        }

        .ft-copy {
            font-size: 11px;
            color: rgba(255, 255, 255, .2);
        }

        .ft-social {
            display: flex;
            gap: 18px;
        }

        .ft-social a {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .3);
            text-decoration: none;
            transition: color .2s;
            cursor: pointer;
        }

        .ft-social a:hover {
            color: var(--br-l);
        }

        /* ═══════════════════════════════════════
   ANIMATIONS
═══════════════════════════════════════ */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes marquee {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(-50%);
            }
        }

        /* ═══════════════════════════════════════
   FILM STUDIO SECTION
═══════════════════════════════════════ */

        /* Intro banner */
        .studio-intro {
            background: var(--text);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .studio-intro::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 60% at 20% 50%, rgba(160, 120, 80, .12) 0%, transparent 70%),
                radial-gradient(ellipse 50% 60% at 80% 50%, rgba(200, 168, 120, .08) 0%, transparent 70%);
            pointer-events: none;
        }

        .si-eyebrow {
            font-size: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--br-l);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .si-eyebrow::before,
        .si-eyebrow::after {
            content: '';
            width: 28px;
            height: 1px;
            background: var(--br-l);
            opacity: .5;
        }

        .si-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(36px, 5vw, 64px);
            font-weight: 300;
            color: var(--w);
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .si-title em {
            font-style: italic;
            color: var(--br-l);
        }

        .si-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, .45);
            font-weight: 300;
            max-width: 560px;
            margin: 0 auto 40px;
            line-height: 1.8;
        }

        /* Stats row */
        .si-stats {
            display: flex;
            justify-content: center;
            gap: 0;
            margin-top: 48px;
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding-top: 40px;
        }

        .si-stat {
            flex: 1;
            max-width: 200px;
            border-right: 1px solid rgba(255, 255, 255, .08);
            padding: 0 28px;
        }

        .si-stat:last-child {
            border-right: none;
        }

        .si-stat-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 42px;
            font-weight: 300;
            color: var(--br-l);
            line-height: 1;
        }

        .si-stat-lbl {
            font-size: 11px;
            color: rgba(255, 255, 255, .35);
            margin-top: 6px;
            font-weight: 300;
            letter-spacing: .5px;
        }

        /* Concept cards scroll row */
        .concept-scroll-wrap {
            background: var(--b1);
            padding: 64px 0;
            overflow: hidden;
        }

        .concept-scroll-inner {
            display: flex;
            gap: 20px;
            padding: 4px 48px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
            cursor: grab;
        }

        .concept-scroll-inner:active {
            cursor: grabbing;
        }

        .concept-scroll-inner::-webkit-scrollbar {
            display: none;
        }

        .concept-pill {
            flex-shrink: 0;
            scroll-snap-align: start;
            background: var(--w);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
            width: 220px;
            transition: box-shadow .2s, transform .2s;
            cursor: pointer;
        }

        .concept-pill:hover {
            box-shadow: 0 8px 32px var(--shadow);
            transform: translateY(-3px);
        }

        .cp-img {
            height: 160px;
            overflow: hidden;
        }

        .cp-img .ph {
            transition: transform .4s;
        }

        .concept-pill:hover .cp-img .ph {
            transform: scale(1.06);
        }

        .cp-body {
            padding: 14px 16px 16px;
        }

        .cp-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 16px;
            font-style: italic;
            color: var(--text);
            margin-bottom: 4px;
        }

        .cp-tag {
            font-size: 10px;
            color: var(--br);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Tab section */
        .filmset-tabs-section {
            padding: 80px 0 0;
            background: var(--b0);
        }

        .fts-header {
            text-align: center;
            margin-bottom: 48px;
        }

        /* Tab pills */
        .tab-nav {
            display: flex;
            justify-content: center;
            gap: 0;
            background: var(--b1);
            border: 1px solid var(--border2);
            border-radius: 16px;
            padding: 6px;
            max-width: 480px;
            margin: 0 auto 56px;
        }

        .tab-btn {
            flex: 1;
            padding: 13px 20px;
            border: none;
            border-radius: 11px;
            background: transparent;
            color: var(--text2);
            font-size: 13px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all .22s;
            text-align: center;
            line-height: 1.4;
        }

        .tab-btn.active {
            background: var(--w);
            color: var(--br-d);
            box-shadow: 0 2px 16px var(--shadow);
        }

        .tab-btn span {
            display: block;
            font-size: 10px;
            font-weight: 300;
            color: inherit;
            opacity: .6;
            margin-top: 2px;
            letter-spacing: .5px;
        }

        /* Tab panels */
        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
            animation: tabFade .35s ease;
        }

        @keyframes tabFade {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Panel layout */
        .tp-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            min-height: 580px;
        }

        .tp-info {
            padding: 64px 56px 64px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .tp-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--br-p);
            color: var(--br-d);
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
            width: fit-content;
        }

        .tp-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(28px, 3.5vw, 44px);
            font-weight: 300;
            color: var(--text);
            line-height: 1.15;
            margin-bottom: 6px;
        }

        .tp-name em {
            font-style: italic;
            color: var(--br-d);
        }

        .tp-sub {
            font-size: 12px;
            color: var(--br);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .tp-desc {
            font-size: 14px;
            color: var(--text2);
            line-height: 1.9;
            font-weight: 300;
            margin-bottom: 28px;
        }

        .tp-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 32px;
        }

        .tp-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--text2);
            font-weight: 300;
        }

        .tp-feat-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--br-l);
            flex-shrink: 0;
        }

        /* Right image grid */
        .tp-imgs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr;
            gap: 4px;
            overflow: hidden;
        }

        .tp-img {
            overflow: hidden;
            position: relative;
        }

        .tp-img .ph {
            transition: transform .5s ease;
        }

        .tp-img:hover .ph {
            transform: scale(1.05);
        }

        .tp-img-main {
            grid-column: 1 / 3;
        }

        /* Full-width concept strip below tab */
        .concept-strip {
            padding: 40px 0 80px;
            background: var(--b0);
        }

        .cs-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .cs-item {
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform .24s ease, box-shadow .24s ease;
        }

        .cs-item .ph {
            transition: transform .5s;
        }

        .cs-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(44, 33, 24, 0.14);
        }

        .cs-item:hover .ph {
            transform: scale(1.04);
        }

        .cs-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 24px 14px 14px;
            background: linear-gradient(to top, rgba(44, 33, 24, .65) 0%, transparent 100%);
            opacity: 0;
            transition: opacity .3s;
        }

        .cs-item:hover .cs-label {
            opacity: 1;
        }

        .cs-lbl-txt {
            font-family: 'Cormorant Garamond', serif;
            font-size: 15px;
            font-style: italic;
            color: var(--w);
        }

        a:focus-visible,
        button:focus-visible,
        .nav-link:focus-visible {
            outline: 2px solid var(--br-l);
            outline-offset: 2px;
            border-radius: 8px;
        }

        /* Responsive for studio section */
        @media (max-width: 768px) {
            .si-stats {
                gap: 0;
                flex-wrap: wrap;
                justify-content: center;
            }

            .si-stat {
                max-width: 50%;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, .08);
                padding: 20px;
            }

            .si-stat:nth-child(odd) {
                border-right: 1px solid rgba(255, 255, 255, .08);
            }

            .si-stat:last-child {
                border-bottom: none;
            }

            .concept-scroll-inner {
                padding: 4px 20px;
                gap: 14px;
            }

            .concept-pill {
                width: 180px;
            }

            .tab-nav {
                max-width: 90%;
                margin-bottom: 36px;
            }

            .tab-btn {
                padding: 11px 12px;
                font-size: 12px;
            }

            .tp-layout {
                grid-template-columns: 1fr;
                min-height: auto;
            }

            .tp-info {
                padding: 40px 20px 32px;
            }

            .tp-imgs {
                height: 300px;
            }

            .tp-img-main {
                grid-column: 1 / 3;
            }

            .cs-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }
        }

        @media (max-width: 430px) {
            .tab-nav {
                flex-direction: column;
                border-radius: 14px;
            }

            .tab-btn {
                border-radius: 9px;
            }

            .tp-imgs {
                height: 240px;
            }

            .cs-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ═══════════════════════════════════════
   MOBILE NAV — Hamburger
═══════════════════════════════════════ */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 6px;
            margin-left: auto;
            background: none;
            border: none;
        }

        .nav-hamburger span {
            display: block;
            width: 22px;
            height: 1.5px;
            background: var(--text);
            border-radius: 2px;
            transition: all .25s;
        }

        .nav-hamburger.open span:nth-child(1) {
            transform: translateY(6.5px) rotate(45deg);
        }

        .nav-hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .nav-hamburger.open span:nth-child(3) {
            transform: translateY(-6.5px) rotate(-45deg);
        }

        /* Mobile drawer */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--w);
            z-index: 400;
            flex-direction: column;
            padding: 28px 24px 40px;
            gap: 4px;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform .3s ease;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        .mm-link {
            padding: 14px 16px;
            font-size: 18px;
            font-family: 'Cormorant Garamond', serif;
            font-weight: 400;
            color: var(--text);
            cursor: pointer;
            border-bottom: 1px solid var(--border);
            transition: color .18s;
        }

        .mm-link:hover {
            color: var(--br-d);
        }

        .mm-book {
            margin-top: 20px;
            background: var(--br-d);
            color: var(--w);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            width: 100%;
            transition: background .2s;
        }

        .mm-book:hover {
            background: var(--br);
        }

        .mm-tel {
            text-align: center;
            margin-top: 14px;
            font-size: 13px;
            color: var(--text3);
        }

        /* ═══════════════════════════════════════
   TABLET — max 1024px
═══════════════════════════════════════ */
        @media (max-width: 1024px) {
            .wrap {
                padding: 0 32px;
            }

            /* Nav */
            .nav {
                padding: 0 32px;
            }

            .nav-links {
                gap: 0;
            }

            .nav-link {
                padding: 7px 10px;
                font-size: 11px;
            }

            .nav-tel {
                display: none;
            }

            /* Hero */
            .hero {
                grid-template-columns: 1fr 1fr;
            }

            .hero-left {
                padding: 60px 40px 60px 40px;
            }

            .hero-h1 {
                font-size: clamp(36px, 4.5vw, 56px);
            }

            /* Grids */
            .about-grid {
                gap: 48px;
            }

            .svc-grid {
                gap: 14px;
            }

            .pkg-grid {
                padding: 40px 32px;
                gap: 18px;
            }

            .team-grid {
                gap: 18px;
            }

            .rev-grid {
                gap: 14px;
            }

            .ft-grid {
                gap: 32px;
                grid-template-columns: 1.4fr 1fr 1fr 1fr;
            }

            /* Port grid */
            .port-grid {
                grid-template-rows: 220px 220px;
            }

            /* Blog */
            .blog-layout {
                gap: 32px;
            }
        }

        /* ═══════════════════════════════════════
   MOBILE — max 768px
═══════════════════════════════════════ */
        @media (max-width: 768px) {

            /* === NAV === */
            .nav {
                padding: 0 20px;
                height: 58px;
            }

            .nav-links,
            .nav-actions {
                display: none;
            }

            .nav-hamburger {
                display: flex;
            }

            .mobile-menu {
                display: flex;
                top: 58px;
            }

            /* === WRAP === */
            .wrap {
                padding: 0 20px;
            }

            /* === SECTIONS === */
            .sec {
                padding: 64px 0;
            }

            .sec-sm {
                padding: 44px 0;
            }

            /* === PAGE HERO === */
            .page-hero-inner {
                padding: 56px 20px 44px;
            }

            .page-hero-inner .h1 {
                font-size: clamp(30px, 8vw, 44px);
            }

            /* === HOME HERO === */
            .hero {
                grid-template-columns: 1fr;
                min-height: auto;
                padding-top: 58px;
            }

            .hero-left {
                padding: 48px 20px 44px;
                order: 2;
            }

            .hero-right {
                order: 1;
                height: 56vw;
                min-height: 260px;
                max-height: 340px;
            }

            /* Hero right: mosaic becomes single panel */
            .hero-img-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 1fr;
            }

            .hig-a {
                grid-row: auto;
            }

            .hig-c {
                display: none;
            }

            .hero-deco,
            .hero-deco2 {
                display: none;
            }

            .hero-h1 {
                font-size: clamp(32px, 8vw, 48px);
                margin-bottom: 16px;
            }

            .hero-desc {
                font-size: 14px;
                margin-bottom: 28px;
                max-width: 100%;
            }

            .hero-btns {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .hero-btns .btn {
                justify-content: center;
                padding: 14px 20px;
            }

            .hero-stats {
                gap: 20px;
                margin-top: 36px;
                padding-top: 24px;
            }

            .hs-num {
                font-size: 26px;
            }

            .hero-card {
                bottom: auto;
                top: 12px;
                left: 12px;
                right: auto;
                padding: 12px 14px;
                min-width: 0;
                border-radius: 12px;
            }

            .hc-val {
                font-size: 18px;
            }

            /* === ABOUT === */
            .about-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .about-imgs {
                height: 320px;
                order: 0;
            }

            .about-main {
                width: 75%;
                height: 85%;
            }

            .about-sub {
                width: 48%;
                height: 50%;
            }

            .about-deco {
                display: none;
            }

            /* === SERVICES === */
            .svc-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .svc-img {
                height: 200px;
            }

            /* === PORTFOLIO HOME === */
            .port-grid {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 180px 180px 180px;
                gap: 8px;
            }

            .port-item:nth-child(1) {
                grid-column: 1 / 3;
            }

            .port-item:nth-child(4) {
                grid-column: auto;
            }

            /* === PROCESS === */
            .proc-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px 16px;
            }

            .proc-grid::before {
                display: none;
            }

            .proc-step:last-child {
                grid-column: 1 / -1;
                max-width: 200px;
                margin: 0 auto;
            }

            /* === REVIEWS === */
            .rev-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            /* Scrollable reviews on mobile */
            .rev-grid {
                display: flex;
                overflow-x: auto;
                scroll-snap-type: x mandatory;
                gap: 12px;
                padding-bottom: 12px;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: none;
            }

            .rev-grid::-webkit-scrollbar {
                display: none;
            }

            .rev-card {
                min-width: 80vw;
                max-width: 80vw;
                scroll-snap-align: start;
                flex-shrink: 0;
            }

            /* === CTA BAND === */
            .cta-band {
                padding: 56px 0;
            }

            .cta-h {
                font-size: clamp(22px, 6vw, 32px);
            }

            .cta-band .btn {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }

            /* === SERVICES PAGE === */
            .pkg-grid {
                grid-template-columns: 1fr;
                padding: 36px 20px;
                gap: 20px;
                max-width: 100%;
            }

            .pkg-card.featured {
                order: -1;
            }

            .addon-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }

            /* === PORTFOLIO PAGE === */
            .pf-filters {
                gap: 6px;
            }

            .pf-btn {
                padding: 6px 12px;
                font-size: 11px;
            }

            .pf-masonry {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
                padding: 32px 0 60px;
            }

            .pf-card:nth-child(3n+2) {
                margin-top: 0;
            }

            .pf-card:nth-child(2n+2) {
                margin-top: 24px;
            }

            /* === STUDIO PAGE === */
            .studio-story-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .studio-img-col {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 200px 140px;
                gap: 8px;
            }

            .sic-main {
                grid-column: 1 / 3;
            }

            .studio-stats {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .st-val {
                font-size: 26px;
            }

            .team-grid {
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            /* === BOOKING PAGE === */
            .booking-layout {
                grid-template-columns: 1fr;
                padding-top: 58px;
            }

            .booking-left {
                padding: 44px 20px 40px;
                background: var(--b1);
            }

            .booking-right {
                padding: 32px 20px 56px;
            }

            .bf-wrap {
                padding: 28px 20px;
                border-radius: 16px;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }

            /* === BLOG PAGE === */
            .blog-layout {
                grid-template-columns: 1fr;
                padding: 36px 0 60px;
                gap: 36px;
            }

            .bfeat-img {
                height: 220px;
            }

            .bc-img {
                width: 90px;
            }

            /* === FOOTER === */
            footer {
                padding: 52px 20px 28px;
            }

            .ft-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }

            .ft-grid>div:first-child {
                grid-column: 1 / -1;
            }

            .ft-bottom {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            /* === HEADER SECTIONS === */
            .section-header-flex {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }
        }

        /* ═══════════════════════════════════════
   SMALL MOBILE — max 430px
═══════════════════════════════════════ */
        @media (max-width: 430px) {

            .hero-right {
                height: 62vw;
                min-height: 240px;
            }

            .hero-h1 {
                font-size: 30px;
            }

            .about-imgs {
                height: 280px;
            }

            .addon-grid {
                grid-template-columns: 1fr;
            }

            .pf-masonry {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .pf-card:nth-child(2n+2) {
                margin-top: 0;
            }

            .pf-card:nth-child(3n+2) {
                margin-top: 0;
            }

            .team-grid {
                grid-template-columns: 1fr 1fr;
            }

            .ft-grid {
                grid-template-columns: 1fr;
            }

            .port-grid {
                grid-template-columns: 1fr;
                grid-template-rows: repeat(5, 220px);
            }

            .port-item:nth-child(1) {
                grid-column: auto;
            }

            .port-item:nth-child(4) {
                grid-column: auto;
            }

            .rev-card {
                min-width: 88vw;
                max-width: 88vw;
            }

            .proc-grid {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .proc-step:last-child {
                grid-column: auto;
                max-width: 100%;
            }

            .h2 {
                font-size: 30px;
            }

            .h1 {
                font-size: 34px;
            }

            .pkg-grid {
                padding: 28px 16px;
            }

            .booking-right {
                padding: 24px 16px 48px;
            }

            .bf-wrap {
                padding: 22px 16px;
            }
        }

    </style>
