<!DOCTYPE html>
<html lang="en" dir="ltr" data-nav-layout="vertical" data-vertical-style="overlay" data-theme-mode="light"
    data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>
    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/chaakra-2.png') }}" type="image/x-icon">

    <!-- Main Theme Js -->
    <script src="{{ asset('Tema/dist/assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('Tema/dist/assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Style Css -->
    <link href="{{ asset('Tema/dist/assets/css/styles.min.css') }}" rel="stylesheet">

    <!-- Icons Css -->
    <link href="{{ asset('Tema/dist/assets/css/icons.min.css') }}" rel="stylesheet">
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
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="mb-5 d-flex">
                                        <a href="index.html" class="header-logo">
                                            <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}"
                                                class="desktop-logo ht-40" alt="logo">
                                            <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}"
                                                class="desktop-white ht-40" alt="logo">
                                        </a>
                                    </div>
                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h3>Sdm Chaakra</h3>
                                            <h6 class="fw-medium mb-4 fs-17">Silahkan login untuk melanjutkan.</h6>
                                            <!-- Laravel Login Form -->
                                            <div id="login-form-wrapper">
                                                <!-- Form Login Biasa -->
                                                <form id="login-form" action="{{ route('login-proses') }}" method="POST">
                                                    @csrf

                                                    <!-- Email Input -->
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Masukkan email" type="email" name="email" required>
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Password Input -->
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Password</label>
                                                        <input class="form-control @error('password') is-invalid @enderror"
                                                            placeholder="Masukkan password" type="password" name="password" required>
                                                        @error('password')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <button type="submit" class="btn btn-primary btn-block w-100">Sign In</button>
                                                </form>

                                                <!-- Button Login SSO -->
                                                <button type="button" id="btn-login-sso" class="btn btn-primary-transparent btn-block w-100 mt-3">
                                                    Sign In SSO
                                                </button>
                                            </div>

                                            <!-- Footer Links -->
                                            <div class="main-signin-footer mt-5">

                                                <p>Belum memiliki akun ? <a
                                                        href="{{ route('register') }}">Registrasi</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End -->
                </div>
            </div><!-- End -->
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('Tema/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Show Password JS -->
    <script src="{{ asset('Tema/dist/assets/js/show-password.js') }}"></script>

    <script>
        const formWrapper = document.getElementById('login-form-wrapper');
    
        // Event listener untuk tombol Login SSO
        document.getElementById('btn-login-sso').addEventListener('click', function () {
            formWrapper.innerHTML = `
                <form id="sso-form" action="{{ route('sso.login.form') }}" method="POST">
                    @csrf
    
                    <!-- SSO Description -->
                    <p class="text-center mb-4">Login menggunakan akun SSO Anda.</p>
    
                    <!-- Email Input -->
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email" type="email" name="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Password Input -->
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password" type="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block w-100">Sign In SSO</button>
    
                    <!-- Button Kembali ke Login Biasa -->
                    <button type="button" id="btn-back-to-login" class="btn btn-primary-transparent btn-block w-100 mt-3">
                        Login Primary
                    </button>
                </form>
            `;
    
            // Event listener untuk tombol Kembali ke Login Biasa
            document.getElementById('btn-back-to-login').addEventListener('click', function () {
                renderLoginForm();
            });
        });
    
        // Fungsi untuk merender ulang form login biasa
        function renderLoginForm() {
            formWrapper.innerHTML = `
                <form id="login-form" action="{{ route('login-proses') }}" method="POST">
                    @csrf
    
                    <!-- Email Input -->
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email" type="email" name="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Password Input -->
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password" type="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-block w-100">Sign In</button>
    
                    <!-- Button Login SSO -->
                    <button type="button" id="btn-login-sso" class="btn btn-primary-transparent btn-block w-100 mt-3">
                        Sign In SSO
                    </button>
                </form>
            `;
    
            // Pasang kembali event listener untuk tombol Login SSO
            document.getElementById('btn-login-sso').addEventListener('click', function () {
                formWrapper.innerHTML = `
                    <form id="sso-form" action="{{ route('sso.login.form') }}" method="POST">
                        @csrf
    
                        <!-- SSO Description -->
                        <p class="text-center mb-4">Login menggunakan akun SSO Anda.</p>
    
                        <!-- Email Input -->
                    <div class="form-group mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukkan email" type="email" name="email" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                    <!-- Password Input -->
                    <div class="form-group mb-3">
                        <label class="form-label">Password</label>
                        <input class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password" type="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
    
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-block w-100">Sign In SSO</button>
    
                        <!-- Button Kembali ke Login Biasa -->
                        <button type="button" id="btn-back-to-login" class="btn btn-primary-transparent btn-block w-100 mt-3">
                            Login Primary
                        </button>
                    </form>
                `;
    
                // Pasang kembali event listener untuk tombol Kembali ke Login Biasa
                document.getElementById('btn-back-to-login').addEventListener('click', function () {
                    renderLoginForm();
                });
            });
        }
    </script>
    
</body>

</html>