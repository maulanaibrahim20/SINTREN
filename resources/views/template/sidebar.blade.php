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
                    @endif
                @endauth
            </ul>
            @can('operator')
                <ul class="side-menu">
                    <li class="sub-category">
                        <h3>Data Pengguna</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'pertanian' || Request::segment(3) == 'uptd' || Request::segment(3) == 'penyuluh'
                            ? 'is-expanded'
                            : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'pertanian' || Request::segment(3) == 'uptd' || Request::segment(3) == 'penyuluh' ? 'active open' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon ti ti-user"></i><span
                                class="side-menu__label">Data Pengguna</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side33">
                                            <ul class="sidemenu-list">
                                                <li><a href="{{ url('/operator/user/pertanian') }}"
                                                        class="slide-item {{ Request::segment(3) == 'pertanian' ? 'active' : '' }}">Pengguna
                                                        Pertanian</a></li>
                                                <li><a href="{{ url('/operator/user/uptd') }}"
                                                        class="slide-item {{ Request::segment(3) == 'uptd' ? 'active' : '' }}">Pengguna
                                                        UPTD</a></li>
                                                <li><a href="{{ url('/operator/user/penyuluh') }}"
                                                        class="slide-item {{ Request::segment(3) == 'penyuluh' ? 'active' : '' }}">Pengguna
                                                        Penyuluh</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Pengairan</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'pengairan' ? 'active' : '' }}"
                            href="{{ url('/operator/master/pengairan') }}"><i class="side-menu__icon fa fa-tint"></i><span
                                class="side-menu__label">Pengairan</span>
                        </a>
                    </li>
                    </li>
                    <li class="sub-category">
                        <h3>Tanaman</h3>
                    </li>
                    <li
                        class="slide {{ Request::segment(3) == 'padi' || Request::segment(3) == 'palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'padi' ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)"><i
                                class="side-menu__icon mdi mdi-barley"></i><span class="side-menu__label">Jenis
                                Tanaman</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side33">
                                            <ul class="sidemenu-list">
                                                <li><a href="{{ url('/operator/tanaman/padi') }}"
                                                        class="slide-item {{ Request::segment(3) == 'padi' ? 'active' : '' }}">Tanaman
                                                        Padi</a></li>
                                            </ul>
                                            <ul class="sidemenu-list">
                                                <li><a href="{{ url('/operator/tanaman/palawija') }}"
                                                        class="slide-item {{ Request::segment(3) == 'palawija' ? 'active' : '' }}">Tanaman
                                                        Palawija</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="sub-category">
                        <h3>Master Kategori Tanaman</h3>
                    </li>
                    <li class="slide {{ Request::segment(3) == 'kategori_tanaman_palawija' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'kategori_tanaman_palawija' ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)"><i class="side-menu__icon fa fa-tree"></i><span
                                class="side-menu__label">Master Kategori
                                Tanaman</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side33">
                                            <ul class="sidemenu-list">
                                                <li><a href="{{ url('/operator/master/kategori_tanaman_palawija') }}"
                                                        class="slide-item {{ Request::segment(3) == 'kategori_tanaman_palawija' ? 'active' : '' }}">Kategori
                                                        Tanaman Palawija</a>
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
                        class="slide {{ Request::segment(3) == 'role' || Request::segment(3) == 'wilayah' ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ Request::segment(3) == 'role' || Request::segment(3) == 'wilayah' ? 'active' : '' }}"
                            data-bs-toggle="slide" href="javascript:void(0)"><i
                                class="side-menu__icon mdi mdi-database"></i><span
                                class="side-menu__label">Master</span><i class="angle fe fe-chevron-right"></i></a>
                        <ul class="slide-menu">
                            <li class="panel sidetab-menu">
                                <div class="panel-body tabs-menu-body p-0 border-0">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="side33">
                                            <ul class="sidemenu-list">
                                                <li><a href="{{ url('/operator/master/wilayah') }}"
                                                        class="slide-item {{ Request::segment(3) == 'wilayah' ? 'active' : '' }}">Wilayah</a>
                                                </li>
                                                <li><a href="{{ url('/operator/master/role') }}"
                                                        class="slide-item {{ Request::segment(3) == 'role' ? 'active' : '' }}">Role</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            @endcan
            @can('pertanian')
            @endcan
            @can('penyuluh')
                <ul class="side-menu">
                    <li class="sub-category">
                        <h3>Laporan</h3>
                    </li>
                    <li>
                        <a class="side-menu__item {{ Request::segment(3) == 'laporan_padi' ? 'active' : '' }}"
                            href="{{ url('/penyuluh/create/laporan_padi') }}"><i
                                class="side-menu__icon mdi mdi-barley"></i><span class="side-menu__label">Laporan
                                Padi</span>
                        </a>
                    </li>
                    </li>
                </ul>
            @endcan
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </div>
</div>
