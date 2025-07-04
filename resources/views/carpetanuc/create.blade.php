{{-- create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Overlay para el modal -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal-container">
            <!-- Header del modal -->
            <div class="modal-header">
                <h4 class="modal-title">ASIGNAR TRABAJO PARA CARPETA</h4>
                <button type="button" class="btn-close" onclick="closeModal()">
                    <span>&times;</span>
                </button>
            </div>

            <!-- Contenido del modal -->
            <div class="modal-body">
                <form id="assignWorkForm" method="POST" action="{{ route('carpeta.assign') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="document_type">Seleccione el tipo de documento:</label>
                        <select class="form-control" id="document_type" name="document_type" required>
                            <option value="">Seleccione un tipo</option>
                            <option value="contrato">Contrato</option>
                            <option value="acuerdo">Acuerdo</option>
                            <option value="dictamen">Dictamen</option>
                            <option value="informe">Informe</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="assigned_user">Seleccione el usuario que acordará:</label>
                        <select class="form-control" id="assigned_user" name="assigned_user" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="ordering_person">Persona que ordena el acuerdo:</label>
                        <input type="text" class="form-control" id="ordering_person" name="ordering_person" 
                               placeholder="Ingrese el nombre de la persona" required>
                    </div>

                    <div class="form-group">
                        <label for="document_description">Escriba una descripción del documento:</label>
                        <textarea class="form-control" id="document_description" name="document_description" 
                                  rows="5" placeholder="Describa el documento detalladamente..." required></textarea>
                    </div>
                </form>
            </div>

            <!-- Footer del modal con botones -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">
                    Cancelar
                </button>
                <button type="submit" form="assignWorkForm" class="btn btn-primary">
                    Asignar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para el modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1050;
}

.modal-container {
    background-color: #f5f5dc;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: hidden;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-header {
    background-color: #dc3545;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #dee2e6;
}

.modal-title {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
}

.btn-close {
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.btn-close:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.modal-body {
    padding: 25px;
    max-height: 60vh;
    overflow-y: auto;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
    background-color: white;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control::placeholder {
    color: #6c757d;
}

textarea.form-control {
    resize: vertical;
    min-height: 80px;
}

.modal-footer {
    padding: 15px 25px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    background-color: #f8f9fa;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-1px);
}

/* Responsividad */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        margin: 10px;
    }
    
    .modal-body {
        padding: 20px;
    }
    
    .modal-footer {
        flex-direction: column;
        gap: 8px;
    }
    
    .btn {
        width: 100%;
    }
}

/* Animación de cierre */
.modal-overlay.closing {
    animation: modalFadeOut 0.3s ease-in forwards;
}

@keyframes modalFadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
</style>

<script>
// Función para cerrar el modal
function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.classList.add('closing');
    
    setTimeout(() => {
        // Redireccionar o cerrar según tu lógica
        window.history.back(); // o window.close() si es popup
    }, 300);
}

// Cerrar modal al hacer clic en el overlay
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Prevenir cierre accidental del modal
document.querySelector('.modal-container').addEventListener('click', function(e) {
    e.stopPropagation();
});

// Manejar el envío del formulario
document.getElementById('assignWorkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar campos
    const requiredFields = ['document_type', 'assigned_user', 'ordering_person', 'document_description'];
    let isValid = true;
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.style.borderColor = '#dc3545';
            isValid = false;
        } else {
            element.style.borderColor = '#ced4da';
        }
    });
    
    if (isValid) {
        // Aquí puedes agregar una animación de loading
        const submitBtn = document.querySelector('.btn-primary');
        submitBtn.innerHTML = 'Asignando...';
        submitBtn.disabled = true;
        
        // Enviar formulario
        this.submit();
    } else {
        alert('Por favor, complete todos los campos obligatorios.');
    }
});

// Escape key para cerrar modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection