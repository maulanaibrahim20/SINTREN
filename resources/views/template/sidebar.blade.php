<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="index.html">
                <img src="{{ url('/assets') }}/images/brand/logo.png" class="header-brand-img main-logo" alt="Sparic logo">
                <img src="{{ url('/assets') }}/images/brand/logo-light.png" class="header-brand-img darklogo"
                    alt="Sparic logo">
                <img src="{{ url('/assets') }}/images/brand/icon.png" class="header-brand-img icon-logo"
                    alt="Sparic logo">
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
                                href="{{ url('/dinas_pangan/dashboard') }}"><i class="side-menu__icon fa fa-home"></i><span
                                    class="side-menu__label">Dashboard</span></a>
                        </li>
                    @endif
                @endauth
                @can('operator')
                    <li class="sub-category">
                        <h3>Data Pengguna</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'pertanian' || Request::segment(3) == 'uptd' || Request::segment(3) == 'penyuluh' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'pertanian' || Request::segment(3) == 'uptd' || Request::segment(3) == 'penyuluh' ? 'active open' : '' }}"
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
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Data Tanaman</h3>
                    </li>
                    <li
                        class="slide  {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' || Request::segment(3) == 'pengairan' || Request::segment(3) == 'tanaman_palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' || Request::segment(3) == 'pengairan' || Request::segment(3) == 'tanaman_palawija' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-pagelines"></i>
                            <span class="side-menu__label">Data Tanaman</span><i class="angle fe fe-chevron-right"></i></a>
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
                                                <li
                                                    class="sub-slide {{ Request::segment(3) == 'tanaman_palawija' ? 'is-expanded' : '' }}">
                                                    <a class="sub-side-menu__item {{ Request::segment(3) == 'tanaman_palawija' ? 'active' : '' }} "
                                                        data-bs-toggle="sub-slide" href="javascript:void(0)"><span
                                                            class="sub-side-menu__label">Kategori</span><i
                                                            class="sub-angle fe fe-chevron-right"></i></a>
                                                    <ul class="sub-slide-menu">
                                                        <li>
                                                            <a
                                                                class="sub-slide-item {{ Request::segment(3) == 'tanaman_palawija' ? 'active' : '' }}"href="{{ url('/operator/kategori/tanaman_palawija') }}">
                                                                Kategori Palawija
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li
                                                    class="sub-slide {{ Request::segment(3) == 'pengairan' ? 'is-expanded' : '' }}">
                                                    <a class="sub-side-menu__item {{ Request::segment(3) == 'pengairan' ? 'active' : '' }} "
                                                        data-bs-toggle="sub-slide" href="javascript:void(0)"><span
                                                            class="sub-side-menu__label">Pengairan</span><i
                                                            class="sub-angle fe fe-chevron-right"></i></a>
                                                    <ul class="sub-slide-menu">
                                                        <li><a class="sub-slide-item {{ Request::segment(3) == 'pengairan' ? 'active' : '' }}"
                                                                href="{{ url('/operator/master/pengairan') }}">Pengairan</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Master</h3>
                    </li>
                    <li
                        class="slide  {{ Request::segment(3) == 'role' || Request::segment(3) == 'wilayah' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'role' || Request::segment(3) == 'wilayah' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)">
                            <i class="side-menu__icon fa fa-database"></i>
                            <span class="side-menu__label">Master</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu ">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side29">
                                            <ul class="sidemenu-list">
                                                <li class="side-menu-label1"><a href="javascript:void(0)">Master</a></li>
                                                <li><a href="{{ url('/operator/master/role') }}"
                                                        class="slide-item {{ Request::segment(3) == 'role' ? 'active' : '' }}">Role</a>
                                                </li>
                                                <li><a href="{{ url('/operator/master/wilayah') }}"
                                                        class="slide-item {{ Request::segment(3) == 'wilayah' ? 'active' : '' }}">Wilayah</a>
                                                </li>
                                                {{-- <li
                                                    class="sub-slide {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' ? 'is-expanded' : '' }}">
                                                    <a class="sub-side-menu__item {{ Request::segment(3) == 'padi' || Request::Segment(3) == 'palawija' ? 'active' : '' }} "
                                                        data-bs-toggle="sub-slide" href="javascript:void(0)"><span
                                                            class="sub-side-menu__label">Jenis
                                                            Tanaman</span><i class="sub-angle fe fe-chevron-right"></i></a>
                                                    <ul class="sub-slide-menu">
                                                        <li><a class="sub-slide-item {{ Request::segment(3) == 'padi' ? 'active' : '' }}"
                                                                href="{{ url('/operator/tanaman/padi') }}">Tanaman
                                                                Padi</a></li>
                                                        <li><a class="sub-slide-item {{ Request::segment(3) == 'palawija' ? 'active' : '' }}"
                                                                href="{{ url('/operator/tanaman/palawija') }}">Tanaman
                                                                Palawija</a>
                                                        </li>
                                                        <li class="sub-slide2">
                                                            <a class="sub-side-menu__item2" href="javascript:void(0)"
                                                                data-bs-toggle="sub-slide2"><span
                                                                    class="sub-side-menu__label2">Submenu-2.3</span><i
                                                                    class="sub-angle2 fe fe-chevron-right"></i></a>
                                                            <ul class="sub-slide-menu2">
                                                                <li><a href="javascript:void(0)"
                                                                        class="sub-slide-item2">Submenu-2.3.1</a></li>
                                                                <li><a href="javascript:void(0)"
                                                                        class="sub-slide-item2">Submenu-2.3.2</a></li>
                                                                <li><a href="javascript:void(0)"
                                                                        class="sub-slide-item2">Submenu-2.3.3</a></li>
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                </li> --}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('pertanian')
                @endcan
                @can('uptd')
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
                @can('dinas_pangan')
                <li class="sub-category">
                    <h3>Data Pengguna</h3>
                </li>
                <li
                    class="slide {{ Request::segment(3) == 'pasar' ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ Request::segment(3) == 'pasar' ? 'active open' : '' }}"
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
                                            <li><a href="{{ url('/dinas_pangan/user/pasar') }}"
                                                    class="slide-item {{ Request::segment(3) == 'pasar' ? 'active' : '' }}">Pengguna Pasar</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
                @endcan
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </div>
</div>
