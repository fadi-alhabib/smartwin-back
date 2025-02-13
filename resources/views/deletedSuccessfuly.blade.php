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
	
    <meta http-equiv="refresh" content="3;url=http://127.0.0.1:8000/deleteAccount" />

    
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
                                    
                                    <h2 class="d-flex justify-content-center text-danger"><b> تم حذف الحساب بنجاح </b></h2>
                                    

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