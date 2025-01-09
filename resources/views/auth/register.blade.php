<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Valex - Bootstrap 5 Premium Admin & Dashboard Template </title>
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="keywords"
        content="admin,admin dashboard,admin panel,admin template,bootstrap,clean,dashboard,flat,jquery,modern,responsive,premium admin templates,responsive admin,ui,ui kit.">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('Tema/dist/assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('Tema/dist/assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('Tema/dist/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('Tema/dist/assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('Tema/dist/assets/css/icons.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container-fluid custom-page">
        <div class="row bg-white">
            <!-- The image half -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent-3">
                <div class="row w-100 mx-auto text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto w-100">
                        <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}"
                            class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                    </div>
                </div>
            </div>
            <!-- The content half -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white py-4">
                <div class="login d-flex align-items-center py-2">
                    <!-- Demo content-->
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="mb-5 d-flex">
                                        <a href="index.html" class="header-logo">
                                            <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}"
                                                class="desktop-logo ht-80" alt="logo">
                                        </a>
                                    </div>
                                    <div class="card-sigin">


                                        @if(session('success'))
                                            <div class="alert alert-success">
                                                {{ session('success') }}
                                            </div>
                                        @endif
                                        @if($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="main-signup-header">
                                            <h3>Sdm Chaakra</h3>
                                            <h6 class="fw-medium mb-4 fs-17">Silhakan registrasi terlebih dahulu sebelum
                                                login.</h6>
                                            <!-- Laravel Form -->
                                            <form action="{{ route('register_process') }}" method="POST">
                                                @csrf
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Nama</label>


                                                    <input class="form-control @error('name') is-invalid @enderror"
                                                        name="name" placeholder="Enter your name" type="text"
                                                        value="{{ old('name') }}" required>
                                                    @error('name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Email</label>


                                                    <input class="form-control @error('email') is-invalid @enderror"
                                                        name="email" placeholder="Enter your email" type="email"
                                                        value="" required>
                                                    @error('email')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Password</label>

                                                    <input class="form-control @error('password') is-invalid @enderror"
                                                        name="password" placeholder="Enter your password"
                                                        type="password" value="" required>
                                                    @error('password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label class="form-label">Konfirmasi Password</label>
                                                    <input class="form-control" name="password_confirmation"
                                                        placeholder="Confirm your password" type="password" value=""
                                                        required>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-block w-100">Buat Akun
                                                </button>
                                            </form>
                                            <div class="main-signin-footer mt-5">
                                                <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="{{ asset('Tema/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Show Password JS -->
    <script src="{{ asset('Tema/dist/assets/js/show-password.js') }}"></script>
    <script>
        // Menampilkan pesan sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
            });
        @endif

        // Menampilkan pesan error validasi
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                html: `
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            `,
            });
        @endif
    </script>
</body>

</html>