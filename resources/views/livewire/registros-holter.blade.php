<div>
    <script>
        document.addEventListener('SweetAlertVista', (event) => {
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
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #modalContent,
            #modalContent * {
                visibility: visible;
            }

            #modalContent {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }
        }
    </style>

    <div>
        <!-- StartModal -->
        <!-- EndModal -->

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
                                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                        <div class="sidenav-toggler-inner">
                                            <i class="sidenav-toggler-line">z</i>
                                            <i class="sidenav-toggler-line">x</i>
                                            <i class="sidenav-toggler-line">y</i>
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
                                    <div>
                                        <h2 class="mb-0">Registros Holter No. 000{{$procedimiento_id}}</h2>
                                    </div>
                                    <div class="align-middle text-right">
                                        <button class="btn btn-primary" wire:click="graficar({{$procedimiento_id}})" onclick="loadGrafico()">Generar Reporte</button>
                                    </div>
                                    <div class="d-flex flex-row justify-content-between">
                                        @foreach($data as $proc)
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

                                <div class="card-body px-0 pt-0 pb-2">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        ID
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Hora
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        FC Min
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        FC Max
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        FC Medio
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Total Latidos
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Eventos Ventriculares
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Eventos Supraventriculares
                                                    </th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                        Action
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($registros as $reg)
                                                <tr>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->id }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->hora }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->fc_min }} - {{ $reg->hora_fc_min }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->fc_max }} - {{ $reg->hora_fc_max }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->fc_medio }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->total_latidos }}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->vent_total}}</p>
                                                    </td>
                                                    <td class="text-center">
                                                        <p class="text-xs font-weight-bold mb-0">{{ $reg->supr_total}}</p>
                                                    </td>

                                                    <td class="text-center">
                                                        -
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($modalReport)
                    {{-- MODAL_REPORTE --}}
                    <div class="modal-backdrop fade show"></div>
                    <div class="modal fade show" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                        aria-modal="true" style="display: block; padding-left: 0px;">
                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                            <div class="modal-content">

                                <div class="modal-body" id="modalContent">
                                    <div class="container d-flex justify-content-center align-items-center"
                                        style="min-height: 100vh;">
                                        <div class="card p-4" style="width: 500%; max-width: 1000px;">
                                            <h1 class="text-center mb-4">{{$estadoModal}}</h1>

                                            <div class="card shadow-sm mb-4">
                                                <div class="card-body">
                                                    <h3 class="card-title text-primary">Información del Paciente</h3>
                                                    <div class="row">

                                                        @foreach($data as $proc)
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

                                            <div class="card shadow-sm mb-4">
                                                <div class="card-body">
                                                    <h3 class="card-title text-primary">Detalles del Holter</h3>
                                                    <div class="row">

                                                        @foreach($data as $proc)
                                                        <div class="col-md-6">
                                                            <p class="mb-1"><strong>FC Mín:</strong> {{$fc_min}} @<strong> Hora FC Mín:</strong> {{$hora_fc_min}}</p>
                                                            <p class="mb-1"><strong>FC Máx:</strong> {{$fc_max}} @<strong> Hora FC Máx:</strong> {{$hora_fc_max}}</p>
                                                            <p class="mb-1"><strong>Número Total De Latidos</strong> {{$total_latidos}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <p class="mb-1"><strong># Ventriculares:</strong> {{$vent_total}}</p>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <p class="mb-1"><strong># Supra-Ventriculares</strong> {{$supr_total}}</p>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- startForm-->
                                            <div>
                                                <div id="grafico-container" data-datos-grafico="{{ $datosGrafico }}"></div>
                                                <div class="card shadow-sm mb-4">
                                                    <h3>Gráfico de Frecuencia Cardíaca</h3>
                                                    <canvas id="graficoFc" width="800" height="400"></canvas>
                                                </div>

                                                <div class="card shadow-sm mb-4">
                                                    <h3>Gráfico de Número de Latidos</h3>
                                                    <canvas id="graficoLatidos" width="800" height="400"></canvas>
                                                </div>

                                                <div class="card shadow-sm mb-4">
                                                    <h3>Gráfico de Eventos</h3>
                                                    <canvas id="graficoEventos" width="800" height="400"></canvas>
                                                </div>
                                            </div>

                                            <div class="card shadow-sm mb-4">
                                                <div class="card-body">
                                                    <h3 class="card-title text-primary text-center">Profesional</h3>
                                                    <div class="row text-center">

                                                        @foreach($data as $esp)
                                                        <div class="col-md-12">
                                                            <p class="mb-1"><strong>Nombres: </strong> {{$esp->fname_esp}} {{$esp->lname_esp}}</p>
                                                            <p class="mb-1"><strong>Identificación/Rm:</strong> {{$esp->id_esp}}</p>
                                                            <p class="mb-1"><strong>Especialidad</strong> {{$esp->espp}}</p>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- endForm-->
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn bg-gradient-secondary" wire:click="cerrar()" data-bs-dismiss="modal">Cerrar</button>
                                    <button class="btn bg-gradient-success" onclick="generatePDF()">Generar PDF</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- MODAL_REPORTE --}}
                    @endif
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
                    <script>
                        function loadGrafico() {
                            setTimeout(function() {

                                // Obtener los datos del atributo data-*
                                const graficoContainer = document.getElementById('grafico-container');
                                if (!graficoContainer) {
                                    console.error('El contenedor del gráfico no existe');
                                    return;
                                }

                                const datosGrafico = JSON.parse(graficoContainer.dataset.datosGrafico);

                                // Extraer etiquetas (horas) y valores (fc_min, fc_max, fc_medio)
                                const etiquetas = datosGrafico.map(item => item.hora);
                                const valoresMin = datosGrafico.map(item => item.fc_min);
                                const valoresMax = datosGrafico.map(item => item.fc_max);
                                const valoresMedio = datosGrafico.map(item => item.fc_medio);
                                const totalLatidos = datosGrafico.map(item => item.total_latidos);
                                const totalVent = datosGrafico.map(item => item.vent_total);
                                const totalSupr = datosGrafico.map(item => item.supr_total);


                                const ctx = document.getElementById('graficoFc').getContext('2d');
                                const cty = document.getElementById('graficoLatidos').getContext('2d');
                                const ctz = document.getElementById('graficoEventos').getContext('2d');
                                new Chart(ctx, {
                                    type: 'bar',
                                    data: {
                                        labels: etiquetas,
                                        datasets: [{
                                                label: 'Frecuencia Cardíaca Mínima',
                                                data: valoresMin,
                                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                borderWidth: 1
                                            },
                                            {
                                                label: 'Frecuencia Cardíaca Máxima',
                                                data: valoresMax,
                                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                borderWidth: 1
                                            },
                                            {
                                                label: 'Frecuencia Cardíaca Media',
                                                data: valoresMedio,
                                                backgroundColor: 'rgba(255, 206, 86, 0.6)',
                                                borderColor: 'rgba(255, 206, 86, 1)',
                                                borderWidth: 1
                                            }
                                        ]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Frecuencia Cardíaca'
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Hora del Estudio'
                                                }
                                            }
                                        },
                                        responsive: true,
                                    }
                                });

                                new Chart(cty, {
                                    type: 'line',
                                    data: {
                                        labels: etiquetas,
                                        datasets: [{
                                            label: 'Total Latidos',
                                            data: totalLatidos,
                                            fill: false,
                                            backgroundColor: 'rgba(255, 99, 132)',
                                            borderColor: 'rgba(17, 217, 50)',
                                            tension: 0.4
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Número de Latidos'
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Hora del Estudio'
                                                }
                                            }
                                        },
                                        responsive: true,
                                    }
                                });

                                new Chart(ctz, {
                                    type: 'bar',
                                    data: {
                                        labels: etiquetas,
                                        datasets: [{
                                                label: 'Total Eventos Ventriculares',
                                                data: totalVent,
                                                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                                                borderColor: 'rgba(75, 192, 192, 1)',
                                                borderWidth: 1
                                            },
                                            {
                                                label: 'Total Eventos Supra-Ventriculares',
                                                data: totalSupr,
                                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                                borderColor: 'rgba(255, 99, 132, 1)',
                                                borderWidth: 1
                                            },
                                        ]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Total Eventos Durante el Estudio'
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Hora del Estudio'
                                                }
                                            }
                                        },
                                        responsive: true,
                                    }
                                });
                            }, 2000);
                        }

                        /*function printModalContent() {
                            const printArea = document.getElementById('modalContent').cloneNode(true);
                            const originalContent = document.body.innerHTML;
                            document.body.innerHTML = '';
                            document.body.appendChild(printArea);
                            window.print();
                            document.body.innerHTML = originalContent;
                        }*/
                        async function generatePDF() {
                            const {
                                jsPDF
                            } = window.jspdf;
                            const pdf = new jsPDF('p', 'mm', 'a4');
                            const modalContent = document.getElementById('modalContent');
                            const contentHeight = modalContent.offsetHeight;
                            const pageHeight = pdf.internal.pageSize.getHeight();
                            const totalPages = 1; //Math.ceil(contentHeight / pageHeight);

                            for (let page = 0; page < totalPages; page++) {
                                await html2canvas(modalContent, {
                                    scale: 2,
                                    scrollY: -window.scrollY,
                                    y: -page * pageHeight
                                }).then(canvas => {
                                    const imgData = canvas.toDataURL('image/png');
                                    pdf.addImage(imgData, 'PNG', 0, 0, pdf.internal.pageSize.getWidth(), pageHeight);

                                    if (page < totalPages - 1) {
                                        pdf.addPage();
                                    }
                                });
                            }

                            pdf.save('informe-holter.pdf');
                        }
                    </script>
            </main>
        </body>
    </div>
</div>