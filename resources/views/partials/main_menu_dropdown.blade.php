{{-- resources/views/partials/main_menu_dropdown.blade.php --}}
<ul class="dropdown-menu dropdown-menu-custom"> 
    <div class="menu-row-icons">
        <a class="menu-item-icon" href="#">
            <i class="fas fa-file-alt"></i>
            <span>Recepción</span>
        </a>
        <a class="menu-item-icon" href="#">
            <i class="fas fa-print"></i>
            <span>Digitalizar</span>
        </a>
        <a class="menu-item-icon" href="#">
            <i class="fas fa-calendar-alt"></i>
            <span>Complementar</span>
        </a>
        <div class="year-dropdown">
            <span>Año:</span>
            <select class="form-select form-select-sm" aria-label="Seleccionar año">
                <option value="2017" selected>2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
            </select>
        </div>
    </div>

    <div class="menu-row-options">
        <div class="option-group">
            <span>OPCIONES DE RECEPCIÓN</span>
            <span class="value">0</span>
        </div>
        <div class="option-group">
            <span>TABLERO</span>
            <span class="value">0</span>
        </div>
    </div>
</ul>