<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistema Judicial Tamaulipas')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background-color:rgb(110, 20, 50) !important;
        }
        
        .navbar-brand {
            color: white !important;
            font-weight: bold;
        }
        
        .nav-tabs {
            background-color:rgb(48, 46, 47);
            border-bottom: none;
        }
        
        .nav-tabs .nav-link {
            color: white;
            border: none;
            border-radius: 0;
        }
        
        .nav-tabs .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            border-color: transparent;
        }
        
        .nav-tabs .nav-link.active {
            background-color: white;
            color:rgb(102, 100, 101);
            border-color: transparent;
        }
        
        .main-content {
            padding: 20px 0;
        }

        /* Estilos adicionales para el selector de años */
        .year-selector {
            border-top: 1px solid #dee2e6;
            padding-top: 8px;
            margin-top: 8px;
        }
        
        .year-selector .dropdown-header {
            color: #6c757d;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .year-item {
            cursor: pointer;
        }
        
        .year-item:hover {
            background-color: #f8f9fa;
        }
        
        .year-item.active {
            background-color: #e9ecef;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" width="30" height="30" class="d-inline-block align-text-top me-2">
                Poder Judicial Tamaulipas
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            INICIO
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('recepcion.index') }}">Recepción</a></li>
                            <li><a class="dropdown-item" href="{{ route('digitalizar.create') }}">Digitalizar</a></li>
                            <li><a class="dropdown-item" href="#">Complementar</a></li>
                            <li><a class="dropdown-item" href="#">TABLERO</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <h6 class="dropdown-header">OPCIONES DE RECEPCIÓN</h6>
                            <li><a class="dropdown-item" href="#">TABLERO</a></li>
                            <!-- Selector de Años -->
                            <li class="dropdown-item year-dropdown">
                            <div class="d-flex align-items-center">
                            <span class="me-2">Año:</span>
                            <select id="yearSelect" class="form-select form-select-sm" onchange="changeYear(this.value)">
                         <option value="2024">2024</option>
                         <option value="2023">2023</option>
                         <option value="2022">2022</option>
                         <option value="2021">2021</option>
                         <option value="2020">2020</option>
                         <option value="2019">2019</option>
                         <option value="2018">2018</option>
                         <option value="2017" selected>2017</option>
                         <option value="2016">2016</option>
                         <option value="2015">2015</option>
                       </select>
                      </div>
                    </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">SEGUIMIENTO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">CARPETA PRELIMINAR</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">CARPETA PROCESAL</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">AMPARO</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">ENFOTOS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">INFORMES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">CONFIGURACIÓN</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Usuario
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Configuración</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Salir</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" id="current-year-tab">AÑO: <span id="selected-year">2017</span></a>
        </li>
    </ul>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 main-content">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <script>
        // Variable global para el año actual
        let currentYear = 2017;
        
        /**
         * Función para cambiar el año seleccionado
         * @param {number} year - Año a seleccionar
         */
        function changeYear(year) {
            // Actualizar año actual
            currentYear = year;
            
            // Actualizar el texto del tab
            document.getElementById('selected-year').textContent = year;
            
            // Actualizar clase active en el menú
            document.querySelectorAll('.year-item').forEach(item => {
                item.classList.remove('active');
            });
            document.querySelector(`[data-year="${year}"]`).classList.add('active');
            
            // Cerrar el dropdown
            const dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
            if (dropdown) {
                dropdown.hide();
            }
            
            // Aquí puedes agregar la lógica para actualizar los datos
            // Por ejemplo:
            // updateDashboardData(year);
            
            console.log(`Año seleccionado: ${year}`);
            
            // Ejemplo de cómo podrías hacer una petición AJAX para actualizar los datos:
            // fetch(`/dashboard/data/${year}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         updateCharts(data);
            //         updateTables(data);
            //     })
            //     .catch(error => {
            //         console.error('Error al cargar datos:', error);
            //     });
        }
        
        /**
         * Función placeholder para actualizar gráficos
         * Esta función será implementada cuando tengas la lógica del backend
         */
        function updateCharts(data) {
            // Aquí actualizarías los gráficos con los nuevos datos
            console.log('Actualizando gráficos con datos:', data);
        }
        
        /**
         * Función placeholder para actualizar tablas
         * Esta función será implementada cuando tengas la lógica del backend
         */
        function updateTables(data) {
            // Aquí actualizarías las tablas con los nuevos datos
            console.log('Actualizando tablas con datos:', data);
        }
        
        // Inicialización cuando se carga la página
        document.addEventListener('DOMContentLoaded', function() {
            // Marcar el año actual como activo
            document.querySelector(`[data-year="${currentYear}"]`)?.classList.add('active');
        });
    </script>
    
    @stack('scripts')
</body>
</html>