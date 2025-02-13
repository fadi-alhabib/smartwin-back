<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="keywords" content="admin, dashboard">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <meta name="description" content="Dompet : Payment Admin Template"> -->
	<!-- <meta property="og:title" content="Dompet : Payment Admin Template"> -->
	<!-- <meta property="og:description" content="Dompet : Payment Admin Template"> -->
	<!-- <meta property="og:image" content="https://dompet.dexignlab.com/xhtml/social-image.png"> -->
	<meta name="format-detection" content="telephone=no">
	

    
	<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">

	<!-- PAGE TITLE HERE -->
	<title>Delete Account</title>
	
	<!-- FAVICONS ICON -->
	<!-- <link rel="shortcut icon" type="image/png" href="images/favicon.png"> -->
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
									<div class=" d-flex justify-content-center">

                                        <img src="images/logo-full.png"  alt="" width="50%">

									</div>
                                    <h4 class="text-center mb-4"> هل حقا تريد حذف حسابك ؟ </h4>
                                    <form action="{{ route('deleteAccount') }}" method="POST">

                                        @csrf

                                        <div class="mb-3">
                                            <label class="mb-1" style="font-size: 20px"><strong> البريد الإلكتروني </strong></label>
                                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        </div>
                                        <div class="mb-3">
                                            <label class="mb-1" style="font-size: 20px"><strong>كلمة المرور</strong></label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        </div>

                                        <!-- <div class="row mb-3">
                                            <div class="col-md-6 offset-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="remember">
                                                        {{ __('Remember Me') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="row d-flex justify-content-between mt-4 mb-2">
                                            <!-- <div class="mb-3">
                                               <div class="form-check custom-checkbox ms-1">
													<input type="checkbox" class="form-check-input" id="basic_checkbox_1">
													<label class="form-check-label" for="basic_checkbox_1">Remember my preference</label>
												</div>
                                            </div> -->
                                            <!-- <div class="">
                                                <div class="">
                                                    
                                                    <label class="form-check-label mx-2" for="remember">
                                                        {{ __('تذكرني') }}
                                                    </label>

                                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                                </div>
                                            </div> -->
                                            <!-- <div class="mb-3">
                                            @if (Route::has('password.request'))
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    {{ __('هل نسيت كلمة السر ؟') }}
                                                </a>
                                            @endif
                                            </div> -->
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-danger btn-block"> حذف الحساب </button>
                                        </div>

                                        <div class="text-center mt-3">
                                            <a href="{{ route('google') }}" class="login-with-google-btn btn btn-block border border-danger"> سجلت عن طريق جوجل </a>
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