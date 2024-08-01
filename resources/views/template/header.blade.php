<div class="app-header header sticky">
    <div class="container-fluid main-container">
        <div class="d-flex">
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="javascript:void(0)"></a>
            <!-- sidebar-toggle-->
            <a class="logo-horizontal" href="index.html">
                <img src="{{ url('/assets') }}/images/brand/logo.png" class="header-brand-img main-logo"
                    alt="Sparic logo">
                <img src="{{ url('/assets') }}/images/brand/logo-light.png" class="header-brand-img darklogo"
                    alt="Sparic logo">
            </a>
            <!-- LOGO -->
            <div class="d-flex order-lg-2 ms-auto header-right-icons">
                <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                    aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                </button>
                <div class="navbar navbar-collapse responsive-navbar p-0">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                        <div class="d-flex order-lg-2">
                            <div class="d-flex country">
                                <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                    <span class="dark-layout mt-1"><i class="ri-moon-clear-line"></i></span>
                                    <span class="light-layout mt-1"><i class="ri-sun-line"></i></span>
                                </a>
                            </div>
                            <!-- CART -->
                            <div class="dropdown d-flex">
                                <a class="nav-link icon full-screen-link" id="fullscreen-button">
                                    <i class="ri-fullscreen-exit-line fullscreen-button"></i>
                                </a>
                            </div>
                            <!-- FULL-SCREEN -->
                            {{-- <div class="dropdown d-flex notifications">
                                <a class="nav-link icon" data-bs-toggle="dropdown"><i
                                        class="ri-notification-line"></i><span class=" pulse"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow ">
                                    <div class="drop-heading border-bottom">
                                        <h6 class="mt-1 mb-0 fs-14 text-dark fw-semibold">Notifications
                                        </h6>
                                    </div>
                                    <div class="notifications-menu header-dropdown-scroll">
                                        <a class="dropdown-item border-bottom d-flex" href="notify-list.html">
                                            <div>
                                                <span
                                                    class="avatar avatar-md fs-20 brround fw-semibold text-center bg-primary-transparent"><i
                                                        class="fe fe-message-square text-primary"></i></span>
                                            </div>
                                            <div class="wd-80p ms-3 my-auto">
                                                <h5 class="text-dark mb-0 fw-semibold">Gladys Dare <span
                                                        class="text-muted">commented on</span>
                                                    Ecosystems</h5>
                                                <span class="notification-subtext">2m ago</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item border-bottom d-flex" href="notify-list.html">
                                            <div>
                                                <span
                                                    class="avatar avatar-md fs-20 brround fw-semibold text-danger bg-danger-transparent"><i
                                                        class="fe fe-user"></i></span>
                                            </div>
                                            <div class="wd-80p ms-3 my-auto">
                                                <h5 class="text-dark mb-0 fw-semibold">Jackson Wisky
                                                    <span class="text-muted"> followed
                                                        you</span>
                                                </h5>
                                                <span class="notification-subtext">15 min ago</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item border-bottom d-flex" href="notify-list.html">
                                            <span
                                                class="avatar avatar-md fs-20 brround fw-semibold text-center bg-success-transparent"><i
                                                    class="fe fe-check text-success"></i></span>
                                            <div class="wd-80p ms-3 my-auto">
                                                <h5 class="text-muted fw-semibold mb-0">You swapped exactly
                                                    <span class="text-dark fw-bold">2.054 BTC</span> for
                                                    <Span class="text-dark fw-bold">14,124.00</Span>
                                                </h5>
                                                <span class="notification-subtext">1 day ago</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item border-bottom d-flex" href="notify-list.html">
                                            <div>
                                                <span
                                                    class="avatar avatar-md fs-20 brround fw-semibold text-center bg-warning-transparent"><i
                                                        class="fe fe-dollar-sign text-warning"></i></span>
                                            </div>
                                            <div class="wd-80p ms-3 my-auto">
                                                <h5 class="text-dark mb-0 fw-semibold">Laurel <span
                                                        class="text-muted">donated</span> <span
                                                        class="text-success fw-semibold">$100</span> <span
                                                        class="text-muted">for</span> carbon removal</h5>
                                                <span class="notification-subtext">15 min ago</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex" href="notify-list.html">
                                            <div>
                                                <span
                                                    class="avatar avatar-md fs-20 brround fw-semibold text-center bg-info-transparent"><i
                                                        class="fe fe-thumbs-up text-info"></i></span>
                                            </div>
                                            <div class="wd-80p ms-3 my-auto">
                                                <h5 class="text-dark mb-0 fw-semibold">Sunny Grahm <span
                                                        class="text-muted">voted for</span> carbon capture
                                                </h5>
                                                <span class="notification-subtext">2 hors ago</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="text-center dropdown-footer">
                                        <a class="btn btn-primary btn-sm btn-block text-center"
                                            href="notify-list.html">VIEW ALL NOTIFICATIONS</a>
                                    </div>
                                </div>
                            </div> --}}
                            <!-- NOTIFICATIONS -->
                            {{-- <div class="dropdown d-flex message">
                                <a class="nav-link icon text-center" data-bs-toggle="dropdown">
                                    <i class="ri-chat-1-line"></i><span class="pulse-danger"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    <div class="drop-heading border-bottom">
                                        <h6 class="mt-1 mb-0 fs-14 fw-semibold text-dark">You have 5
                                            Messages</h6>
                                    </div>
                                    <div class="message-menu message-menu-scroll">
                                        <a class="dropdown-item border-bottom d-flex align-items-center"
                                            href="chat.html">
                                            <img class="avatar avatar-md brround cover-image"
                                                src="{{ url('/assets') }}/images/users/male/28.jpg" alt="person-image">
                                            <div class="wd-90p ms-2">
                                                <div class="d-flex">
                                                    <h5 class="mb-0 text-dark fw-semibold ">Madeleine</h5>
                                                    <small class="text-muted ms-auto">
                                                        2 min ago
                                                    </small>
                                                </div>
                                                <span class="fw-semibold mb-0">Just completed <span
                                                        class="text-info">Project</span></span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item border-bottom d-flex align-items-center"
                                            href="chat.html">
                                            <img class="avatar avatar-md brround me-3 align-self-center cover-image"
                                                src="{{ url('/assets') }}/images/users/male/32.jpg" alt="person-image">
                                            <div class="wd-90p">
                                                <div class="d-flex">
                                                    <h5 class="mb-0 text-dark fw-semibold ">Anthony</h5>
                                                    <small class="text-muted ms-auto text-end">
                                                        1 hour ago
                                                    </small>
                                                </div>
                                                <span class="fw-semibold">Updates the new <span class="text-info">Task
                                                        Names</span></span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item border-bottom d-flex align-items-center"
                                            href="chat.html">
                                            <img class="avatar avatar-md brround me-3 cover-image"
                                                src="{{ url('/assets') }}/images/users/female/23.jpg"
                                                alt="person-image">
                                            <div class="wd-90p">
                                                <div class="d-flex">
                                                    <h5 class="mb-0 text-dark fw-semibold ">Olivia</h5>
                                                    <small class="text-muted ms-auto text-end">
                                                        1 hour ago
                                                    </small>
                                                </div>
                                                <span class="fw-semibold">Added a file into <span
                                                        class="text-info">Project Name</span></span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="chat.html">
                                            <img class="avatar avatar-md brround me-3 cover-image"
                                                src="{{ url('/assets') }}/images/users/male/33.jpg" alt="person-image">
                                            <div class="wd-90p">
                                                <div class="d-flex">
                                                    <h5 class="mb-0 text-dark fw-semibold ">Sanderson</h5>
                                                    <small class="text-muted ms-auto text-end">
                                                        1 days ago
                                                    </small>
                                                </div>
                                                <span class="fw-semibold">Assigned 9 Upcoming <span
                                                        class="text-info">Projects</span></span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item border-bottom d-flex align-items-center border-bottom-0"
                                            href="chat.html">
                                            <img class="avatar avatar-md brround cover-image"
                                                src="{{ url('/assets') }}/images/users/male/8.jpg" alt="person-image">
                                            <div class="wd-90p ms-2">
                                                <div class="d-flex">
                                                    <h5 class="mb-0 text-dark fw-semibold ">Madeleine</h5>
                                                    <small class="text-muted ms-auto">
                                                        2 min ago
                                                    </small>
                                                </div>
                                                <span class="fw-semibold mb-0">Just completed <span
                                                        class="text-info">Project</span></span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="dropdown-divider m-0">

                                    </div>
                                    <div class="text-center dropdown-footer">
                                        <a class="btn btn-primary btn-sm btn-block text-center" href="chat.html">MARK
                                            ALL AS READ</a>
                                    </div>
                                </div>
                            </div> --}}
                            <!-- MESSAGE-BOX -->
                            <!-- SIDE-MENU -->
                            <div class="dropdown d-flex profile-1">
                                <a href="javascript:void(0)" data-bs-toggle="dropdown"
                                    class="nav-link leading-none d-flex">
                                    <img src="{{ url('/assets') }}/images/user-profile.png" alt="profile-user"
                                        class="avatar  profile-user brround cover-image">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-popper="none">
                                    <div class="drop-heading">
                                        <div class="text-center">
                                            <h5 class="text-dark mb-0 fw-semibold">{{ Auth::user()->name }}</h5>
                                            <span class="text-muted fs-12">{{ Auth::user()->getAkses->name }}</span>
                                            @if (Auth::user()->role_id == '4')
                                                <span class="text-muted fs-12">
                                                    {{ Auth::user()->penyuluh->kecamatan->name }}
                                                </span>
                                            @elseif (Auth::user()->role_id == '3')
                                                <span class="text-muted fs-12">
                                                    {{ Auth::user()->uptd->kecamatan->name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if (Auth::user()->role_id == '2')
                                        <a class="dropdown-item text-dark fw-semibold border-top"
                                            href="{{ url('/pertanian/pengaturan/editProfile') }}">
                                            <i class="dropdown-icon fe fe-user"></i> Profile Saya
                                        </a>
                                    @elseif(Auth::user()->role_id == '3')
                                        <a class="dropdown-item text-dark fw-semibold border-top"
                                            href="{{ url('/uptd/pengaturan/editProfile') }}">
                                            <i class="dropdown-icon fe fe-user"></i> Profile Saya
                                        </a>
                                    @elseif(Auth::user()->role_id == '4')
                                        <a class="dropdown-item text-dark fw-semibold border-top"
                                            href="{{ url('/penyuluh/pengaturan/editProfile') }}">
                                            <i class="dropdown-icon fe fe-user"></i> Profile Saya
                                        </a>
                                    @elseif(Auth::user()->role_id == '5')
                                        <a class="dropdown-item text-dark fw-semibold border-top"
                                            href="{{ url('/pangan/pengaturan/editProfile') }}">
                                            <i class="dropdown-icon fe fe-user"></i> Profile Saya
                                        </a>
                                    @endif
                                    {{-- <a class="dropdown-item text-dark fw-semibold" href="email-inbox.html">
                                        <i class="dropdown-icon fe fe-mail"></i> Inbox
                                        <span class="badge bg-success float-end">3</span>
                                    </a>
                                    <a class="dropdown-item text-dark fw-semibold" href="settings.html">
                                        <i class="dropdown-icon fe fe-settings"></i> Settings
                                    </a>
                                    <a class="dropdown-item text-dark fw-semibold" href="faq.html">
                                        <i class="dropdown-icon fe fe-alert-triangle"></i>
                                        Support ?
                                    </a> --}}
                                    <a class="dropdown-item text-dark fw-semibold"
                                        href="{{ route('web.auth.logout') }}">
                                        <i class="dropdown-icon fe fe-log-out"></i> Sign
                                        out
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
