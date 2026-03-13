<meta charset="utf-8" />
<meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<meta name="robots" content="noindex, nofollow" />
<title>@yield('title', 'Admin | Wedding Studio')</title>

<meta name="description" content="" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="../../assets/img/favicon/favicon.ico" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
  href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
  rel="stylesheet" />

<link rel="stylesheet" href="../../assets/vendor/fonts/iconify-icons.css" />

<script src="../../assets/vendor/libs/@algolia/autocomplete-js.js"></script>

<!-- Core CSS -->
<link rel="stylesheet" href="../../assets/vendor/libs/node-waves/node-waves.css" />
<link rel="stylesheet" href="../../assets/vendor/libs/pickr/pickr-themes.css" />
<link rel="stylesheet" href="../../assets/vendor/css/core.css" />
<link rel="stylesheet" href="../../assets/css/demo.css" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
<link rel="stylesheet" href="../../assets/vendor/libs/swiper/swiper.css" />
<link rel="stylesheet" href="../../assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
<link rel="stylesheet" href="../../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
<link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />

<!-- Page CSS -->
<link rel="stylesheet" href="../../assets/vendor/css/pages/cards-advance.css" />

<!-- Helpers -->
<script src="../../assets/vendor/js/helpers.js"></script>
<script src="../../assets/vendor/js/template-customizer.js"></script>
<script src="../../assets/js/config.js"></script>

<!-- Select2 (dùng chung cho các select trong admin) -->
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

<!-- Flatpickr: date picker hiển thị dd/mm/yyyy (giống Single Picker trong demo) -->
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
<style>
/* Calendar Flatpickr: khi nằm trong modal thì appendTo modal, cần z-index cao hơn nội dung modal */
.modal .flatpickr-calendar,
.modal .flatpickr-calendar.open,
.flatpickr-calendar.open {
  z-index: 9999 !important;
}
</style>

@stack('styles')
