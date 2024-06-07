<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            @auth
                @if (Auth::user()->role_id == 1)
                    <a class="header-brand1" href="{{ url('/operator/dashboard') }}">
                    @elseif (Auth::user()->role_id == 2)
                        <a class="header-brand1" href="{{ url('/pertanian/dashboard') }}">
                        @elseif(Auth::user()->role_id == 3)
                            <a class="header-brand1" href="{{ url('/uptd/dashboard') }}">
                            @elseif (Auth::user()->role_id == 4)
                                <a class="header-brand1" href="{{ url('/penyuluh/dashboard') }}">
                                @elseif (Auth::user()->role_id == 5)
                                    <a class="header-brand1" href="{{ url('/pangan/dashboard') }}">
                @endif
            @endauth
            <img src="{{ url('/assets') }}/images/brand/logo.png" class="header-brand-img main-logo" alt="Sparic logo">
            <img src="{{ url('/assets') }}/images/brand/logo-light.png" class="header-brand-img darklogo"
                alt="Sparic logo">
            <img src="{{ url('/assets') }}/images/brand/icon.png" class="header-brand-img icon-logo" alt="Sparic logo">
            <img src="{{ url('/assets') }}/images/brand/icon2.png" class="header-brand-img icon-logo2"
                alt="Sparic logo">
            </a>
        </div>
        <!-- logo-->
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="sub-category">
                    <h3>Main</h3>
                </li>
                @auth
                    @if (Auth::user()->role_id == 1)
                        <li>
                            <a class="side-menu__item {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}"
                                href="{{ url('/operator/dashboard') }}"><i class="side-menu__icon fa fa-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                    @elseif(Auth::user()->role_id == 2)
                        <li>
                            <a class="side-menu__item {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}"
                                href="{{ url('/pertanian/dashboard') }}"><i class="side-menu__icon fa fa-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                    @elseif(Auth::user()->role_id == 3)
                        <li>
                            <a class="side-menu__item {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}"
                                href="{{ url('/uptd/dashboard') }}"><i class="side-menu__icon fa fa-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                    @elseif(Auth::user()->role_id == 4)
                        <li>
                            <a class="side-menu__item {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}"
                                href="{{ url('/penyuluh/dashboard') }}"><i class="side-menu__icon fa fa-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                    @elseif(Auth::user()->role_id == 5)
                        <li>
                            <a class="side-menu__item {{ Request::segment(2) == 'dashboard' ? 'active' : '' }}"
                                href="{{ url('/pangan/dashboard') }}"><i class="side-menu__icon fa fa-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                    @endif
                @endauth
                @can('operator')
                    <li class="sub-category">
                        <h3>Kelola Data Pengguna</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'pertanian' || Request::segment(3) == 'uptd' || Request::segment(3) == 'penyuluh' || Request::segment(3) == 'pangan' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'pertanian' || Request::segment(3) == 'uptd' || Request::segment(3) == 'penyuluh' || Request::segment(3) == 'pangan' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon ti ti-user"></i>
                            <span class="side-menu__label">Data Pengguna</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Data Pengguna</a>
                                                </li>
                                                <li><a href="{{ url('/operator/user/pertanian') }}"
                                                        class="slide-item {{ Request::segment(3) == 'pertanian' ? 'active' : '' }}">Pengguna
                                                        Pertanian</a>
                                                </li>
                                                <li><a href="{{ url('/operator/user/uptd') }}"
                                                        class="slide-item {{ Request::segment(3) == 'uptd' ? 'active' : '' }}">Pengguna
                                                        UPTD</a>
                                                </li>
                                                <li><a href="{{ url('/operator/user/penyuluh') }}"
                                                        class="slide-item {{ Request::segment(3) == 'penyuluh' ? 'active' : '' }}">Pengguna
                                                        Penyuluh</a>
                                                </li>
                                                <li><a href="{{ url('/operator/user/pangan') }}"
                                                        class="slide-item {{ Request::segment(3) == 'pangan' ? 'active' : '' }}">Pengguna
                                                        Pangan</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Master Data</h3>
                    </li>
                    <li
                        class="slide  {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' || Request::segment(3) == 'tanaman_palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' || Request::segment(3) == 'tanaman_palawija' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-pagelines"></i>
                            <span class="side-menu__label">Data Tanaman</span><i class="angle fe fe-chevron-right"></i>
                        </a>
                        <ul class="slide-menu ">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Data Tanaman</a>
                                                </li>
                                                <li><a href="{{ url('/operator/tanaman/padi') }}"
                                                        class="slide-item {{ Request::segment(3) == 'padi' ? 'active' : '' }}">Tanaman
                                                        Padi</a>
                                                </li>
                                                <li><a href="{{ url('/operator/tanaman/palawija') }}"
                                                        class="slide-item {{ Request::segment(3) == 'palawija' ? 'active' : '' }}">Tanaman
                                                        Palawija</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'pengairan' ? 'active' : '' }}"
                            href="{{ url('/operator/master/pengairan') }}"><i
                                class="side-menu__icon fe fe-droplet"></i><span
                                class="side-menu__label">Pengairan</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'wilayah' ? 'active' : '' }}"
                            href="{{ url('/operator/master/wilayah') }}"><i class="side-menu__icon fa fa-globe"></i><span
                                class="side-menu__label">Wilayah</span>
                        </a>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'role' ? 'active' : '' }}"
                            href="{{ url('/operator/master/role') }}"><i class="side-menu__icon fa fa-cogs"></i><span
                                class="side-menu__label">Role</span>
                        </a>
                    </li>
                    <li class="sub-category mt-4">
                        <h3>Kelola Luas Lahan Wilayah</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'luas_lahan_wilayah' ? 'active' : '' }}"
                            href="{{ url('/operator/master/luas_lahan_wilayah') }}"><i
                                class="side-menu__icon fa fa-map"></i><span class="side-menu__label">Luas Lahan
                                Wilayah</span></a>
                    </li>
                @endcan
                @can('pertanian')
                    <li class="sub-category">
                        <h3>Data Laporan</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(2) == 'data_padi' || Request::segment(2) == 'data_palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(2) == 'data_padi' || Request::segment(2) == 'data_palawija' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-files-o"></i>
                            <span class="side-menu__label">Laporan</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Data</a></li>
                                                <li><a href="{{ url('/pertanian/data_padi') }}"
                                                        class="slide-item {{ Request::segment(2) == 'data_padi' ? 'active' : '' }}">
                                                        Data Padi
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/pertanian/data_palawija') }}"
                                                        class="slide-item {{ Request::segment(2) == 'data_palawija' ? 'active' : '' }}">
                                                        Data Palawija
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Prediksi</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'padi' ? 'active' : '' }}"
                            href="{{ url('/pertanian/prediksi/padi') }}"><i class="side-menu__icon fa fa-leaf"></i><span
                                class="side-menu__label">Prediksi Padi</span>
                        </a>
                        <a class="side-menu__item {{ Request::segment(3) == 'padiSp' ? 'active' : '' }}"
                            href="{{ url('/pertanian/prediksi/padiSp') }}"><i
                                class="side-menu__icon fa fa-leaf"></i><span class="side-menu__label">Prediksi Padi
                                SP</span>
                        </a>
                        <a class="side-menu__item {{ Request::segment(3) == 'palawija' ? 'active' : '' }}"
                            href="{{ url('/pertanian/prediksi/palawija') }}"><i
                                class="side-menu__icon fa fa-pagelines"></i><span class="side-menu__label">Prediksi
                                Palawija</span>
                        </a>
                    </li>
                @endcan
                @can('uptd')
                    <li class="sub-category">
                        <h3>Kelola Akun</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'penyuluh' ? 'active' : '' }}"
                            href="{{ url('/uptd/pengguna/penyuluh') }}"><i class="side-menu__icon fa fa-user"></i><span
                                class="side-menu__label">Akun
                                Penyuluh</span></a>
                    </li>
                    <li class="sub-category">
                        <h3>Laporan</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-files-o"></i>
                            <span class="side-menu__label">Laporan</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Laporan</a></li>
                                                <li><a href="{{ url('/uptd/laporan/padi') }}"
                                                        class="slide-item {{ Request::segment(3) == 'padi' ? 'active' : '' }}">
                                                        Laporan Padi
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/uptd/laporan/palawija') }}"
                                                        class="slide-item {{ Request::segment(3) == 'palawija' ? 'active' : '' }}">
                                                        Laporan Palawija
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Master Luas Lahan Wilayah</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'luas_lahan_wilayah' ? 'active' : '' }}"
                            href="{{ url('/uptd/master/luas_lahan_wilayah') }}"><i
                                class="side-menu__icon fa fa-map"></i><span class="side-menu__label">Luas Lahan
                                Wilayah</span></a>
                    </li>
                    {{-- <li class="sub-category">
                        <h3>Pengaturan</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == '' ? 'active' : '' }}"
                            href="{{ url('/uptd/master/') }}"><i class="side-menu__icon fa fa-map"></i><span
                                class="side-menu__label">Profil Saya</span></a>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == '' ? 'active' : '' }}"
                            href="{{ url('/uptd/master/') }}"><i class="side-menu__icon fa fa-map"></i><span
                                class="side-menu__label">Change Password</span></a>
                    </li> --}}
                @endcan
                @can('penyuluh')
                    <li class="sub-category">
                        <h3>Laporan</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'laporan_padi' || Request::segment(3) == 'laporan_palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'laporan_padi' || Request::segment(3) == 'laporan_palawija' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-files-o"></i>
                            <span class="side-menu__label">Laporan</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Master</a></li>
                                                <li><a href="{{ url('/penyuluh/create/laporan_padi') }}"
                                                        class="slide-item {{ Request::segment(3) == 'laporan_padi' ? 'active' : '' }}">
                                                        Laporan Padi
                                                    </a>
                                                </li>
                                                <li><a href="{{ url('/penyuluh/create/laporan_palawija') }}"
                                                        class="slide-item {{ Request::segment(3) == 'laporan_palawija' ? 'active' : '' }}">
                                                        Laporan Palawija
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endcan
                {{-- yuan diana --}}
                @can('pangan')
                    <li class="sub-category">
                        <h3>Pasar</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'pasar' || Request::segment(3) == 'data_pasar' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'pasar' || Request::segment(3) == 'data_pasar' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-files-o"></i>
                            <span class="side-menu__label">Pasar</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Pasar</a></li>
                                                <li><a href="{{ url('/pangan/user/pasar') }}"
                                                        class="slide-item {{ Request::segment(3) == 'pasar' ? 'active' : '' }}">
                                                        Data Pengguna Pasar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/pangan/create/data_pasar') }}"
                                                        class="slide-item {{ Request::segment(3) == 'data_pasar' ? 'active' : '' }}">
                                                        Data Pasar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Pangan</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'kategori' || Request::segment(3) == 'data_pangan' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'kategori' || Request::segment(3) == 'data_pangan' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-files-o"></i>
                            <span class="side-menu__label">Pangan</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Pangan</a></li>
                                                <li><a href="{{ url('/pangan/user/pasar') }}"
                                                        class="slide-item {{ Request::segment(3) == 'pasar' ? 'active' : '' }}">
                                                        Data Pengguna Pasar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/pangan/create/data_pasar') }}"
                                                        class="slide-item {{ Request::segment(3) == 'data_pasar' ? 'active' : '' }}">
                                                        Data Pasar
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endcan
                <li class="sub-category">
                    <h3>Pengaturan</h3>
                </li>
                <li>
                    <a class="side-menu__item" href="#" id="logoutButton">
                        <i class="side-menu__icon fa fa-sign-in"></i>
                        <span class="side-menu__label">Logout</span>
                    </a>
                </li>
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </div>
</div>
