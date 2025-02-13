<!DOCTYPE html>
<html lang="ar" dir="rtl" class="h-100">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="admin, dashboard">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">

	<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

	<!-- PAGE TITLE HERE -->
	<title>تسجيل الدخول</title>
	
	<!-- FAVICONS ICON -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="d-flex justify-content-center">
                                        <img src="{{ asset('images/logo-full.png') }}" alt="Logo" width="50%">
									</div>
                                    <h4 class="text-center mb-4">سجل دخولك الى حسابك</h4>
                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="username" class="mb-1" style="font-size: 20px"><strong>اسم المستخدم</strong></label>
                                            <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="mb-1" style="font-size: 20px"><strong>كلمة المرور</strong></label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary btn-block">تسجيل الدخول</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="terms p-2" style="display: flex; justify-content: center; font-size:12px;">
                                    <a href="{{ route('termsOfUse') }}">
                                        سياسة الخصوصية
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="{{ asset('vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('js/custom.min.js') }}"></script>
</body>
</html>
