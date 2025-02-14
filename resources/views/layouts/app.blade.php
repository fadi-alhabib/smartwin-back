<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="admin, dashboard">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">

	<link rel="icon" href="images/favicon.ico" type="image/x-icon">

	<!-- PAGE TITLE HERE -->
	<title>Dashboard</title>

	<!-- FAVICONS ICON -->
	<!-- <link rel="shortcut icon" type="image/png" href="images/favicon.png"> -->

	<!-- <link href="vendor/jquery-nice-select/css/nice-select.css" rel="stylesheet"> -->
	<!-- <link rel="stylesheet" href="vendor/nouislider/nouislider.min.css"> -->
	<!-- Style css -->
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
	<!-- <link href="{{ asset('css/style_2.css') }}" rel="stylesheet"> -->
	<link href="{{ asset('css/nouislider/nouislider.min.css') }}" rel="stylesheet">
	<link href="{{ asset('js/jquery-nice-select/css/nice-select.css') }}" rel="stylesheet">
</head>

<body>

	<!--*******************
        Preloader start
    ********************-->
	<!-- <div id="preloader">
        <div class="waviy" dir="ltr">
		   <span style="--i:1">L</span>
		   <span style="--i:2">o</span>
		   <span style="--i:3">a</span>
		   <span style="--i:4">d</span>
		   <span style="--i:5">i</span>
		   <span style="--i:6">n</span>
		   <span style="--i:7">g</span>
		   <span style="--i:8">.</span>
		   <span style="--i:9">.</span>
		   <span style="--i:10">.</span>
		</div>
    </div> -->
	<!--*******************
        Preloader end
    ********************-->

	<div id="app">
		<!--**********************************
            Nav header start
        ***********************************-->
		<div class="nav-header d-flex justify-content-center">
			<a href="/home" class="brand-logo d-flex justify-content-center">
				<img src="{{ asset('images/logo-full.png') }}" alt="" width="55%">
			</a>
		</div>
		<!--**********************************
            Nav header end
        ***********************************-->

		<!--**********************************
            Header start
        ***********************************-->
		<div class="header">
			<div class="header-content">
				<nav class="navbar navbar-expand">
					<div class="collapse navbar-collapse justify-content-between">
						<ul class="navbar-nav header-right">
						</ul>
						<div class="header-left">
							<div class="dashboard_bar">
								@yield('header')
							</div>
						</div>
					</div>
				</nav>
			</div>
		</div>
		<!--**********************************
            Header end
        ***********************************-->

		<!--**********************************
            Sidebar start
        ***********************************-->
		<div class="dlabnav">
			<div class="dlabnav-scroll">
				<ul class="metismenu" id="menu">
					<li class="dropdown header-profile">
						<a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
							<div class="header-info ms-3">
								<span class="font-w600">مرحبا, <b>{{ auth('admin')->user()->full_name }}</b></span>
								<small class="text-end font-w400">{{ auth('admin')->user()->email }}</small>
							</div>
							<img src="{{ asset('images/avatar/user.png') }}" width="20" alt="">
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="/changePassword" class="dropdown-item ai-icon">
								<svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary me-2" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
									<circle cx="12" cy="7" r="4"></circle>
								</svg>
								<span class="ms-2">كلمة المرور</span>
							</a>
							<a href="/logout" class="dropdown-item ai-icon">
								<svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger me-2" width="18" height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
									<polyline points="16 17 21 12 16 7"></polyline>
									<line x1="21" y1="12" x2="9" y2="12"></line>
								</svg>
								<span class="ms-2">خروج</span>
							</a>
						</div>
					</li>
					<li>
						<a class="ai-icon" href="{{ route('home') }}" aria-expanded="false">
							<span class="nav-text">الرئيسية</span>
							<i class="flaticon-025-dashboard"></i>
						</a>
					</li>

					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<span class="nav-text">عمليات التحويل</span>
							<i class="flaticon-381-network"></i>
						</a>
						<ul aria-expanded="false">
							<li><a href="{{ route('transferRequest') }}">طلبات التحويل</a></li>
							<li><a href="{{ route('transfer') }}">عمليات تمت</a></li>
							{{-- <li><a href="{{ route('points') }}">النقاط</a></li> --}}
						</ul>
					</li>
					<!-- ********************** Start Stores Section ********************** -->
					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<span class="nav-text">المتجر</span>
							<i class="fa fa-solid fa-store"></i>
						</a>
						<ul aria-expanded="false">
							<li><a href="{{ route('store.index') }}">جميع المتاجر</a></li>
							<li><a href="{{ route('product.index') }}">جميع المنتجات</a></li>
						</ul>
					</li>
					<!-- ********************** End Stores Section ********************** -->
					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<span class="nav-text">الأسئلة</span>
							<i class="flaticon-381-notebook"></i>
						</a>
						<ul aria-expanded="false">
							<li><a href="{{ route('question.index') }}">الأسئلة</a></li>
							<li><a href="{{ route('question.create') }}">إضافة سؤال</a></li>
						</ul>
					</li>
					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<span class="nav-text">المستخدمين</span>
							<i class="flaticon-381-user"></i>
						</a>
						<ul aria-expanded="false">
							<li><a href="{{ route('user.index') }}">المشتركين</a></li>
							<li><a href="{{ route('admin.index') }}">الأدمن</a></li>
							<li><a href="{{ route('admin.create') }}">إضافة أدمن</a></li>
						</ul>
					</li>
					<li>
						<a href="{{ route('privilege') }}" class="ai-icon" aria-expanded="false">
							<span class="nav-text">الصلاحيات</span>
							<i class="flaticon-013-checkmark"></i>
						</a>
					</li>
					<li>
						<a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
							<span class="nav-text">الاعلانات</span>
							<i class="flaticon-381-picture"></i>
						</a>
						<ul aria-expanded="false">
							<li><a href="{{ route('advertisement.index') }}">كل الاعلانات</a></li>
							<li><a href="{{ route('advertisement.create') }}">إضافة إعلان</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<!--**********************************
            Sidebar end
        ***********************************-->

		<main class="py-4">
			@yield('content')
		</main>
	</div>

	<script src="{{ asset('js/global/global.min.js') }}"></script>
	<script src="{{ asset('js/dashboard/dashboard-1.js') }}"></script>
	<script src="{{ asset('js/custom.min.js') }}"></script>
	<script src="{{ asset('js/dlabnav-init.js') }}"></script>
	<script src="{{ asset('js/demo.js') }}"></script>
	<script src="{{ asset('js/chart.js/Chart.bundle.min.js') }}"></script>
	<script src="{{ asset('js/jquery-nice-select/js/jquery.nice-select.min.js') }}"></script>
	<script src="{{ asset('js/apexchart/apexchart.js') }}"></script>
	<script src="{{ asset('js/nouislider/nouislider.min.js') }}"></script>
	<script src="{{ asset('js/wnumb/wNumb.js') }}"></script>
	<script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
