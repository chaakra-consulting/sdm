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
                                <a href="{{ route('karyawan.datadiri') }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.datadiri') ? 'active' : '' }}">Data
                                    Diri</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('karyawan.pengalaman_kerja') }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.pengalaman_kerja') ? 'active' : '' }}">Pengalaman
                                    Kerja</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('karyawan.pelatihan') }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.pelatihan') ? 'active' : '' }}">Pelatihan</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('karyawan.social_media') }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.social_media') ? 'active' : '' }}">Sosial
                                    Media</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->
                    
                    <!-- Start::slide -->
                    @if(Auth::user()->dataDiri)
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-list-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.5 5.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 11.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 17.5l1.5 1.5l2.5 -2.5" /><path d="M11 6l9 0" /><path d="M11 12l9 0" /><path d="M11 18l9 0" /></svg>
                            <span class="side-menu__label">Management Absensi</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('karyawan.absensi_harian.show', ['id' => Auth::user()->dataDiri->id]) }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.absensi_harian.show') ? 'active' : '' }}">
                                    Data Absensi Harian
                                </a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->  
                    @endif    
                    
                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-2 9h-2v-4h2v4zM5 7a1.001 1.001 0 0 1 0-2h13v2H5z"/>
                            </svg>
                            <span class="side-menu__label">Management Gaji</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/karyawan/gaji_bulanan/diri"
                                    class="side-menu__item {{ request()->routeIs('/karyawan/gaji_bulanan/diri') ? 'active' : '' }}">Realisasi Gaji
                                    Bulanan</a>
                            </li>
                        </ul>
                    </li>
                    <!-- End::slide -->       
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 2a1 1 0 0 1 .707 .293l1.708 1.707h4.585a3 3 0 0 1 2.995 2.824l.005 .176v7a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-9a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3zm-6 6h-1a1 1 0 0 0 -1 1v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1 -1v-1h-7a3 3 0 0 1 -3 -3z" />
                            </svg>
                            <span class="side-menu__label">Management Project</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>

                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('karyawan.project') }}"
                                    class="side-menu__item {{ request()->routeIs(['karyawan.project', 'karyawan.detail.project']) ? 'active' : '' }}">
                                    List Project
                                </a>
                                <a href="{{ route('karyawan.task') }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.task', 'karyawan.detail.task') ? 'active' : '' }}">
                                    List Task
                                </a>
                                <a href="{{ route('karyawan.subtask') }}"
                                    class="side-menu__item {{ request()->routeIs('karyawan.subtask', 'karyawan.subtask.detail') ? 'active' : '' }}">
                                    List Sub Task
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="slide">
                        <a href="{{ route('karyawan.laporan_kinerja') }}"
                            class="side-menu__item {{ request()->routeIs(['karyawan.laporan_kinerja', 'karyawan.list.laporan_kinerja']) ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: currentColor;" class="side-menu__icon">
                                <path d="m20 8-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM9 19H7v-9h2v9zm4 0h-2v-6h2v6zm4 0h-2v-3h2v3zM14 9h-1V4l5 5h-4z"></path>
                            </svg>
                            <span class="side-menu__label">Laporan Kinerja</span>
                        </a>
                    </li> --}}
                </ul>
            @endif
            @if (Auth::check() && Auth::user()->role->slug == 'admin-sdm')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/admin_sdm/dashboard"
                                class="side-menu__item {{ request()->routeIs('home') ? 'active' : '' }}">
                                    Absensi</a>
                            </li>
                            <li class="slide">
                                <a href="/admin_sdm/dashboard_gaji"
                                class="side-menu__item {{ request()->routeIs('/admin_sdm/dashboard_gaji') ? 'active' : '' }}">
                                    Gaji</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Informasi Diri</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide__category -->
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
                    </li>
                    <!-- End::slide -->

                    <!-- Start::slide -->
                    @if(Auth::user()->dataDiri)
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg  xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-list-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3.5 5.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 11.5l1.5 1.5l2.5 -2.5" /><path d="M3.5 17.5l1.5 1.5l2.5 -2.5" /><path d="M11 6l9 0" /><path d="M11 12l9 0" /><path d="M11 18l9 0" /></svg>
                            <span class="side-menu__label">Management Absensi</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('admin_sdm.absensi_harian.show', ['id' => Auth::user()->dataDiri->id]) }}"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm.absensi_harian.show') ? 'active' : '' }}">
                                    Data Absensi Harian
                                </a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->
                    @endif   

                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-2 9h-2v-4h2v4zM5 7a1.001 1.001 0 0 1 0-2h13v2H5z"/>
                            </svg>
                            <span class="side-menu__label">Management Gaji</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/admin_sdm/gaji_bulanan/diri"
                                    class="side-menu__item {{ request()->routeIs('/admin_sdm/gaji_bulanan/diri') ? 'active' : '' }}">Realisasi Gaji
                                    Bulanan</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

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

                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-2 9h-2v-4h2v4zM5 7a1.001 1.001 0 0 1 0-2h13v2H5z"/>
                            </svg>
                            <span class="side-menu__label">Management Gaji</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/admin_sdm/gaji"
                                    class="side-menu__item {{ request()->routeIs('/admin_sdm/gaji') ? 'active' : '' }}">Data Gaji</a>
                            </li>
                            <li class="slide">
                                <a href="/admin_sdm/gaji_bulanan"
                                    class="side-menu__item {{ request()->routeIs('/admin_sdm/gaji_bulanan') ? 'active' : '' }}">Realisasi Gaji Bulanan</a>
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
                    </li>
                    <!-- End::slide --> --}}
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 2a1 1 0 0 1 .707 .293l1.708 1.707h4.585a3 3 0 0 1 2.995 2.824l.005 .176v7a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-9a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3zm-6 6h-1a1 1 0 0 0 -1 1v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1 -1v-1h-7a3 3 0 0 1 -3 -3z" />
                            </svg>
                            <span class="side-menu__label">Management Project</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0);">Management Project</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin_sdm.project') }}" 
                                    class="side-menu__item {{ request()->routeIs(['admin_sdm.project', 'admin_sdm.detail.project']) ? 'active' : '' }}">
                                    List Project</a>
                                <a href="{{ route('admin_sdm.task') }}" 
                                    class="side-menu__item {{ request()->routeIs(['admin_sdm.task', 'admin_sdm.detail.task']) ? 'active' : '' }}">
                                    List Task</a>
                                <a href="{{ route('admin_sdm.subtask') }}" 
                                    class="side-menu__item {{ request()->routeIs(['admin_sdm.subtask', 'admin_sdm.subtask.detail']) ? 'active' : '' }}">
                                    List Sub Task</a>
                            </li>
                        </ul>
                    </li>
                    <li class="slide">
                        <a href="{{ route('admin_sdm.laporan_kinerja') }}"
                            class="side-menu__item {{ request()->routeIs(['admin_sdm.laporan_kinerja', 'admin_sdm.list.laporan_kinerja']) ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: currentColor;" class="side-menu__icon">
                                <path d="m20 8-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM9 19H7v-9h2v9zm4 0h-2v-6h2v6zm4 0h-2v-3h2v3zM14 9h-1V4l5 5h-4z"></path>
                            </svg>
                            <span class="side-menu__label">Laporan Kinerja</span>
                        </a>
                    </li> --}}

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
                            <span class="side-menu__label">Master Data</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('admin_sdm.absensi.index') }}"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/absensi') ? 'active' : '' }}">
                                    Absensi</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin_sdm.hari_libur') }}"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/hari_libur') ? 'active' : '' }}">
                                    Hari Libur</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin_sdm.sub_jabatan') }}"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/sub_jabatan') ? 'active' : '' }}">
                                    Jabatan</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin_sdm.status_pekerjaan') }}"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/status_pekerjaan') ? 'active' : '' }}">
                                    Status Pekerjaan</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('admin_sdm.divisi') }}"
                                    class="side-menu__item {{ request()->routeIs('admin_sdm/status_pekerjaan') ? 'active' : '' }}">
                                    Divisi</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
            @if (Auth::check() && Auth::user()->role->slug == 'manager')
                <ul class="main-menu">
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <li class="slide">
                        <a href="{{ route('manajer.dashboard') }}"
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
                    <li class="slide__category"><span class="category-name">Manajer</span></li>
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
                                <a href="{{ route('manajer.datadiri') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.datadiri') ? 'active' : '' }}">Data
                                    Diri</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('manajer.pengalaman_kerja') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.pengalaman_kerja') ? 'active' : '' }}">Pengalaman
                                    Kerja</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('manajer.pelatihan') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.pelatihan') ? 'active' : '' }}">Pelatihan</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('manajer.social_media') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.social_media') ? 'active' : '' }}">Sosial
                                    Media</a>
                            </li>
                        </ul>
                    </li>
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M12 2a1 1 0 0 1 .707 .293l1.708 1.707h4.585a3 3 0 0 1 2.995 2.824l.005 .176v7a3 3 0 0 1 -3 3h-1v1a3 3 0 0 1 -3 3h-10a3 3 0 0 1 -3 -3v-9a3 3 0 0 1 3 -3h1v-1a3 3 0 0 1 3 -3zm-6 6h-1a1 1 0 0 0 -1 1v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1 -1v-1h-7a3 3 0 0 1 -3 -3z" />
                            </svg>
                            <span class="side-menu__label">Management Project</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0);">Management Project</a>
                            </li>
                            <li class="slide">
                                <a href="{{ route('manajer.project') }}" 
                                    class="side-menu__item {{ request()->routeIs(['manajer.project', 'manajer.detail.project']) ? 'active' : '' }}">
                                    List Project</a>
                                <a href="{{ route('manajer.task') }}" 
                                    class="side-menu__item {{ request()->routeIs(['manajer.task', 'manajer.detail.task']) ? 'active' : '' }}">
                                    List Task</a>
                                <a href="{{ route('manajer.subtask') }}" 
                                    class="side-menu__item {{ request()->routeIs(['manajer.subtask', 'manajer.subtask.detail']) ? 'active' : '' }}">
                                    List Sub Task</a>
                            </li>
                        </ul>
                    </li>
                    <li class="slide">
                        <a href="{{ route('manajer.laporan_kinerja') }}"
                            class="side-menu__item {{ request()->routeIs(['manajer.laporan_kinerja', 'manajer.list.laporan_kinerja']) ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="fill: currentColor;" class="side-menu__icon">
                                <path d="m20 8-6-6H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8zM9 19H7v-9h2v9zm4 0h-2v-6h2v6zm4 0h-2v-3h2v3zM14 9h-1V4l5 5h-4z"></path>
                            </svg>
                            <span class="side-menu__label">Laporan Kinerja</span>
                        </a>
                    </li>
                    <li class="slide__category"><span class="category-name">General</span></li>
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
                            <span class="side-menu__label">Master Data</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="{{ route('manajer.perusahaan') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.perusahaan') ? 'active' : '' }}">
                                    Instansi</a>
                                <a href="{{ route('manajer.tipe_task') }}"
                                    class="side-menu__item {{ request()->routeIs('manajer.tipe_task') ? 'active' : '' }}">
                                    Tipe Task</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endif
            @if (Auth::check() && Auth::user()->role->slug == 'direktur')
                <ul class="main-menu">
                    <!-- Start::slide__category -->
                    <li class="slide__category"><span class="category-name">Main</span></li>
                    <!-- End::slide__category -->

                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                                <path
                                    d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                            </svg>
                            <span class="side-menu__label">Dashboard</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/direktur/dashboard"
                                class="side-menu__item {{ request()->routeIs('home') ? 'active' : '' }}">
                                    Absensi</a>
                            </li>
                            <li class="slide">
                                <a href="/direktur/dashboard_gaji"
                                class="side-menu__item {{ request()->routeIs('/direktur/dashboard_gaji') ? 'active' : '' }}">
                                    Gaji</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

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
                                <a href="/direktur/kepegawaian"
                                    class="side-menu__item {{ request()->routeIs('/direktur/kepegawaian') ? 'active' : '' }}">Data
                                    Kepegawaian</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->

                    <!-- Start::slide -->
                    <li class="slide has-sub">
                        <a href="javascript:void(0);"
                            class="side-menu__item {{ request()->routeIs('users*') ? 'active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"
                                class="icon icon-tabler icons-tabler-filled icon-tabler-folders side-menu__icon">
                                <path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zm-2 9h-2v-4h2v4zM5 7a1.001 1.001 0 0 1 0-2h13v2H5z"/>
                            </svg>
                            <span class="side-menu__label">Management Gaji</span>
                            <i class="fe fe-chevron-right side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1">
                            <li class="slide">
                                <a href="/direktur/gaji"
                                    class="side-menu__item {{ request()->routeIs('/direktur/gaji') ? 'active' : '' }}">Data
                                    Gaji</a>
                            </li>
                            <li class="slide">
                                <a href="/direktur/gaji_bulanan"
                                    class="side-menu__item {{ request()->routeIs('/direktur/gaji_bulanan') ? 'active' : '' }}">Realisasi Gaji
                                    Bulanan</a>
                            </li>
                        </ul>
                    </li> <!-- End::slide -->
                </ul>
            @endif
            <li class="slide__category"><span class="category-name">Sync SSO Login</span></li>
            <li class="slide">
                <a href="{{ route('sso') }}" class="side-menu__item">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M19.14,12.94a7.14,7.14,0,0,0,0-1.88l2-1.56a.5.5,0,0,0,.11-.62L19.66,5.62a.5.5,0,0,0-.61-.22l-2.37.95a7.23,7.23,0,0,0-1.62-.95L14.1,3a.5.5,0,0,0-.5-.41H10.4a.5.5,0,0,0-.5.41L9.36,5.4a7.23,7.23,0,0,0-1.62.95L5.37,5.4a.5.5,0,0,0-.61.22L2.71,9.88a.5.5,0,0,0,.11.62l2,1.56a7.14,7.14,0,0,0,0,1.88l-2,1.56a.5.5,0,0,0-.11.62l2.65,4.26a.5.5,0,0,0,.61.22l2.37-.95a7.23,7.23,0,0,0,1.62.95l.54,2.42a.5.5,0,0,0,.5.41h3.2a.5.5,0,0,0,.5-.41l.54-2.42a7.23,7.23,0,0,0,1.62-.95l2.37.95a.5.5,0,0,0,.61-.22l2.65-4.26a.5.5,0,0,0-.11-.62ZM12,15.5A3.5,3.5,0,1,1,15.5,12,3.51,3.51,0,0,1,12,15.5Z">
                        </path>
                    </svg>
                    <span class="side-menu__label">Sync SSO</span>
                </a>
            </li>
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
