<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Wedding Studio - Trọn gói chụp ảnh cưới</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css (dùng với WOW.js) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #c9a86c;
            --primary-dark: #a68b52;
            --dark: #2d2a26;
            --light: #faf8f5;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; color: var(--dark); overflow-x: hidden; }
        .navbar { background: rgba(45, 42, 38, 0.95) !important; backdrop-filter: blur(10px); }
        .navbar-brand, .nav-link { color: #fff !important; }
        .nav-link:hover { color: var(--primary) !important; }
        .btn-gold { background: var(--primary); color: #fff; border: none; }
        .btn-gold:hover { background: var(--primary-dark); color: #fff; }

        /* Hero */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(45, 42, 38, 0.85) 0%, rgba(45, 42, 38, 0.6) 100%),
                url('https://images.unsplash.com/photo-1519741497674-611481863552?w=1920') center/cover no-repeat;
            color: #fff;
            display: flex;
            align-items: center;
            text-align: center;
        }
        .hero h1 { font-size: clamp(2rem, 5vw, 3.5rem); font-weight: 600; letter-spacing: 0.02em; }
        .hero .lead { font-size: 1.15rem; opacity: 0.95; }
        .hero .btn { padding: 0.75rem 2rem; font-weight: 500; }

        /* Section chung */
        section { padding: 4rem 0; }
        .section-title { font-size: 2rem; font-weight: 600; margin-bottom: 1rem; color: var(--dark); }
        .section-subtitle { color: #6c757d; max-width: 600px; margin: 0 auto 3rem; }

        /* Giới thiệu */
        .about { background: var(--light); }
        .about-card { border: none; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
        .about-card img { height: 220px; object-fit: cover; }

        /* Gói dịch vụ */
        .pricing-card {
            border: 1px solid #eee;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .pricing-card:hover { transform: translateY(-8px); box-shadow: 0 12px 40px rgba(0,0,0,0.12); }
        .pricing-card.featured { border-color: var(--primary); border-width: 2px; position: relative; }
        .pricing-card.featured .card-header { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; }
        .pricing-card .card-header { background: var(--dark); color: #fff; padding: 1.25rem; font-weight: 600; font-size: 1.1rem; }
        .pricing-card .card-body { padding: 1.75rem; }
        .price { font-size: 2rem; font-weight: 700; color: var(--primary); }
        .pricing-card ul { list-style: none; padding: 0; margin: 0; }
        .pricing-card ul li { padding: 0.4rem 0; padding-left: 1.5rem; position: relative; }
        .pricing-card ul li::before { content: "✓"; position: absolute; left: 0; color: var(--primary); font-weight: bold; }

        /* CTA / Footer */
        .cta { background: linear-gradient(135deg, var(--dark) 0%, #3d3832 100%); color: #fff; }
        .cta .btn-gold { padding: 0.75rem 2rem; }
        footer { background: #1a1816; color: rgba(255,255,255,0.7); padding: 2rem 0; font-size: 0.9rem; }
        footer a { color: var(--primary); text-decoration: none; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">{{ config('app.name', 'Wedding Studio') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto gap-2">
                    <li class="nav-item"><a class="nav-link" href="#hero">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#gioi-thieu">Giới thiệu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#goi-dich-vu">Gói dịch vụ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#lien-he">Liên hệ</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.index') }}">Quản trị</a></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light btn-sm">Đăng xuất</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="btn btn-gold btn-sm" href="{{ route('login') }}">Đăng nhập</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section id="hero" class="hero">
        <div class="container">
            <div class="wow animate__animated animate__fadeInUp" data-wow-duration="1s">
                <h1 class="mb-3">Wedding Studio</h1>
                <p class="lead mb-4">Ghi lại khoảnh khắc đẹp nhất ngày cưới của bạn với ekip chuyên nghiệp và phong cách hiện đại.</p>
                <a href="#goi-dich-vu" class="btn btn-gold btn-lg">Xem gói dịch vụ</a>
            </div>
        </div>
    </section>

    <!-- Giới thiệu -->
    <section id="gioi-thieu" class="about">
        <div class="container">
            <h2 class="section-title text-center wow animate__animated animate__fadeInUp" data-wow-duration="0.8s">Về chúng tôi</h2>
            <p class="section-subtitle text-center wow animate__animated animate__fadeInUp" data-wow-delay="0.2s" data-wow-duration="0.8s">
                Wedding Studio mang đến trải nghiệm chụp ảnh cưới trọn gói với phông nền đa dạng, trang phục cao cấp và ekip giàu kinh nghiệm.
            </p>
            <div class="row g-4">
                <div class="col-md-4 wow animate__animated animate__fadeInUp" data-wow-delay="0.1s" data-wow-duration="0.8s">
                    <div class="card about-card h-100">
                        <img src="https://images.unsplash.com/photo-1606216794074-735e91aa2c92?w=600" class="card-img-top" alt="Phim trường">
                        <div class="card-body">
                            <h5 class="card-title">Phim trường đa phong cách</h5>
                            <p class="card-text text-muted">Nhiều bối cảnh trong nhà và ngoài trời, phù hợp mọi gu thẩm mỹ.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s" data-wow-duration="0.8s">
                    <div class="card about-card h-100">
                        <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=600" class="card-img-top" alt="Trang phục">
                        <div class="card-body">
                            <h5 class="card-title">Trang phục cao cấp</h5>
                            <p class="card-text text-muted">Váy cưới, vest và phụ kiện đa dạng, được chăm sóc thường xuyên.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 wow animate__animated animate__fadeInUp" data-wow-delay="0.3s" data-wow-duration="0.8s">
                    <div class="card about-card h-100">
                        <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=600" class="card-img-top" alt="Ekip">
                        <div class="card-body">
                            <h5 class="card-title">Ekip chuyên nghiệp</h5>
                            <p class="card-text text-muted">Photographer, make-up và stylist nhiều năm kinh nghiệm phục vụ tận tâm.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gói dịch vụ -->
    <section id="goi-dich-vu" class="py-5">
        <div class="container">
            <h2 class="section-title text-center wow animate__animated animate__fadeInUp" data-wow-duration="0.8s">Các gói dịch vụ</h2>
            <p class="section-subtitle text-center wow animate__animated animate__fadeInUp" data-wow-delay="0.2s" data-wow-duration="0.8s">
                Chọn gói phù hợp với nhu cầu và ngân sách của bạn. Liên hệ để được tư vấn chi tiết.
            </p>
            <div class="row g-4 justify-content-center">
                <!-- Gói Cơ bản -->
                <div class="col-lg-4 col-md-6 wow animate__animated animate__fadeInUp" data-wow-delay="0.1s" data-wow-duration="0.8s">
                    <div class="card pricing-card h-100">
                        <div class="card-header">Gói Cơ bản</div>
                        <div class="card-body">
                            <div class="price mb-3">8.000.000đ</div>
                            <ul>
                                <li>Chụp trong studio (2 bối cảnh)</li>
                                <li>1 bộ trang phục (váy/vest)</li>
                                <li>Make-up & làm tóc</li>
                                <li>50 ảnh chỉnh sửa, in album 20x30</li>
                                <li>File gốc (ảnh đã chỉnh)</li>
                            </ul>
                            <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 mt-3">Đặt lịch</a>
                        </div>
                    </div>
                </div>
                <!-- Gói Tiêu chuẩn (featured) -->
                <div class="col-lg-4 col-md-6 wow animate__animated animate__fadeInUp" data-wow-delay="0.2s" data-wow-duration="0.8s">
                    <div class="card pricing-card featured h-100">
                        <div class="card-header">Gói Tiêu chuẩn</div>
                        <div class="card-body">
                            <div class="price mb-3">15.000.000đ</div>
                            <ul>
                                <li>Chụp studio + 1 địa điểm ngoại cảnh</li>
                                <li>3 bộ trang phục</li>
                                <li>Make-up & làm tóc full</li>
                                <li>100 ảnh chỉnh sửa, album cao cấp</li>
                                <li>Video teaser ngắn</li>
                                <li>File gốc full bộ</li>
                            </ul>
                            <a href="{{ route('login') }}" class="btn btn-gold w-100 mt-3">Đặt lịch</a>
                        </div>
                    </div>
                </div>
                <!-- Gói Cao cấp -->
                <div class="col-lg-4 col-md-6 wow animate__animated animate__fadeInUp" data-wow-delay="0.3s" data-wow-duration="0.8s">
                    <div class="card pricing-card h-100">
                        <div class="card-header">Gói Cao cấp</div>
                        <div class="card-body">
                            <div class="price mb-3">25.000.000đ</div>
                            <ul>
                                <li>Studio + 2 địa điểm ngoại cảnh</li>
                                <li>5 bộ trang phục (không giới hạn thay)</li>
                                <li>Make-up & stylist riêng</li>
                                <li>150 ảnh chỉnh sửa, album premium</li>
                                <li>Video highlight 3–5 phút</li>
                                <li>Ảnh in khung trang trí (1 khung lớn)</li>
                            </ul>
                            <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 mt-3">Đặt lịch</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="lien-he" class="cta">
        <div class="container text-center wow animate__animated animate__fadeInUp" data-wow-duration="0.8s">
            <h2 class="mb-3 text-white">Sẵn sàng đặt lịch chụp?</h2>
            <p class="mb-4 opacity-90">Liên hệ hoặc đăng nhập để đặt lịch và nhận tư vấn miễn phí.</p>
            <a href="{{ route('login') }}" class="btn btn-gold btn-lg">Đăng nhập / Đặt lịch</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Wedding Studio. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- WOW.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new WOW({ offset: 80, mobile: true }).init();
        });
    </script>
</body>
</html>
