<div>

    <script>
        document.addEventListener('DispositivoCreado', (event) => {
                let data = event.detail;
                console.log(data);
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.type,
                    confirmButtonText: 'Ok'
                })
            }),
            document.addEventListener('DispositivoEliminado', (event) => {
                let data = event.detail;
                console.log(data);
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.type,
                    confirmButtonText: 'Ok'
                })
            })
    </script>
    @if ($modal)
    {{-- MODAL --}}
    <div class="modal-backdrop fade show"></div>
    <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-modal="true" style="display: block; padding-left: 0px;">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">

                <div>
                    <div class="container d-flex justify-content-center align-items-center"
                        style="min-height: 100vh;">
                        <div class="card p-4" style="width: 100%; max-width: 1000px;">
                            <h1 class="text-center mb-4">{{ $estadoModal }}</h1>

                            <form wire:submit.prevent="crearDispositivos">
                                <div class="form-group">
                                    <label>Número de Serie</label>
                                    <input type="text" wire:model="numero_serie" class="form-control">
                                    <!-- Asegúrate de usar numero_serie -->
                                    @error('numero_serie')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- Asegúrate de usar numero_serie -->
                                </div>

                                <!-- Modelo -->
                                <div class="form-group">
                                    <label>Modelo</label>
                                    <input type="text" wire:model="modelo" class="form-control">
                                    <!-- Cambiado a 'modelo' -->
                                    @error('modelo')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- Cambiado a 'modelo' -->
                                </div>

                                <!-- Fabricante -->
                                <div class="form-group">
                                    <label>Fabricante</label>
                                    <input type="text" wire:model="fabricante" class="form-control">
                                    <!-- Agregar campo para 'fabricante' -->
                                    @error('fabricante')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror <!-- Manejo de errores para 'fabricante' -->
                                </div>



                                <!-- Estado -->
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select wire:model="estado" class="form-control">
                                        <option value="" default>Seleccione</option>
                                        <option value="Operativo">Operativo</option>
                                        <option value="Fuera de Servicio">Fuera de Servicio</option>
                                        <option value="En Reparación">En Reparación</option>
                                        <option value="En Uso">En Uso</option>
                                    </select>
                                    @error('estado')
                                    <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="modal-footer d-flex justify-content-end">
                                    <button type="button" class="btn bg-gradient-secondary" wire:click="cerrar()" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary ms-2">Guardar Dispositivo</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>
    {{-- MODAL --}}
    @endif


    <body class="g-sidenav-show  bg-gray-100">
        @include('components.layouts.navbars.admin.aside')
        <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
                <div class="container-fluid py-1 px-3">

                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                        </div>
                        <ul class="navbar-nav  justify-content-end">
                            <li class="nav-item d-flex align-items-center">
                                <div class="input-group">
                                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Buscar dispositivo..."
                                        wire:model="search"
                                        wire:keydown.enter="buscarDispositivo">
                                </div>
                            </li>
                            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                    <div class="sidenav-toggler-inner">
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                        <i class="sidenav-toggler-line"></i>
                                    </div>
                                </a>
                            </li>
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
                                        <h5 class="mb-0">Dispositivos</h5>
                                    </div>
                                    <button type="button" class="btn bg-gradient-primary" wire:click="creacion">
                                        Agregar nuevo dispositivo
                                    </button>
                                </div>
                            </div>
                            <div class="card-body px-0 pt-0 pb-2">
                                <div class="table-responsive p-0">
                                    @if(count($listadoDispositivos) > 0)
                                    <table class="table align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Número de Serie
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Modelo
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Fabricante
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Estado
                                                </th>
                                                <th
                                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Acciones
                                                </th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listadoDispositivos as $dispositivo)
                                            <tr>
                                                <td class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $dispositivo->numero_serie }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $dispositivo->modelo }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $dispositivo->fabricante }}
                                                    </p>
                                                </td>


                                                <td class="text-center">
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ $dispositivo->estado }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    <a href="#" class="mx-3" data-bs-toggle="tooltip"
                                                        data-bs-original-title="Editar"
                                                        wire:click="editar({{ $dispositivo->id }})">
                                                        <i class="fas fa-user-edit text-secondary"></i>
                                                    </a>
                                                    <span>
                                                        <i class="cursor-pointer fas fa-trash text-danger"
                                                            wire:click="confirmarEliminar({{ $dispositivo->id }})"
                                                            data-bs-original-title="Eliminar"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                    @else
                                    <p>No se encontraron resultados.</p>
                                    @endif
                                </div>
                            </div>

                            @if ($modalDelete)
                            <div class="modal fade show" id="exampleModalLive" tabindex="-1"
                                aria-labelledby="exampleModalLiveLabel" style="display: block;"
                                aria-modal="true" role="dialog">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLiveLabel">
                                                Eliminar
                                                dispositivo: <b class="text-danger">
                                                    {{ $dispositivoEliminar->numero_serie }}</b></h5>


                                        </div>
                                        <div class="modal-body">
                                            <p>¿Está seguro de eliminar este dispositivo? esta
                                                operación <b>NO</b>
                                                se puede
                                                deshacer! </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                wire:click="cerrar"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button"
                                                wire:click="eliminar({{ $dispositivoEliminar->id }})"
                                                class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-backdrop fade show"></div>
                            @endif

                        </div>
                    </div>
                </div>
        </main>

    </body>
</div>

</div>