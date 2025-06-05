<div>
    <script>
        document.addEventListener('ProcedimientoCreado', (event) => {
            let data = event.detail;
            console.log(data);
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.type,
                confirmButtonText: 'Ok'
            });
        });

        document.addEventListener('ProcedimientoEliminado', (event) => {
            let data = event.detail;
            console.log(data);
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.type,
                confirmButtonText: 'Ok'
            });
        });

        document.addEventListener('ProcedimientoError', (event) => {
            let data = event.detail;
            console.log(data);
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.type,
                confirmButtonText: 'Ok'
            });
        });
    </script>

    <div>
        @if ($modalNuevo)
        {{-- MODAL --}}
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-modal="true" style="display: block; padding-left: 0px;">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">

                    <div class="modal-body">
                        <div class="container d-flex justify-content-center align-items-center"
                            style="min-height: 100vh;">
                            <div class="card p-4" style="width: 500%; max-width: 1000px;">
                                <h1 class="text-center mb-4">{{$estadoModal}}</h1>

                                <form wire:submit.prevent="crearProcedimiento">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha Inicio</label>
                                                <input type="datetime-local" wire:model="fecha_ini" class="form-control">
                                                @error('fecha_ini')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Fecha Final</label>
                                                <input type="datetime-local" wire:model="fecha_fin" class="form-control">
                                                @error('fecha_fin')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Duracion (horas)</label>
                                                <input type="text" wire:model="duracion" class="form-control">
                                                @error('duracion')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Edad (años)</label>
                                                <input type="number" wire:model="edad" class="form-control">
                                                @error('edad')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Paciente</label>
                                        <select class="form-control" wire:model="paciente_id" name="choices-button" id="choices-button" wire:change="datosSeleccion($event.target.value,'pacientes')">
                                            <option value="0" selected="">Seleccione un paciente</option>
                                            @foreach ($listaPacientes as $paciente)
                                            <option value="{{ $paciente->id }}">{{ $paciente->nombres }} {{ $paciente->apellidos }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label>Especialista</label>
                                        <select class="form-control" wire:model="especialista_id" name="choices-button" id="choices-button" wire:change="datosSeleccion($event.target.value,'especialistas')">
                                            <option value="0" selected="">Seleccione un especialista</option>
                                            @foreach ($listaEspecialistas as $esp)
                                            <option value="{{ $esp->id }}">{{ $esp->nombres }} {{ $esp->apellidos }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Dispositivo</label>
                                                <select class="form-control" wire:model="dispositivo_id" name="choices-button" id="choices-button" wire:change="datosSeleccion($event.target.value,'dispositivos')">
                                                    <option value="0" selected="">Seleccione un dispositivo</option>
                                                    @foreach ($listaDispositivos as $disp)
                                                    <option value="{{ $disp->id }}">{{ $disp->numero_serie}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <select class="form-control" wire:model="estado_proc" name="choices-button" id="choices-button">
                                                    <option value="0" selected="">Seleccione...</option>
                                                    <option value="ABIERTO">ABIERTO</option>
                                                    <option value="CERRADO">CERRADO</option>
                                                    <option value="CANCELADO">CANCELADO</option>
                                                </select>
                                                @error('estado_proc')
                                                <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label>Observaciones</label>
                                        <textarea class="form-control" wire:model="observaciones" cols="80" rows="3"></textarea>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn bg-gradient-secondary" wire:click="cerrar()" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn bg-gradient-primary">Guardar</button>
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

        <body class="g-sidenav-show bg-gray-100">
            @include('components.layouts.navbars.user.aside')
            <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
                <!-- Navbar -->
                <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
                    <div class="container-fluid py-1 px-3">

                        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                            <div class="ms-md-auto pe-md-3 d-flex align-items-center">

                            </div>
                            <ul class="navbar-nav justify-content-end">
                                <li class="nav-item d-flex align-items-center">
                                    <div class="input-group">
                                        <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Buscar procedimiento..."
                                            wire:model="search"
                                            wire:keydown.enter="buscarProcedimiento">
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4 mx-4">
                                <div class="card-header pb-0">
                                    <div class="d-flex flex-row justify-content-between">
                                        <div>
                                            <h5 class="mb-0">Procedimientos</h5>
                                        </div>
                                        <button type="button" wire:click="creacion" class="btn bg-gradient-primary">
                                            Nuevo Procedimiento
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        @if(count($listadoProcedimientos) > 0)
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        ID
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Paciente
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Edad
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Fecha Inicial
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Fecha Final
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Dispositivo
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Estado
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                //print_r($listadoProcedimientos);
                                                ?>
                                                @foreach ($listadoProcedimientos as $procedimiento)
                                                <tr>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $procedimiento->id }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $procedimiento->paciente->identificacion }} - {{ $procedimiento->paciente->nombres }} {{ $procedimiento->paciente->apellidos }} </p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $procedimiento->edad }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $procedimiento->fecha_ini }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $procedimiento->fecha_fin }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $procedimiento->dispositivo->numero_serie }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge
                                                            @if($procedimiento->estado_proc == 'ABIERTO') bg-warning text-dark
                                                            @elseif($procedimiento->estado_proc == 'CERRADO') bg-success
                                                            @elseif($procedimiento->estado_proc == 'CANCELADO') bg-danger
                                                            @endif">
                                                            {{ $procedimiento->estado_proc }}
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span>
                                                            <i class="cursor-pointer fas fa-trash text-danger"
                                                                wire:click="confirmarEliminar({{ $procedimiento->id }})"
                                                                data-bs-original-title="Eliminar"></i>
                                                        </span>
                                                        <a href="#" class="mx-3" data-bs-toggle="tooltip"
                                                            data-bs-original-title="Editar"
                                                            wire:click="editar({{ $procedimiento->id }})"
                                                            title="Row: {{$procedimiento->id}}">
                                                            <i class="fas fa-user-edit text-secondary"></i>
                                                        </a>
                                                        @if($procedimiento->estado_proc == 'ABIERTO')
                                                        <a href="#" class="mx-3" data-bs-toggle="tooltip"
                                                            data-bs-original-title="Masivo"
                                                            wire:click="registrosHolterExcel({{ $procedimiento->id }})"
                                                            title="Row: {{$procedimiento->id}}">
                                                            <i class="fas fa-file-excel text-secondary"></i>
                                                        </a>
                                                        @elseif($procedimiento->estado_proc == 'CERRADO')
                                                        <a href="{{ route('registros', $procedimiento->id) }}" class="mx-3"
                                                            title="Row: {{ $procedimiento->id }}">
                                                            <i class="fas fa-heartbeat text-secondary"></i>
                                                        </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @else
                                        <p>No se encontraron resultados.</p>
                                        @endif
                                        {{-- Modal para confirmar eliminación --}}
                                        {{-- @if ($modalDelete)
                                            <div class="modal fade show" id="exampleModalLive" tabindex="-1"
                                            aria-labelledby="exampleModalLiveLabel" style="display: block;"
                                            aria-modal="true" role="dialog">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLiveLabel">Eliminar
                                                procedimiento:
                                                <b class="text-danger">{{ $procedimientoEliminar->id }}</b>
                                        </h5>
                                    </div>
                                    <div class="modal-body">
                                        <p>¿Está seguro de eliminar este procedimiento? Esta operación
                                            <b>NO</b>
                                            se puede deshacer!
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            wire:click="cerrar" data-bs-dismiss="modal">Cerrar</button>
                                        <button type="button"
                                            wire:click="eliminar({{ $procedimientoEliminar->id }})"
                                            class="btn btn-danger">Eliminar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-backdrop fade show"></div>

                        @endif
                    </div> --}}
                    {{-- Modal para confirmar eliminación --}}

                    @if ($modalRegistros)
                    {{-- MODAL_REGISTROS --}}
                    <div class="modal-backdrop fade show"></div>
                    <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-modal="true" style="display: block; padding-left: 0px;">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <div class="container d-flex justify-content-center align-items-center"
                                        style="min-height: 100vh;">
                                        <div class="card p-4" style="width: 500%; max-width: 1000px;">
                                            <h1 class="text-center mb-4">{{$estadoModal}}</h1>

                                            <div class="card shadow-sm mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Detalles del Procedimiento</h5>
                                                    <div class="row">

                                                        @foreach($dataProcedimiento as $proc)
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Paciente:</strong> {{$proc->identificacion}} - {{$proc->nombres}} {{$proc->apellidos}}</p>
                                                            <p class="mb-1"><strong>Edad:</strong> {{$proc->edad}} años</p>
                                                            <p class="mb-1"><strong>Fecha Inicio:</strong> {{$proc->fecha_ini}}</p>
                                                            <p class="mb-1"><strong>Fecha Fin:</strong> {{$proc->fecha_fin}} aprox.</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Procedimiento No:</strong> 000{{$proc->id}}</p>
                                                            <p class="mb-1"><strong>Dispositivo:</strong> {{$proc->numero_serie}}</p>
                                                            <p class="mb-1"><strong>Duracion:</strong> {{$proc->duracion}} horas</p>
                                                            <p class="mb-1">
                                                                <strong>Estado:</strong>
                                                                <span class="badge
                                                                    @if($proc->estado_proc == 'ABIERTO') bg-warning text-dark
                                                                    @elseif($proc->estado_proc == 'CERRADO') bg-success
                                                                    @elseif($proc->estado_proc == 'CANCELADO') bg-danger
                                                                    @endif">{{$proc->estado_proc}}</span>
                                                            </p>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- startForm-->
                                            <form wire:submit.prevent="crearRegistrosHolter">
                                                <input type="hidden" name="procedimiento_id" value="{{$id}}">
                                                <div class="form-group">
                                                    <label>Hora</label>
                                                    <input type="time" wire:model="hora" class="form-control">
                                                    @error('hora')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>FC Min</label>
                                                            <input type="text" wire:model="fc_min" class="form-control">
                                                            @error('fc_min')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Hora FC Min</label>
                                                            <input type="time" wire:model="hora_fc_min" class="form-control">
                                                            @error('hora_fc_min')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>FC Max</label>
                                                            <input type="text" wire:model="fc_max" class="form-control">
                                                            @error('fc_max')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label>Hora FC Max</label>
                                                            <input type="time" wire:model="hora_fc_max" class="form-control">
                                                            @error('hora_fc_max')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>FC Medio</label>
                                                            <input type="text" wire:model="fc_medio" class="form-control">
                                                            @error('fc_medio')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Total Latidos</label>
                                                            <input type="text" wire:model="total_latidos" class="form-control">
                                                            @error('total_latidos')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Total Eventos Ventriculares</label>
                                                            <input type="text" wire:model="vent_total" class="form-control">
                                                            @error('vent_total')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Total Eventos Supraventriculares</label>
                                                            <input type="text" wire:model="supr_total" class="form-control">
                                                            @error('supr_total')
                                                            <span class="text-danger">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary" wire:click="cerrar()" data-bs-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn bg-gradient-primary">Registrar</button>
                                                </div>
                                            </form>
                                            <!-- endForm-->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- MODAL_REGISTROS --}}
                    @endif


                    @if ($modalRegistrosExcel)
                    {{-- MODAL_REGISTROS_EXCEL --}}
                    <div class="modal-backdrop fade show"></div>
                    <div class="modal fade show" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-modal="true" style="display: block; padding-left: 0px;">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">

                                <div class="modal-body">
                                    <div class="container d-flex justify-content-center align-items-center"
                                        style="min-height: 100vh;">
                                        <div class="card p-4" style="width: 500%; max-width: 1000px;">
                                            <h1 class="text-center mb-4">{{$estadoModal}}</h1>

                                            <div class="card shadow-sm mb-4">
                                                <div class="card-body">
                                                    <h5 class="card-title text-primary">Detalles del Procedimiento</h5>
                                                    <div class="row">

                                                        @foreach($dataProcedimiento as $proc)
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Paciente:</strong> {{$proc->identificacion}} - {{$proc->nombres}} {{$proc->apellidos}}</p>
                                                            <p class="mb-1"><strong>Edad:</strong> {{$proc->edad}} años</p>
                                                            <p class="mb-1"><strong>Fecha Inicio:</strong> {{$proc->fecha_ini}}</p>
                                                            <p class="mb-1"><strong>Fecha Fin:</strong> {{$proc->fecha_fin}} aprox.</p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>Procedimiento No:</strong> 000{{$proc->id}}</p>
                                                            <p class="mb-1"><strong>Dispositivo:</strong> {{$proc->numero_serie}}</p>
                                                            <p class="mb-1"><strong>Duracion:</strong> {{$proc->duracion}} horas</p>
                                                            <p class="mb-1">
                                                                <strong>Estado:</strong>
                                                                <span class="badge
                                                                    @if($proc->estado_proc == 'ABIERTO') bg-warning text-dark
                                                                    @elseif($proc->estado_proc == 'CERRADO') bg-success
                                                                    @elseif($proc->estado_proc == 'CANCELADO') bg-danger
                                                                    @endif">{{$proc->estado_proc}}</span>
                                                            </p>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- startForm-->
                                            <form wire:submit.prevent="crearRegistrosHolterExcel" enctype="multipart/form-data">
                                                <input type="hidden" name="procedimiento_id" value="{{ $id }}">

                                                <div class="form-group">
                                                    <label>Importar [xlsx, xls, csv]</label>
                                                    <input
                                                        type="file"
                                                        wire:model="csv_file"
                                                        class="form-control @error('csv_file') is-invalid @enderror"
                                                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                                        data-max-file-size="2M"
                                                        required>
                                                    @error('csv_file')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn bg-gradient-secondary" wire:click="cerrar()" data-bs-dismiss="modal">Cerrar</button>
                                                    @if(!$cerrarCaso)
                                                    <!-- Botón Importar -->
                                                    <button type="submit" class="btn bg-gradient-primary">Importar</button>
                                                    @else
                                                    <!-- Botón Cerrar Procedimiento -->
                                                    <a href="#" class="btn bg-gradient-danger" wire:click="cerrarProcedimiento({{ $id }})">
                                                        Cerrar Procedimiento
                                                    </a>
                                                    @endif
                                                </div>
                                            </form>
                                            <!-- endForm-->
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- MODAL_REGISTROS_EXCEL --}}
                    @endif
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