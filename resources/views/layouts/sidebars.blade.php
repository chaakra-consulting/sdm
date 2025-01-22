<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        @if (Auth::check() && Auth::user()->role->slug == 'admin')
        <a href="/admin/dashboard" class="header-logo">
            <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}" alt="logo" class="desktop-logo">
        </a>
        @elseif (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
        <a href="/admin_sdm/dashboard" class="header-logo">
            <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}" alt="logo" class="desktop-logo">
        </a>
        @else
        <a href="/karyawan/dashboard" class="header-logo">
            <img src="{{ asset('Tema/dist/assets/images/media/logo.png') }}" alt="logo" class="desktop-logo">
        </a>
        @endif
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            @if (Auth::check() && Auth::user()->role->slug == 'admin')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="/admin/dashboard"
                            class="side-menu__item {{ request()->routeIs('/admin/dashboard') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <!-- End::slide -->

                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">General</span></li>
                    <!-- End::slide__category -->
                    <li class="slide">
                        <a href="/admin/data_karyawan"
                            class="side-menu__item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Data Karyawan</span>
                        </a>
                    </li>
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span class="side-menu__label">Management User</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0);">Management User</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.users') }}"
                                    class="side-menu__item {{ request()->routeIs('admin.users') ? 'active' : '' }}">List
                                    User</a>
                            </li>
                            {{-- <li class="slide">
                                <a href="{{ route('admin.sub_jabatan') }}"
                                    class="side-menu__item {{ request()->routeIs('admin.sub_Jabatan') ? 'active' : '' }}">Sub Jabatan</a>
                            </li> --}}
                            <li class="slide">
                                <a href="{{ route('admin.roles') }}"
                                    class="side-menu__item {{ request()->routeIs('admin.roles') ? 'active' : '' }}">Role
                                    User</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->
                </ul>
            @endif

            @if (Auth::check() && Auth::user()->role->slug == 'karyawan')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="/karyawan/dashboard"
                            class="side-menu__item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <!-- End::slide -->

                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Informasi Diri</span></li>
                    <!-- End::slide__category -->
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span class="side-menu__label">Management Data Diri</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('datadiri') }}"
                                    class="side-menu__item {{ request()->routeIs('datadiri') ? 'active' : '' }}">Data
                                    Diri</a>
                            </li>
                            <li class="slide">
                                <a href="/karyawan/pengalaman_kerja"
                                    class="side-menu__item {{ request()->routeIs('karyawan/pengalaman_kerja') ? 'active' : '' }}">Pengalaman
                                    Kerja</a>
                            </li>
                            <li class="slide">
                                <a href="/karyawan/pelatihan"
                                    class="side-menu__item {{ request()->routeIs('karyawan/pelatihan') ? 'active' : '' }}">Pelatihan</a>
                            </li>
                            <li class="slide">
                                <a href="/karyawan/social_media"
                                    class="side-menu__item {{ request()->routeIs('karyawan/social_media') ? 'active' : '' }}">Sosial
                                    Media</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->
                </ul>
            @endif
            @if (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="/admin_sdm/dashboard"
                            class="side-menu__item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <!-- End::slide -->

                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Informasi Karyawan</span></li>
                    <!-- End::slide__category -->
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span class="side-menu__label">Management Karyawan</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/admin_sdm/kepegawaian"
                                    class="side-menu__item {{ request()->routeIs('/admin_sdm/kepegawaian') ? 'active' : '' }}">Data
                                    Kepegawaian</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span class="side-menu__label">Management Data Diri</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/admin_sdm/datadiri"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/datadiri') ? 'active' : '' }}">Data
                                    Diri</a>
                            </li>
                            <li class="slide">
                                <a href="/admin_sdm/pengalaman_kerja"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/pengalaman_kerja') ? 'active' : '' }}">Pengalaman
                                    Kerja</a>
                            </li>
                            <li class="slide">
                                <a href="/admin_sdm/pelatihan"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/pelatihan') ? 'active' : '' }}">Pelatihan</a>
                            </li>
                            <li class="slide">
                                <a href="/admin_sdm/social_media"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/social_media') ? 'active' : '' }}">Sosial
                                    Media</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

                    {{-- <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span class="side-menu__label">Management Gaji</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/admin_sdm/gaji"
                                    class="side-menu__item {{ request()->routeIs('/admin_sdm/gaji') ? 'active' : '' }}">Data
                                    Gaji Karyawan</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide --> --}}

                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">General</span></li>
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                            </svg>
                            <span class="side-menu__label">Management User</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0);">Management User</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin.users') }}"
                                    class="side-menu__item {{ request()->routeIs('admin.users') ? 'active' : '' }}">List
                                    User</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

                    <li class="slide__category"><span class="category-name">Data Master</span></li>
                    <li class="slide">
                        <a href="{{ route('admin_sdm.sub_jabatan') }}" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Master Jabatan</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="{{ route('admin_sdm.status_pekerjaan') }}" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Master Status Pekerjaan</span>
                        </a>
                    </li>
                    <li class="slide">
                        <a href="{{ route('admin_sdm.divisi') }}" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Master Divisi</span>
                        </a>
                    </li>
                    {{-- <li class="slide">
                        <a href="{{ route('admin_sdm.absensi.index') }}" class="side-menu__item">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Master Absensi</span>
                        </a>
                    </li> --}}
                </ul>
            @endif
            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="{{ route('manajer.dahsboard') }}"
                            class="side-menu__item {{ request()->routeIs('manajer.dashboard') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <!-- End::slide -->

                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Manajer</span></li>
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Management Project</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0);">Management Projcet</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('manajer.perusahaan') }}"
                                    class="side-menu__item {{ request()->routeIs('admin.users') ? 'active' : '' }}">List
                                    Perusahaan</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('manajer.project') }}"
                                    class="side-menu__item {{ request()->routeIs('admin.users') ? 'active' : '' }}">List
                                    Project</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->
                    <!-- End::slide__category -->

                </ul>
            @endif

            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->
                    <!-- Start::slide -->
                    <li class="slide">
                        <a href="/admin_sdm/dashboard"
                            class="side-menu__item {{ request()->routeIs('home') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                        </a>
                    </li>
                    <!-- End::slide -->
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Manajemen Perusahaan</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ Route('manajer.daftar-perusahaan') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.daftar-perusahaan') ? 'active' : '' }}">Data
                                    Perusahaan</a>
                            </li>
                        </ul>
                    </li>
                    <!-- End::slide -->
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none"></path>
                                <path
                                    d="M5 9h14V5H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5S7.83 8.5 7 8.5 5.5 7.83 5.5 7 6.17 5.5 7 5.5zM5 19h14v-4H5v4zm2-3.5c.83 0 1.5.67 1.5 1.5s-.67 1.5-1.5 1.5-1.5-.67-1.5-1.5.67-1.5 1.5-1.5z"
                                    opacity=".3"></path>
                                <path
                                    d="M20 13H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1v-6c0-.55-.45-1-1-1zm-1 6H5v-4h14v4zm-12-.5c.83 0 1.5-.67 1.5-1.5s-.67-1.5-1.5-1.5-1.5.67-1.5 1.5.67 1.5 1.5 1.5zM20 3H4c-.55 0-1 .45-1 1v6c0 .55.45 1 1 1h16c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1zm-1 6H5V5h14v4zM7 8.5c.83 0 1.5-.67 1.5-1.5S7.83 5.5 7 5.5 5.5 6.17 5.5 7 6.17 8.5 7 8.5z">
                                </path>
                            </svg>
                            <span class="side-menu__label">Manajemen Project</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ Route('manajer.daftar-project') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.daftar-project') ? 'active' : '' }}">
                                    Data Project
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- End::slide -->
                </ul>
            @endif
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg>
            </div>
        </nav> <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
<!-- End::app-sidebar -->
