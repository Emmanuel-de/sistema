@extends('layouts.app')

@section('title', 'Dashboard Judicial - Tamaulipas')

@section('content')
<div class="container-fluid">
    <!-- Header con logo -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="judicial-logo">
                        <img src="{{ asset('images/sistem1.png') }}" alt="Poder Judicial Tamaulipas" class="img-fluid mb-3" style="max-width: 300px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Carpetas por Etapa: 2017</h5>
                </div>
                <div class="card-body">
                    <div class="stage-stats">
                        <div class="stage-item mb-2">
                            <span class="stage-number">(1)</span>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 90%"></div>
                            </div>
                            <span class="stage-label">INVESTIGACIÓN INICIAL</span>
                            <span class="stage-count">11</span>
                        </div>
                        <div class="stage-item mb-2">
                            <span class="stage-number">(2)</span>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 70%"></div>
                            </div>
                            <span class="stage-label">INVESTIGACIÓN COMPLEMENTARIA</span>
                            <span class="stage-count">8</span>
                        </div>
                        <div class="stage-item mb-2">
                            <span class="stage-number">(3)</span>
                            <span class="stage-label">INTERMEDIA</span>
                        </div>
                        <div class="stage-item mb-2">
                            <span class="stage-number">(4)</span>
                            <span class="stage-label">JUICIO</span>
                        </div>
                        <div class="stage-item mb-2">
                            <span class="stage-number">(5)</span>
                            <span class="stage-label">CONCLUIDA</span>
                        </div>
                        <div class="stage-item">
                            <span class="stage-number">(99)</span>
                            <span class="stage-label">NO DEFINIDA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos principales -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Carpetas por Año</h5>
                </div>
                <div class="card-body">
                    <canvas id="carpetasAnoChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>Audiencias de Control por Juez: 2017</h5>
                </div>
                <div class="card-body">
                    <canvas id="audienciasJuezChart" width="400" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stage-stats {
    font-size: 0.9rem;
}

.stage-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stage-number {
    font-weight: bold;
    color: #495057;
    min-width: 25px;
}

.stage-label {
    flex: 1;
    font-size: 0.85rem;
}

.stage-count {
    font-weight: bold;
    color: #495057;
    min-width: 30px;
    text-align: right;
}

.progress {
    flex: 0 0 100px;
    height: 6px;
}

.judicial-logo {
    padding: 20px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    font-weight: 600;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de Carpetas por Año
    const ctxAno = document.getElementById('carpetasAnoChart').getContext('2d');
    new Chart(ctxAno, {
        type: 'bar',
        data: {
            labels: ['2015', '2016', '2017'],
            datasets: [{
                data: [2, 15, 14],
                backgroundColor: ['#4472C4', '#4472C4', '#4472C4'],
                borderColor: ['#365899', '#365899', '#365899'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 16,
                    ticks: {
                        stepSize: 2
                    }
                }
            }
        }
    });

    // Gráfico de Audiencias por Juez
    const ctxJuez = document.getElementById('audienciasJuezChart').getContext('2d');
    new Chart(ctxJuez, {
        type: 'bar',
        data: {
            labels: ['SANTIAGO', 'CARLOS FAVIÁN'],
            datasets: [{
                data: [27, 2],
                backgroundColor: ['#4472C4', '#4472C4'],
                borderColor: ['#365899', '#365899'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            indexAxis: 'y',
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    max: 30,
                    ticks: {
                        stepSize: 5
                    }
                }
            }
        }
    });
});
</script>
@endsection