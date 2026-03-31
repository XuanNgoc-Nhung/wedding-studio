<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sora Bridal Studio — Hà Nội</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;1,300;1,400;1,500&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @include('user.layouts.partials.styles')
</head>

<body>

    <!-- ══════════════ NAV ══════════════ -->
    <nav class="nav" id="main-nav">
        <a class="nav-logo" href="#" onclick="return false;">
            <div class="nav-logo-mark">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#c8a878" stroke-width="1.5">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" opacity=".4" />
                    <path d="M12 8v4l3 3" />
                </svg>
            </div>
            Sora Bridal
        </a>
        <div class="nav-links">
            <a class="nav-link active" id="nl-home" onclick="go('home')">Trang chủ</a>
            <a class="nav-link" id="nl-services" onclick="go('services')">Dịch vụ</a>
            <a class="nav-link" id="nl-concept" onclick="go('concept')">Concept</a>
            <a class="nav-link" id="nl-portfolio" onclick="go('portfolio')">Portfolio</a>
            <a class="nav-link" id="nl-studio" onclick="go('studio')">Studio</a>
            <a class="nav-link" id="nl-blog" onclick="go('blog')">Blog cưới</a>
        </div>
        <div class="nav-actions">
            <span class="nav-tel">0901 234 567</span>
            <button class="btn-nav" onclick="go('booking')">Đặt lịch tư vấn</button>
        </div>
        <button class="nav-hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </nav>

    <!-- Mobile Drawer -->
    <div class="mobile-menu" id="mobile-menu">
        <div class="mm-link" onclick="go('home');closeMenu()">Trang chủ</div>
        <div class="mm-link" onclick="go('services');closeMenu()">Dịch vụ & Bảng giá</div>
        <div class="mm-link" onclick="go('concept');closeMenu()">Concept & Phim trường</div>
        <div class="mm-link" onclick="go('portfolio');closeMenu()">Portfolio</div>
        <div class="mm-link" onclick="go('studio');closeMenu()">Studio</div>
        <div class="mm-link" onclick="go('blog');closeMenu()">Blog cưới</div>
        <button class="mm-book" onclick="go('booking');closeMenu()">Đặt lịch tư vấn miễn phí →</button>
        <div class="mm-tel">Hotline: 0901 234 567 · Zalo</div>
    </div>

    <!-- ══════════════════════════════════
     PAGE: TRANG CHỦ
══════════════════════════════════ -->
    <div class="page active" id="page-home">

        <!-- Hero -->
        <div class="hero">
            <div class="hero-left">
                <div class="hero-deco"></div>
                <div class="hero-deco2"></div>
                <div class="hero-tag">Studio ảnh cưới Hà Nội · Thành lập 2018</div>
                <h1 class="hero-h1">
                    Lưu giữ những<br>
                    khoảnh khắc<br>
                    <em>đẹp nhất đời bạn</em>
                </h1>
                <p class="hero-desc">Sora Bridal — nơi mỗi bộ ảnh cưới là một tác phẩm nghệ thuật, được tạo ra bằng cả
                    trái tim, chuyên nghiệp và tinh tế.</p>
                <div class="hero-btns">
                    <button class="btn btn-dark" onclick="go('booking')">Đặt lịch tư vấn miễn phí</button>
                    <button class="btn btn-outline" onclick="go('portfolio')">Xem tác phẩm →</button>
                </div>
                <div class="hero-stats">
                    <div>
                        <div class="hs-num">1.200<em>+</em></div>
                        <div class="hs-lbl">Cặp đôi tin tưởng</div>
                    </div>
                    <div>
                        <div class="hs-num">7</div>
                        <div class="hs-lbl">Năm kinh nghiệm</div>
                    </div>
                    <div>
                        <div class="hs-num">4.9<em>★</em></div>
                        <div class="hs-lbl">Đánh giá Google</div>
                    </div>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-img-grid">
                    <div class="hig-a">
                        <div class="ph gk1" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                    </div>
                    <div class="hig-b">
                        <div class="ph gk3" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                    </div>
                    <div class="hig-c">
                        <div class="ph gk5" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                    </div>
                </div>
                <div class="hero-card">
                    <div class="hc-stars">★★★★★</div>
                    <div class="hc-val">1.200+</div>
                    <div class="hc-lbl">cặp đôi hài lòng</div>
                </div>
            </div>
        </div>

        <!-- Marquee -->
        <div class="marquee-wrap">
            <div class="marquee-track">
                <div class="mi">
                    <div class="mi-dot"></div>Ảnh cưới<div class="mi-dot"></div>Pre-wedding<div class="mi-dot"></div>
                    Album cao cấp<div class="mi-dot"></div>Video highlight<div class="mi-dot"></div>Makeup cưới<div
                        class="mi-dot"></div>Trang phục<div class="mi-dot"></div>Concept cưới<div class="mi-dot"></div>
                    Hà Nội
                </div>
                <div class="mi">
                    <div class="mi-dot"></div>Ảnh cưới<div class="mi-dot"></div>Pre-wedding<div class="mi-dot"></div>
                    Album cao cấp<div class="mi-dot"></div>Video highlight<div class="mi-dot"></div>Makeup cưới<div
                        class="mi-dot"></div>Trang phục<div class="mi-dot"></div>Concept cưới<div class="mi-dot"></div>
                    Hà Nội
                </div>
            </div>
        </div>

        <!-- About -->
        <section class="sec" style="background:var(--b1);">
            <div class="wrap">
                <div class="about-grid">
                    <div class="about-imgs r">
                        <div class="about-main">
                            <div class="ph gk2" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                        <div class="about-sub">
                            <div class="ph gk4" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                        <div class="about-deco"></div>
                    </div>
                    <div class="r d2">
                        <div class="eyebrow">Về chúng tôi</div>
                        <h2 class="h2" style="margin-bottom:20px;">Nghệ thuật<br><span class="em-text">nhiếp ảnh
                                cưới</span></h2>
                        <p class="about-quote">"Mỗi cặp đôi mang một câu chuyện riêng — và chúng tôi ở đây để kể câu
                            chuyện đó bằng ánh sáng."</p>
                        <p class="about-body">Sora Bridal thành lập năm 2018, ra đời từ niềm đam mê nghệ thuật và khát
                            vọng nâng tầm ảnh cưới Việt Nam. Hơn 7 năm đồng hành cùng hơn 1.200 cặp đôi trên hành trình
                            đến với hạnh phúc.</p>
                        <p class="about-body">Phong cách chụp của Sora chú trọng cảm xúc thật, ánh sáng tự nhiên và từng
                            khoảnh khắc không thể tái hiện.</p>
                        <div style="margin-top:24px;">
                            <button class="btn btn-outline" onclick="go('studio')">Tìm hiểu thêm về studio →</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ PHIM TRƯỜNG ═══ -->

        <!-- Studio intro banner -->
        <div class="studio-intro">
            <div class="wrap" style="position:relative;z-index:1;">
                <div class="si-eyebrow r">Hệ thống phim trường</div>
                <h2 class="si-title r">Phim trường cưới<br><em>lớn nhất Hà Nội</em></h2>
                <p class="si-sub r">Sora Bridal sở hữu hệ thống 2 phim trường quy mô với hơn 40 concept độc quyền —
                    không gian chụp ảnh đa phong cách, đầy đủ ánh sáng chuyên nghiệp và ekip hỗ trợ toàn diện.</p>
                <div class="r">
                    <button class="btn btn-white" onclick="go('concept')">✦ Concept hot tại Sora</button>
                </div>
                <div class="si-stats r">
                    <div class="si-stat">
                        <div class="si-stat-num">2</div>
                        <div class="si-stat-lbl">Phim trường độc quyền</div>
                    </div>
                    <div class="si-stat">
                        <div class="si-stat-num">40+</div>
                        <div class="si-stat-lbl">Concept đa phong cách</div>
                    </div>
                    <div class="si-stat">
                        <div class="si-stat-num">3.000m²</div>
                        <div class="si-stat-lbl">Tổng diện tích</div>
                    </div>
                    <div class="si-stat">
                        <div class="si-stat-num">100%</div>
                        <div class="si-stat-lbl">Ánh sáng chuyên nghiệp</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services -->
        <section class="sec">
            <div class="wrap">
                <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;">
                    <div class="r">
                        <div class="eyebrow">Dịch vụ</div>
                        <h2 class="h2">Gói chụp <span class="em-text">phù hợp với bạn</span></h2>
                    </div>
                    <button class="btn btn-outline r d1" onclick="go('services')">Xem tất cả →</button>
                </div>
                <div class="svc-grid r">
                    <div class="svc-card" onclick="go('services')">
                        <div class="svc-img">
                            <div class="ph gk1" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="svc-badge">Phổ biến nhất</div>
                        </div>
                        <div class="svc-body">
                            <div class="svc-title">Gói Studio</div>
                            <p class="svc-desc">Chụp tại studio với concept độc đáo, trang phục đa dạng và makeup chuyên
                                nghiệp.</p>
                            <div class="svc-footer"><span class="svc-price">Từ 8.500.000đ</span>
                                <div class="svc-arrow">›</div>
                            </div>
                        </div>
                    </div>
                    <div class="svc-card" onclick="go('services')">
                        <div class="svc-img">
                            <div class="ph gk4" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="svc-badge">Được yêu thích</div>
                        </div>
                        <div class="svc-body">
                            <div class="svc-title">Gói Premium</div>
                            <p class="svc-desc">Trọn gói studio + ngoại cảnh, video highlight và album cao cấp hoàn
                                chỉnh.</p>
                            <div class="svc-footer"><span class="svc-price">Từ 15.000.000đ</span>
                                <div class="svc-arrow">›</div>
                            </div>
                        </div>
                    </div>
                    <div class="svc-card" onclick="go('services')">
                        <div class="svc-img">
                            <div class="ph gk6" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                        <div class="svc-body">
                            <div class="svc-title">Gói Luxury</div>
                            <p class="svc-desc">Trải nghiệm đẳng cấp từ A–Z: nhiều ngày, đa địa điểm, êkíp riêng.</p>
                            <div class="svc-footer"><span class="svc-price">Từ 25.000.000đ</span>
                                <div class="svc-arrow">›</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Portfolio -->
        <section class="sec" style="background:var(--b1);">
            <div class="wrap">
                <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;">
                    <div class="r">
                        <div class="eyebrow">Tác phẩm</div>
                        <h2 class="h2">Những câu chuyện <span class="em-text">đã được kể</span></h2>
                    </div>
                    <button class="btn btn-outline r d1" onclick="go('portfolio')">Xem toàn bộ →</button>
                </div>
                <div class="port-grid r">
                    <div class="port-item">
                        <div class="ph gk1" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                        <div class="port-overlay"></div>
                        <div class="port-info">
                            <div class="port-couple">Minh & Hoàng Lan</div>
                            <div class="port-type">Studio · Concept</div>
                        </div>
                    </div>
                    <div class="port-item">
                        <div class="ph gk3" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                        <div class="port-overlay"></div>
                        <div class="port-info">
                            <div class="port-couple">Hùng & Thu Hà</div>
                            <div class="port-type">Hồ Tây · Ngoại cảnh</div>
                        </div>
                    </div>
                    <div class="port-item">
                        <div class="ph gk5" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                        <div class="port-overlay"></div>
                        <div class="port-info">
                            <div class="port-couple">An & Linh Chi</div>
                            <div class="port-type">Phố cổ Hà Nội</div>
                        </div>
                    </div>
                    <div class="port-item">
                        <div class="ph gk2" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                        <div class="port-overlay"></div>
                        <div class="port-info">
                            <div class="port-couple">Tuấn & Bảo Mai</div>
                            <div class="port-type">Đà Lạt · Luxury</div>
                        </div>
                    </div>
                    <div class="port-item">
                        <div class="ph gk7" style="height:100%;">
                            <div class="ph-icon-wrap"><svg width="30" height="30" viewBox="0 0 24 24" fill="none"
                                    stroke="#7a5a34" stroke-width="1">
                                    <rect x="3" y="3" width="18" height="18" rx="1" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" /></svg></div>
                        </div>
                        <div class="port-overlay"></div>
                        <div class="port-info">
                            <div class="port-couple">Nam & Thanh Thu</div>
                            <div class="port-type">Film tone</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process -->
        <section class="sec">
            <div class="wrap">
                <div class="r" style="text-align:center;margin-bottom:56px;">
                    <div class="eyebrow" style="justify-content:center;">Quy trình</div>
                    <h2 class="h2">5 bước đến <span class="em-text">bộ ảnh hoàn hảo</span></h2>
                </div>
                <div class="proc-grid r">
                    <div class="proc-step">
                        <div class="proc-num">1</div>
                        <div class="proc-title">Tư vấn miễn phí</div>
                        <p class="proc-desc">Trao đổi concept, phong cách và ngày chụp phù hợp</p>
                    </div>
                    <div class="proc-step">
                        <div class="proc-num">2</div>
                        <div class="proc-title">Ký hợp đồng</div>
                        <p class="proc-desc">Xác nhận gói, đặt cọc và lên lịch chi tiết</p>
                    </div>
                    <div class="proc-step">
                        <div class="proc-num">3</div>
                        <div class="proc-title">Ngày chụp</div>
                        <p class="proc-desc">Trải nghiệm cùng đội ngũ chuyên nghiệp</p>
                    </div>
                    <div class="proc-step">
                        <div class="proc-num">4</div>
                        <div class="proc-title">Chọn ảnh demo</div>
                        <p class="proc-desc">Duyệt và chọn ảnh ưng ý để chỉnh màu</p>
                    </div>
                    <div class="proc-step">
                        <div class="proc-num">5</div>
                        <div class="proc-title">Nhận thành phẩm</div>
                        <p class="proc-desc">Ảnh, video, album trong 30 ngày</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews -->
        <section class="sec" style="background:var(--b1);">
            <div class="wrap">
                <div class="r" style="text-align:center;margin-bottom:40px;">
                    <div class="eyebrow" style="justify-content:center;">Cảm nhận</div>
                    <h2 class="h2">Khách hàng <span class="em-text">nói gì về chúng tôi</span></h2>
                </div>
                <div class="rev-grid r">
                    <div class="rev-card">
                        <div class="rev-stars">★★★★★</div>
                        <p class="rev-text">"Sora đã làm tôi khóc khi nhìn thấy bộ ảnh. Không ngờ mình có thể đẹp đến
                            vậy trong ngày cưới!"</p>
                        <div class="rev-author">
                            <div class="rev-av">HL</div>
                            <div>
                                <div class="rev-name">Hoàng Linh & Minh Khoa</div>
                                <div class="rev-date">Tháng 3, 2026</div>
                            </div>
                        </div>
                    </div>
                    <div class="rev-card">
                        <div class="rev-stars">★★★★★</div>
                        <p class="rev-text">"Gói Premium rất xứng đáng. Từ makeup đến chụp đều chuyên nghiệp. Bộ ảnh đẹp
                            hơn mọi kỳ vọng."</p>
                        <div class="rev-author">
                            <div class="rev-av">TH</div>
                            <div>
                                <div class="rev-name">Thu Hà & Tiến Hùng</div>
                                <div class="rev-date">Tháng 1, 2026</div>
                            </div>
                        </div>
                    </div>
                    <div class="rev-card">
                        <div class="rev-stars">★★★★★</div>
                        <p class="rev-text">"Studio đẹp, ekip thân thiện, concept phong phú. Đã giới thiệu Sora cho 3
                            cặp bạn bè chuẩn bị cưới!"</p>
                        <div class="rev-author">
                            <div class="rev-av">BN</div>
                            <div>
                                <div class="rev-name">Bích Ngọc & Quốc Tuấn</div>
                                <div class="rev-date">Tháng 12, 2025</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <div class="cta-band">
            <div class="wrap" style="position:relative;z-index:1;">
                <div class="eyebrow" style="justify-content:center;color:var(--br-l);">Bắt đầu ngay hôm nay</div>
                <div class="cta-h">Để Sora kể <em>câu chuyện của bạn</em></div>
                <p class="cta-sub">Tư vấn hoàn toàn miễn phí · Không áp lực · Phản hồi trong 2 giờ</p>
                <button class="btn btn-white" onclick="go('booking')">Đặt lịch tư vấn ngay →</button>
            </div>
        </div>

    </div><!-- /page-home -->

    <!-- ══════════════════════════════════
     PAGE: DỊCH VỤ
══════════════════════════════════ -->
    <div class="page" id="page-services">
        <div class="page-hero">
            <div class="page-hero-inner">
                <div class="wrap">
                    <div class="r" style="text-align:center;">
                        <div class="eyebrow" style="justify-content:center;">Dịch vụ & Bảng giá</div>
                        <h1 class="h1" style="margin-bottom:14px;">Gói chụp <span class="em-text">cho mọi cặp đôi</span>
                        </h1>
                        <p class="lead" style="margin:0 auto;text-align:center;max-width:460px;">Mỗi gói đều bao gồm tư
                            vấn concept miễn phí và cam kết chất lượng từ Sora.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pkg-grid r">
            <div class="pkg-card">
                <div class="pkg-img">
                    <div class="ph gk1" style="height:100%;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                </div>
                <div class="pkg-body">
                    <div class="pkg-name">Gói Studio</div>
                    <div class="pkg-sub">Studio Package</div>
                    <div class="pkg-price">8.500.000đ</div>
                    <div class="pkg-note">Trọn gói — không phát sinh thêm</div>
                    <div class="pkg-sep"></div>
                    <ul class="pkg-list">
                        <li>
                            <div class="pkg-check">✓</div>Chụp 1 ngày tại studio Sora
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>2 concept trang phục khác nhau
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Makeup & làm tóc chuyên nghiệp
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>40 ảnh chỉnh màu hoàn chỉnh
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>File RAW gốc không giới hạn
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Giao ảnh trong 21 ngày
                        </li>
                    </ul>
                    <button class="pkg-btn" onclick="go('booking')">Đặt lịch tư vấn</button>
                </div>
            </div>
            <div class="pkg-card featured">
                <div class="pkg-ribbon">Được chọn nhiều nhất</div>
                <div class="pkg-img">
                    <div class="ph gk4" style="height:100%;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                </div>
                <div class="pkg-body">
                    <div class="pkg-name">Gói Premium</div>
                    <div class="pkg-sub">Premium Package</div>
                    <div class="pkg-price">15.000.000đ</div>
                    <div class="pkg-note">Studio + Ngoại cảnh trọn gói</div>
                    <div class="pkg-sep"></div>
                    <ul class="pkg-list">
                        <li>
                            <div class="pkg-check">✓</div>Tất cả dịch vụ gói Studio
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Chụp ngoại cảnh 1 ngày (2 địa điểm)
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>4 concept + 4 váy cưới
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>80 ảnh chỉnh màu cao cấp
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Video highlight 3 phút
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Album photobook 30×40cm (30 trang)
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Giao thành phẩm trong 30 ngày
                        </li>
                    </ul>
                    <button class="pkg-btn" onclick="go('booking')">Đặt lịch tư vấn</button>
                </div>
            </div>
            <div class="pkg-card">
                <div class="pkg-img">
                    <div class="ph gk6" style="height:100%;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                </div>
                <div class="pkg-body">
                    <div class="pkg-name">Gói Luxury</div>
                    <div class="pkg-sub">Luxury Package</div>
                    <div class="pkg-price">25.000.000đ</div>
                    <div class="pkg-note">Trải nghiệm toàn diện từ A đến Z</div>
                    <div class="pkg-sep"></div>
                    <ul class="pkg-list">
                        <li>
                            <div class="pkg-check">✓</div>Tất cả dịch vụ gói Premium
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>2 ngày studio + 2 ngày ngoại cảnh
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Nhiếp ảnh gia senior riêng
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>150 ảnh + ảnh phóng lớn
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Phim tình yêu 10–15 phút
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Album luxury in UV cao cấp
                        </li>
                        <li>
                            <div class="pkg-check">✓</div>Có thể chụp Hà Nội / Đà Lạt / Hội An
                        </li>
                    </ul>
                    <button class="pkg-btn" onclick="go('booking')">Đặt lịch tư vấn</button>
                </div>
            </div>
        </div>

        <!-- Add-ons -->
        <section style="padding:0 0 80px;">
            <div class="wrap">
                <div class="r" style="margin-bottom:32px;">
                    <div class="eyebrow">Dịch vụ bổ sung</div>
                    <h2 class="h2">Tuỳ chỉnh <span class="em-text">theo nhu cầu</span></h2>
                </div>
                <div class="addon-grid r">
                    <div class="addon-card">
                        <div class="addon-icon">💄</div>
                        <div class="addon-name">Makeup thêm lần</div>
                        <p class="addon-desc">Thay đổi phong cách makeup cho từng concept</p>
                        <div class="addon-price">+ 500.000đ / lần</div>
                    </div>
                    <div class="addon-card">
                        <div class="addon-icon">📸</div>
                        <div class="addon-name">Ảnh chỉnh thêm</div>
                        <p class="addon-desc">Chỉnh màu bổ sung ngoài gói ban đầu</p>
                        <div class="addon-price">+ 80.000đ / ảnh</div>
                    </div>
                    <div class="addon-card">
                        <div class="addon-icon">🎬</div>
                        <div class="addon-name">Teaser video 60s</div>
                        <p class="addon-desc">Clip ngắn tối ưu cho reels và TikTok</p>
                        <div class="addon-price">+ 2.000.000đ</div>
                    </div>
                    <div class="addon-card">
                        <div class="addon-icon">📖</div>
                        <div class="addon-name">Album bổ sung</div>
                        <p class="addon-desc">Album photobook in thêm tặng gia đình</p>
                        <div class="addon-price">+ 3.500.000đ</div>
                    </div>
                </div>
            </div>
        </section>

        <div class="cta-band">
            <div class="wrap" style="position:relative;z-index:1;">
                <div class="cta-h">Chưa biết chọn gói nào? <em>Để Sora tư vấn</em></div>
                <p class="cta-sub">Hoàn toàn miễn phí · Không áp lực</p><button class="btn btn-white"
                    onclick="go('booking')">Đặt lịch tư vấn →</button>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
     PAGE: PORTFOLIO
══════════════════════════════════ -->
    <div class="page" id="page-portfolio">
        <div class="page-hero">
            <div class="page-hero-inner">
                <div class="wrap">
                    <div class="r">
                        <div class="eyebrow">Tác phẩm</div>
                        <h1 class="h1" style="margin-bottom:14px;">Những câu chuyện <span class="em-text">chúng tôi đã
                                kể</span></h1>
                        <p class="lead">Hơn 1.200 cặp đôi — mỗi bộ ảnh là một tác phẩm độc nhất.</p>
                        <div class="pf-filters" id="pf-filters">
                            <button class="pf-btn on" data-filter="all">Tất cả</button>
                            <button class="pf-btn" data-filter="studio">Studio</button>
                            <button class="pf-btn" data-filter="ngoai-canh">Ngoại cảnh HN</button>
                            <button class="pf-btn" data-filter="da-lat">Đà Lạt</button>
                            <button class="pf-btn" data-filter="hoi-an">Hội An</button>
                            <button class="pf-btn" data-filter="han-quoc">Concept Hàn</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrap">
            <div class="pf-masonry r" id="pf-grid">
                <div class="pf-card" data-cat="studio">
                    <div class="ph gk1" style="aspect-ratio:3/4;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                    <div class="pf-info">
                        <div class="pf-couple">Minh & Hoàng Lan</div>
                        <div class="pf-type">Studio · Concept Hàn Quốc</div>
                    </div>
                </div>
                <div class="pf-card" data-cat="ngoai-canh">
                    <div class="ph gk3" style="aspect-ratio:3/4;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                    <div class="pf-info">
                        <div class="pf-couple">Hùng & Thu Hà</div>
                        <div class="pf-type">Hồ Tây · Ngoại cảnh</div>
                    </div>
                </div>
                <div class="pf-card" data-cat="ngoai-canh">
                    <div class="ph gk5" style="aspect-ratio:3/4;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                    <div class="pf-info">
                        <div class="pf-couple">An & Linh Chi</div>
                        <div class="pf-type">Phố cổ Hà Nội</div>
                    </div>
                </div>
                <div class="pf-card" data-cat="da-lat">
                    <div class="ph gk2" style="aspect-ratio:3/4;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                    <div class="pf-info">
                        <div class="pf-couple">Tuấn & Bảo Mai</div>
                        <div class="pf-type">Đà Lạt · Luxury</div>
                    </div>
                </div>
                <div class="pf-card" data-cat="studio">
                    <div class="ph gk7" style="aspect-ratio:3/4;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                    <div class="pf-info">
                        <div class="pf-couple">Nam & Thanh Thu</div>
                        <div class="pf-type">Studio · Film tone</div>
                    </div>
                </div>
                <div class="pf-card" data-cat="hoi-an">
                    <div class="ph gk4" style="aspect-ratio:3/4;">
                        <div class="ph-icon-wrap"><svg width="36" height="36" viewBox="0 0 24 24" fill="none"
                                stroke="#7a5a34" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="1" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" /></svg></div>
                    </div>
                    <div class="pf-info">
                        <div class="pf-couple">Khoa & Mỹ Linh</div>
                        <div class="pf-type">Hội An · Pre-wedding</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
     PAGE: STUDIO
══════════════════════════════════ -->
    <div class="page" id="page-studio">
        <div class="page-hero">
            <div class="page-hero-inner">
                <div class="wrap r">
                    <div class="eyebrow">Về chúng tôi</div>
                    <h1 class="h1">Câu chuyện của <span class="em-text">Sora Bridal</span></h1>
                    <p class="lead">Thành lập 2018 từ niềm đam mê nghệ thuật và khát vọng nâng tầm ảnh cưới Việt Nam.
                    </p>
                </div>
            </div>
        </div>
        <section class="sec">
            <div class="wrap">
                <div class="studio-story-grid">
                    <div class="studio-img-col r">
                        <div class="sic-main">
                            <div class="ph gk2" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                        <div class="sic-sub">
                            <div class="ph gk4" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                        <div class="sic-sub">
                            <div class="ph gk6" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                    </div>
                    <div class="r d2">
                        <div class="eyebrow">Câu chuyện</div>
                        <h2 class="h2" style="margin-bottom:18px;">Hơn 7 năm <span class="em-text">đồng hành cùng
                                bạn</span></h2>
                        <p class="about-body">Sora Bridal được thành lập bởi những người yêu nghệ thuật và trân trọng
                            khoảnh khắc đẹp. Từ một studio nhỏ tại Hà Nội, chúng tôi đã lớn lên cùng hơn 1.200 cặp đôi
                            tin tưởng.</p>
                        <p class="about-body">Chúng tôi theo đuổi phong cách tinh tế, tự nhiên — lấy cảm xúc thật làm
                            trọng tâm. Không gượng ép, không giả tạo.</p>
                        <div class="studio-stats">
                            <div class="st-stat">
                                <div class="st-val">1.200+</div>
                                <div class="st-lbl">Cặp đôi tin tưởng</div>
                            </div>
                            <div class="st-stat">
                                <div class="st-val">7+ năm</div>
                                <div class="st-lbl">Kinh nghiệm</div>
                            </div>
                            <div class="st-stat">
                                <div class="st-val">12</div>
                                <div class="st-lbl">Nhiếp ảnh gia</div>
                            </div>
                            <div class="st-stat">
                                <div class="st-val">4.9 ★</div>
                                <div class="st-lbl">Google rating</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="sec" style="background:var(--b1);">
            <div class="wrap">
                <div class="r" style="text-align:center;margin-bottom:48px;">
                    <div class="eyebrow" style="justify-content:center;">Đội ngũ</div>
                    <h2 class="h2">Những người <span class="em-text">kể câu chuyện của bạn</span></h2>
                </div>
                <div class="team-grid r">
                    <div class="team-card">
                        <div class="team-av">
                            <div class="ph gk2" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <circle cx="12" cy="8" r="5" />
                                        <path d="M3 21v-2a7 7 0 0 1 14 0v2" /></svg></div>
                            </div>
                        </div>
                        <div class="team-name">Hùng Nguyễn</div>
                        <div class="team-role">Founder · Senior Photographer</div>
                        <p class="team-desc">10 năm kinh nghiệm, chuyên ảnh cưới phong cách tự nhiên và điện ảnh.</p>
                    </div>
                    <div class="team-card">
                        <div class="team-av">
                            <div class="ph gk4" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <circle cx="12" cy="8" r="5" />
                                        <path d="M3 21v-2a7 7 0 0 1 14 0v2" /></svg></div>
                            </div>
                        </div>
                        <div class="team-name">Linh Trần</div>
                        <div class="team-role">Creative Director</div>
                        <p class="team-desc">Chuyên thiết kế concept và định hướng phong cách cho từng cặp đôi.</p>
                    </div>
                    <div class="team-card">
                        <div class="team-av">
                            <div class="ph gk3" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <circle cx="12" cy="8" r="5" />
                                        <path d="M3 21v-2a7 7 0 0 1 14 0v2" /></svg></div>
                            </div>
                        </div>
                        <div class="team-name">Mai Phương</div>
                        <div class="team-role">Lead Makeup Artist</div>
                        <p class="team-desc">Chuyên gia trang điểm cô dâu, phong cách tự nhiên và tinh tế.</p>
                    </div>
                    <div class="team-card">
                        <div class="team-av">
                            <div class="ph gk6" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <circle cx="12" cy="8" r="5" />
                                        <path d="M3 21v-2a7 7 0 0 1 14 0v2" /></svg></div>
                            </div>
                        </div>
                        <div class="team-name">Nam Vũ</div>
                        <div class="team-role">Videographer</div>
                        <p class="team-desc">Chuyên quay và dựng video highlight, phim tài liệu tình yêu.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ══════════════════════════════════
     PAGE: ĐẶT LỊCH
══════════════════════════════════ -->
    <div class="page" id="page-booking">
        <div class="booking-layout">
            <div class="booking-left">
                <div class="eyebrow">Đặt lịch</div>
                <h2 class="h2" style="margin-bottom:16px;">Bắt đầu <span class="em-text">hành trình của bạn</span></h2>
                <p class="lead">Điền form bên cạnh — Sora sẽ liên hệ lại trong vòng 2 giờ, hoàn toàn miễn phí.</p>
                <div class="bl-info">
                    <div class="bli">
                        <div class="bli-icon">📍</div>
                        <div>
                            <div class="bli-lbl">Địa chỉ</div>
                            <div class="bli-val">18 Trần Phú, Hoàn Kiếm, Hà Nội</div>
                        </div>
                    </div>
                    <div class="bli">
                        <div class="bli-icon">📞</div>
                        <div>
                            <div class="bli-lbl">Hotline</div>
                            <div class="bli-val">0901 234 567 (Zalo · Gọi điện)</div>
                        </div>
                    </div>
                    <div class="bli">
                        <div class="bli-icon">🕐</div>
                        <div>
                            <div class="bli-lbl">Giờ làm việc</div>
                            <div class="bli-val">8:00 – 20:00 · Thứ 2 đến Chủ nhật</div>
                        </div>
                    </div>
                    <div class="bli">
                        <div class="bli-icon">✦</div>
                        <div>
                            <div class="bli-lbl">Cam kết</div>
                            <div class="bli-val">Tư vấn miễn phí · Phản hồi trong 2 giờ</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="booking-right">
                <div class="bf-wrap">
                    <div class="bf-heading">Gửi yêu cầu tư vấn</div>
                    <div class="bf-sub">Sora sẽ liên hệ lại trong vòng 2 giờ trong ngày làm việc</div>
                    <div class="form-row">
                        <div class="fg"><label>Tên cô dâu</label><input type="text" placeholder="Nguyễn Thị..."></div>
                        <div class="fg"><label>Tên chú rể</label><input type="text" placeholder="Trần Văn..."></div>
                    </div>
                    <div class="form-row">
                        <div class="fg"><label>Số điện thoại</label><input type="tel" placeholder="09xx xxx xxx"></div>
                        <div class="fg"><label>Ngày cưới dự kiến</label><input type="date"></div>
                    </div>
                    <div class="form-row">
                        <div class="fg">
                            <label>Gói quan tâm</label>
                            <select>
                                <option value="">Chọn gói...</option>
                                <option>Gói Studio — từ 8.500.000đ</option>
                                <option>Gói Premium — từ 15.000.000đ</option>
                                <option>Gói Luxury — từ 25.000.000đ</option>
                                <option>Chưa biết, cần tư vấn</option>
                            </select>
                        </div>
                        <div class="fg">
                            <label>Ngân sách dự kiến</label>
                            <select>
                                <option value="">Chọn mức...</option>
                                <option>Dưới 10 triệu</option>
                                <option>10 – 20 triệu</option>
                                <option>20 – 30 triệu</option>
                                <option>Trên 30 triệu</option>
                            </select>
                        </div>
                    </div>
                    <div class="fg"><label>Ghi chú / Yêu cầu đặc biệt</label><textarea
                            placeholder="Concept mong muốn, địa điểm ngoại cảnh, hay bất kỳ điều gì muốn chia sẻ..."></textarea>
                    </div>
                    <button class="btn-submit"
                        onclick="alert('Cảm ơn! Sora sẽ liên hệ với bạn trong vòng 2 giờ 🤍')">Gửi yêu cầu tư vấn
                        →</button>
                    <p class="form-note">Hoặc nhắn Zalo trực tiếp: 0901 234 567</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
     PAGE: BLOG
══════════════════════════════════ -->
    <div class="page" id="page-blog">
        <div class="page-hero">
            <div class="page-hero-inner">
                <div class="wrap r">
                    <div class="eyebrow">Blog cưới</div>
                    <h1 class="h1">Cẩm nang <span class="em-text">cho ngày trọng đại</span></h1>
                    <p class="lead">Tips chụp ảnh, xu hướng, chia sẻ concept — cập nhật mỗi tuần từ đội ngũ Sora.</p>
                </div>
            </div>
        </div>
        <div class="wrap">
            <div class="blog-layout">
                <div>
                    <div class="blog-featured">
                        <div class="bfeat-img">
                            <div class="ph gk1" style="height:100%;">
                                <div class="ph-icon-wrap"><svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                        </div>
                        <div class="bfeat-body">
                            <div class="bfeat-cat">Xu hướng 2026</div>
                            <div class="bfeat-title">Concept ảnh cưới phong cách Hàn Quốc đang hot nhất 2026 — Sora giải
                                mã tất cả</div>
                            <p class="bfeat-excerpt">Từ tông màu nâu be ấm áp, ánh sáng mềm mại đến bố cục tinh tế —
                                phong cách này đang chiếm lĩnh ảnh cưới Việt Nam. Cùng Sora tìm hiểu cách tái hiện
                                aesthetic này đúng cách nhất...</p>
                            <div class="bfeat-meta">15/03/2026 · 8 phút đọc · Hùng Nguyễn</div>
                        </div>
                    </div>
                    <div class="blog-list">
                        <div class="blog-card">
                            <div class="bc-img">
                                <div class="ph gk2" style="height:100%;">
                                    <div class="ph-icon-wrap"><svg width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="bc-body">
                                <div class="bc-cat">Tips cô dâu</div>
                                <div class="bc-title">5 điều cần chuẩn bị trước ngày chụp studio để ảnh đẹp nhất</div>
                                <div class="bc-meta">10/03/2026 · 5 phút đọc</div>
                            </div>
                        </div>
                        <div class="blog-card">
                            <div class="bc-img">
                                <div class="ph gk4" style="height:100%;">
                                    <div class="ph-icon-wrap"><svg width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="bc-body">
                                <div class="bc-cat">Địa điểm</div>
                                <div class="bc-title">Top 7 địa điểm chụp ảnh cưới ngoại cảnh đẹp nhất Hà Nội 2026</div>
                                <div class="bc-meta">05/03/2026 · 6 phút đọc</div>
                            </div>
                        </div>
                        <div class="blog-card">
                            <div class="bc-img">
                                <div class="ph gk6" style="height:100%;">
                                    <div class="ph-icon-wrap"><svg width="22" height="22" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="bc-body">
                                <div class="bc-cat">Chia sẻ</div>
                                <div class="bc-title">Hành trình từ concept đến bộ ảnh hoàn hảo của Minh & Lan tại Sora
                                </div>
                                <div class="bc-meta">01/03/2026 · 4 phút đọc</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="bs-block">
                        <div class="bs-title">Chủ đề phổ biến</div><span class="bs-tag">Xu hướng 2026</span><span
                            class="bs-tag">Concept Hàn</span><span class="bs-tag">Tips cô dâu</span><span
                            class="bs-tag">Film tone</span><span class="bs-tag">Địa điểm HN</span><span
                            class="bs-tag">Đà Lạt</span><span class="bs-tag">Pre-wedding</span><span
                            class="bs-tag">Makeup</span><span class="bs-tag">Album</span>
                    </div>
                    <div class="bs-newsletter">
                        <div class="bs-nl-title">Nhận tips mỗi tuần?</div>
                        <p class="bs-nl-sub">Đăng ký nhận newsletter — tips cưới, xu hướng và ưu đãi độc quyền từ Sora.
                        </p>
                        <input class="bs-nl-input" type="email" placeholder="Email của bạn...">
                        <button class="bs-nl-btn" onclick="alert('Đăng ký thành công! Cảm ơn bạn 🤍')">Đăng ký nhận bản
                            tin</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════
     PAGE: CONCEPT & PHIM TRƯỜNG
══════════════════════════════════ -->
    <div class="page" id="page-concept">

        <!-- Hero -->
        <div class="page-hero">
            <div class="page-hero-inner" style="background:var(--text);padding-bottom:0;">
                <div class="wrap">
                    <div class="r" style="text-align:center;">
                        <div class="si-eyebrow" style="justify-content:center;">Hệ thống phim trường · 2 địa điểm · 40+
                            concept</div>
                        <h1 class="si-title" style="margin-bottom:14px;">Concept hot<br><em>tại Sora Bridal</em></h1>
                        <p class="si-sub" style="margin-bottom:40px;">Phim trường cưới lớn nhất Hà Nội — mỗi góc phòng
                            là một thế giới riêng, mỗi concept là một câu chuyện tình được dàn dựng công phu.</p>
                        <!-- Stats inline -->
                        <div class="si-stats r"
                            style="border-top:1px solid rgba(255,255,255,.08);padding-top:36px;margin-top:0;">
                            <div class="si-stat">
                                <div class="si-stat-num">2</div>
                                <div class="si-stat-lbl">Phim trường</div>
                            </div>
                            <div class="si-stat">
                                <div class="si-stat-num">40+</div>
                                <div class="si-stat-lbl">Concept độc quyền</div>
                            </div>
                            <div class="si-stat">
                                <div class="si-stat-num">3.000m²</div>
                                <div class="si-stat-lbl">Tổng diện tích</div>
                            </div>
                            <div class="si-stat">
                                <div class="si-stat-num">200+</div>
                                <div class="si-stat-lbl">Trang phục cưới</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab navigation -->
        <div style="background:var(--b0);padding:52px 0 0;">
            <div class="wrap">
                <div class="r" style="text-align:center;margin-bottom:36px;">
                    <div class="eyebrow" style="justify-content:center;">Khám phá phim trường</div>
                    <h2 class="h2">Hai thế giới <span class="em-text">một tình yêu</span></h2>
                    <p style="font-size:14px;color:var(--text2);margin-top:10px;font-weight:300;">Chọn phim trường để
                        xem chi tiết không gian và concept</p>
                </div>
            </div>

            <div class="tab-nav" id="concept-tabs" style="margin-bottom:0;">
                <button class="tab-btn active" data-tab="ct-biet-thu" onclick="switchConceptTab('ct-biet-thu')">
                    Biệt thự tình ái
                    <span>Không gian lãng mạn · 20+ concept</span>
                </button>
                <button class="tab-btn" data-tab="ct-rooftop" onclick="switchConceptTab('ct-rooftop')">
                    Sora Rooftop
                    <span>View thành phố · 20+ concept</span>
                </button>
            </div>
        </div>

        <!-- ── TAB 1: Biệt thự tình ái ── -->
        <div class="tab-panel active" id="tab-ct-biet-thu" style="background:var(--b0);">

            <!-- Giới thiệu phim trường -->
            <div style="padding:64px 0 0;">
                <div class="wrap">
                    <div class="tp-layout r">
                        <div class="tp-info" style="padding-left:0;">
                            <div class="tp-tag">✦ Phim trường 1 — Tầng 1 đến 3</div>
                            <h3 class="tp-name">Biệt thự<br><em>Tình Ái Sora</em></h3>
                            <div class="tp-sub">1.500m² · 20+ concept · Nội thất nhập khẩu</div>
                            <p class="tp-desc">Không gian biệt thự cổ điển được thiết kế tỉ mỉ từng góc nhỏ, lấy cảm
                                hứng từ kiến trúc châu Âu thế kỷ 19. Từ phòng ngủ vintage, vườn hoa trắng, thư viện cổ
                                điển đến salon sang trọng — mỗi không gian là một concept hoàn chỉnh mang lại cảm giác
                                chân thực và đầy cảm xúc.</p>
                            <ul class="tp-features">
                                <li>
                                    <div class="tp-feat-dot"></div>Phòng ngủ Tây phương cổ điển với nội thất vintage
                                    nhập khẩu
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Vườn hoa trắng rộng 200m² — concept tươi sáng, lãng
                                    mạn
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Thư viện cổ điển & salon phong cách Pháp sang trọng
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Phòng bếp và phòng ăn phong cách Hàn Quốc tối giản
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Cầu thang vòng cung — điểm nhấn kiến trúc độc đáo
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Hệ thống đèn studio chuyên nghiệp toàn bộ không gian
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Wardrobe 200+ trang phục cưới độc quyền của Sora
                                </li>
                            </ul>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                <button class="btn btn-dark" onclick="go('booking')">Đặt lịch tham quan →</button>
                                <button class="btn btn-outline" onclick="go('booking')">Tư vấn miễn phí</button>
                            </div>
                        </div>
                        <div class="tp-imgs" style="border-radius:16px;overflow:hidden;">
                            <div class="tp-img tp-img-main">
                                <div class="ph gk1" style="height:100%;min-height:260px;">
                                    <div class="ph-icon-wrap"><svg width="48" height="48" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="tp-img">
                                <div class="ph gk5" style="height:100%;min-height:180px;">
                                    <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="tp-img">
                                <div class="ph gk7" style="height:100%;min-height:180px;">
                                    <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Concept grid đầy đủ -->
            <div style="padding:64px 0 80px;background:var(--b1);margin-top:64px;">
                <div class="wrap">
                    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;"
                        class="r">
                        <div>
                            <div class="eyebrow">20+ Concept</div>
                            <h2 class="h2">Concept tại <span class="em-text">Biệt thự Tình Ái</span></h2>
                        </div>
                        <button class="btn btn-outline" onclick="go('booking')">Tư vấn chọn concept →</button>
                    </div>

                    <!-- Concept grid 4 cột -->
                    <div class="cs-grid r" style="gap:14px;">
                        <!-- Row 1 -->
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk1" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Phòng ngủ vintage</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    CLASSIC</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk3" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Vườn hoa trắng</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    ROMANTIC</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk5" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Thư viện cổ điển</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    EDITORIAL</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk7" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Salon châu Âu</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    LUXURY</div>
                            </div>
                        </div>
                        <!-- Row 2 -->
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk2" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Bếp Hàn Quốc</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    KOREAN</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk4" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Cầu thang vòng cung</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    ICONIC</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk6" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Phòng khách tối giản</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    MINIMAL</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk8" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Phòng tắm vintage</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    RETRO</div>
                            </div>
                        </div>
                    </div>

                    <!-- More concepts teaser -->
                    <div class="r"
                        style="margin-top:32px;background:var(--w);border:1px solid var(--border);border-radius:16px;padding:28px 32px;display:flex;align-items:center;gap:24px;">
                        <div style="flex:1;">
                            <div
                                style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:400;color:var(--text);margin-bottom:4px;">
                                Còn <span style="color:var(--br-d);font-style:italic;">12+ concept</span> nữa đang chờ
                                bạn</div>
                            <div style="font-size:13px;color:var(--text2);font-weight:300;">Phòng ăn Pháp, góc cà phê
                                sáng, vườn đông tuyết, nhà gỗ ấm áp... Đặt lịch tham quan để khám phá toàn bộ.</div>
                        </div>
                        <button class="btn btn-dark" style="flex-shrink:0;" onclick="go('booking')">Đặt lịch tham quan
                            →</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── TAB 2: Sora Rooftop ── -->
        <div class="tab-panel" id="tab-ct-rooftop" style="background:var(--b0);">

            <!-- Giới thiệu phim trường -->
            <div style="padding:64px 0 0;">
                <div class="wrap">
                    <div class="tp-layout r">
                        <div class="tp-info" style="padding-left:0;">
                            <div class="tp-tag">✦ Phim trường 2 — Tầng 8 & Sân thượng</div>
                            <h3 class="tp-name">Sora<br><em>Rooftop Studio</em></h3>
                            <div class="tp-sub">1.500m² · 20+ concept · View panorama HN</div>
                            <p class="tp-desc">Phim trường sân thượng độc đáo nhất Hà Nội với tầm nhìn panorama 360°
                                toàn thành phố. Ánh sáng tự nhiên ban ngày chuyển dần thành lung linh đèn đêm tạo nên
                                những bức ảnh mang tính điện ảnh cao, không thể tái hiện ở bất kỳ studio nào khác.</p>
                            <ul class="tp-features">
                                <li>
                                    <div class="tp-feat-dot"></div>Sân thượng 500m² view toàn cảnh Hà Nội 360°
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Sky lounge hiện đại — concept trẻ trung, sang trọng
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Hồ bơi tràn viền phản chiếu ánh sáng nghệ thuật
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Vườn xanh thượng tầng phong cách Nhật Bản
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Shooting ban đêm độc quyền — đèn thành phố lung linh
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Hệ thống đèn LED chuyên nghiệp điều chỉnh màu sắc
                                </li>
                                <li>
                                    <div class="tp-feat-dot"></div>Góc bar ngoài trời phong cách Ibiza & Santorini
                                </li>
                            </ul>
                            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                                <button class="btn btn-dark" onclick="go('booking')">Đặt lịch tham quan →</button>
                                <button class="btn btn-outline" onclick="go('booking')">Tư vấn miễn phí</button>
                            </div>
                        </div>
                        <div class="tp-imgs" style="border-radius:16px;overflow:hidden;">
                            <div class="tp-img tp-img-main">
                                <div class="ph gk4" style="height:100%;min-height:260px;">
                                    <div class="ph-icon-wrap"><svg width="48" height="48" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="tp-img">
                                <div class="ph gk2" style="height:100%;min-height:180px;">
                                    <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                            <div class="tp-img">
                                <div class="ph gk6" style="height:100%;min-height:180px;">
                                    <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24"
                                            fill="none" stroke="#7a5a34" stroke-width="1">
                                            <rect x="3" y="3" width="18" height="18" rx="1" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" /></svg></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Concept grid Rooftop -->
            <div style="padding:64px 0 80px;background:var(--b1);margin-top:64px;">
                <div class="wrap">
                    <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;"
                        class="r">
                        <div>
                            <div class="eyebrow">20+ Concept</div>
                            <h2 class="h2">Concept tại <span class="em-text">Sora Rooftop</span></h2>
                        </div>
                        <button class="btn btn-outline" onclick="go('booking')">Tư vấn chọn concept →</button>
                    </div>

                    <div class="cs-grid r" style="gap:14px;">
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk4" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Hoàng hôn thành phố</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    GOLDEN HOUR</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk2" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Sky lounge ban ngày</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    MODERN</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk6" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Đèn thành phố đêm</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    NIGHT EDIT</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk8" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Vườn Nhật thượng tầng</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    JAPANESE</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk3" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Hồ bơi phản chiếu</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    REFLECTION</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk5" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Panorama bình minh</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    SUNRISE</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk7" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">Bar ngoài trời Ibiza</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    RESORT</div>
                            </div>
                        </div>
                        <div class="cs-item" style="border-radius:14px;">
                            <div class="ph gk1" style="aspect-ratio:3/4;">
                                <div class="ph-icon-wrap"><svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                        stroke="#7a5a34" stroke-width="1">
                                        <rect x="3" y="3" width="18" height="18" rx="1" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <polyline points="21 15 16 10 5 21" /></svg></div>
                            </div>
                            <div class="cs-label"
                                style="opacity:1;background:linear-gradient(to top,rgba(44,33,24,.7) 0%,transparent 70%);">
                                <div class="cs-lbl-txt">City lights editorial</div>
                                <div
                                    style="font-size:10px;color:rgba(255,255,255,.5);margin-top:2px;letter-spacing:1px;">
                                    CINEMATIC</div>
                            </div>
                        </div>
                    </div>

                    <div class="r"
                        style="margin-top:32px;background:var(--w);border:1px solid var(--border);border-radius:16px;padding:28px 32px;display:flex;align-items:center;gap:24px;">
                        <div style="flex:1;">
                            <div
                                style="font-family:'Cormorant Garamond',serif;font-size:20px;font-weight:400;color:var(--text);margin-bottom:4px;">
                                Còn <span style="color:var(--br-d);font-style:italic;">12+ concept</span> rooftop độc
                                quyền</div>
                            <div style="font-size:13px;color:var(--text2);font-weight:300;">Sân thượng mưa nhân tạo,
                                corner cây xanh, mini pool glass floor... Đặt lịch tham quan để trải nghiệm thực tế.
                            </div>
                        </div>
                        <button class="btn btn-dark" style="flex-shrink:0;" onclick="go('booking')">Đặt lịch tham quan
                            →</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA cuối trang -->
        <div class="cta-band">
            <div class="wrap" style="position:relative;z-index:1;">
                <div class="eyebrow" style="justify-content:center;color:var(--br-l);">Bắt đầu ngay hôm nay</div>
                <div class="cta-h">Chọn concept yêu thích — <em>Sora lo phần còn lại</em></div>
                <p class="cta-sub">Tư vấn miễn phí · Tham quan phim trường không mất phí · Phản hồi trong 2 giờ</p>
                <button class="btn btn-white" onclick="go('booking')">Đặt lịch tư vấn & tham quan →</button>
            </div>
        </div>

    </div><!-- /page-concept -->

    <!-- FOOTER -->
    <footer>
        <div class="ft-grid">
            <div>
                <div class="ft-brand-logo">
                    <div class="ft-logo-dot"></div>Sora Bridal
                </div>
                <div class="ft-tagline">Nơi lưu giữ những khoảnh khắc đẹp nhất đời bạn.</div>
                <div class="ft-ci"><span class="ft-ci-l">Địa chỉ</span><span class="ft-ci-v">18 Trần Phú, Hoàn Kiếm, Hà
                        Nội</span></div>
                <div class="ft-ci"><span class="ft-ci-l">Hotline</span><span class="ft-ci-v">0901 234 567</span></div>
                <div class="ft-ci"><span class="ft-ci-l">Email</span><span class="ft-ci-v"><a
                            href="/cdn-cgi/l/email-protection" class="__cf_email__"
                            data-cfemail="1d75787171725d6e726f7c7f6f74797c71336b73">[email&#160;protected]</a></span>
                </div>
                <div class="ft-ci"><span class="ft-ci-l">Giờ mở</span><span class="ft-ci-v">8:00 – 20:00 · Thứ 2 –
                        CN</span></div>
            </div>
            <div>
                <div class="ft-col-h">Dịch vụ</div>
                <ul class="ft-links">
                    <li><a onclick="go('services')">Gói Studio</a></li>
                    <li><a onclick="go('services')">Gói Premium</a></li>
                    <li><a onclick="go('services')">Gói Luxury</a></li>
                    <li><a onclick="go('services')">Video highlight</a></li>
                    <li><a onclick="go('services')">Trang phục cưới</a></li>
                </ul>
            </div>
            <div>
                <div class="ft-col-h">Studio</div>
                <ul class="ft-links">
                    <li><a onclick="go('studio')">Về chúng tôi</a></li>
                    <li><a onclick="go('portfolio')">Portfolio</a></li>
                    <li><a onclick="go('blog')">Blog cưới</a></li>
                    <li><a href="#">Câu hỏi thường gặp</a></li>
                    <li><a href="#">Chính sách hoàn tiền</a></li>
                </ul>
            </div>
            <div>
                <div class="ft-col-h">Liên hệ</div>
                <ul class="ft-links">
                    <li><a onclick="go('booking')">Đặt lịch tư vấn</a></li>
                    <li><a href="#">Facebook</a></li>
                    <li><a href="#">Instagram</a></li>
                    <li><a href="#">TikTok</a></li>
                    <li><a href="#">Zalo OA</a></li>
                </ul>
            </div>
        </div>
        <div class="ft-bottom">
            <div class="ft-copy">© 2025 Sora Bridal Studio · Hà Nội · Thiết kế với ♥</div>
            <div class="ft-social">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">TikTok</a>
                <a href="#">Zalo</a>
            </div>
        </div>
    </footer>

    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    @include('user.layouts.partials.scripts')
</body>

</html>
