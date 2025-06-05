<div>

    <body class="g-sidenav-show  bg-gray-100">
        @if($role=== 'admin')
        @include('components.layouts.navbars.admin.aside')
        @endif
        @if($role=== 'user')
        @include('components.layouts.navbars.user.aside')
        @endif
        @if($role=== 'cliente')
        @include('components.layouts.navbars.cliente.aside')
        @endif
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
                <div class="container-fluid py-1 px-3">

                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                        </div>
                        <ul class="navbar-nav  justify-content-end">
                            {{-- NOTIFICACIONES  --}}
                            <livewire:nav-logout />
                            {{-- FIN NOTIFICACIONES  --}}
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- End Navbar -->
            <div class="container-fluid py-4">

                <div class="row my-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header pb-0">
                                <div class="d-flex flex-row justify-content-between">
                                    <div>
                                        <h5 class="mb-0">Dashboard</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">

                                <div class="container-fluid py-4">
                                    <div class="row my-4">
                                        <div class="col-12">
                                            <div class="row">

                                                <!-- Card Admin: Costos -->
                                                @if($role=== 'admin')
                                                <!-- Card Admin: Pacientes -->
                                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <a href="{{ route('pacientes') }}">
                                                                <i class="fas fa-user-md fa-3x mb-3"></i>
                                                                <h5 class="card-title">Pacientes</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Card Admin: Especialistas -->
                                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <a href="{{ route('especialistas') }}">
                                                                <i class="fas fa-user-nurse fa-3x mb-3"></i>
                                                                <h5 class="card-title">Especialistas</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Card Admin: Dispositivos -->
                                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <a href="{{ route('dispositivos') }}">
                                                                <i class="fas fa-laptop-medical fa-3x mb-3"></i>
                                                                <h5 class="card-title">Dispositivos</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($role=== 'user')
                                                <!-- Card Admin/User: Procedimientos -->
                                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <a href="{{ route('procedimientos') }}">
                                                                <i class="fas fa-procedures fa-3x mb-3"></i>
                                                                <h5 class="card-title">Procedimientos</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if($role=== 'cliente')
                                                <!-- Card Admin/User: Procedimientos -->
                                                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                                                    <div class="card">
                                                        <div class="card-body text-center">
                                                            <a href="{{ route('reportes') }}">
                                                                <i class="fas fa-procedures fa-3x mb-3"></i>
                                                                <h5 class="card-title">Reportes</h5>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
        </main>

    </body>
</div>

</div>