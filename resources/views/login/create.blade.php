<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem 0;
        }
        .register-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .register-header h2 {
            margin: 0;
            font-weight: 300;
        }
        .register-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .register-form {
            padding: 2rem;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-control {
            border: none;
            border-bottom: 2px solid #e9ecef;
            border-radius: 0;
            padding: 0.75rem 0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: none;
            border-bottom-color: #667eea;
        }
        .input-group {
            position: relative;
        }
        .input-group-text {
            background: none;
            border: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
        }
        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e9ecef;
        }
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 10px;
            border: none;
        }
        .password-requirements {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-container">
                    <div class="register-header">
                        <i class="fas fa-user-plus"></i>
                        <h2>Crear Cuenta</h2>
                    </div>
                    
                    <div class="register-form">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Nombre completo" 
                                           required>
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Correo electrónico" 
                                           required>
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           placeholder="Contraseña" 
                                           required>
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                <div class="password-requirements">
                                    Mínimo 8 caracteres
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           name="password_confirmation" 
                                           placeholder="Confirmar contraseña" 
                                           required>
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="terms" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Acepto los términos y condiciones
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-register">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Crear Cuenta
                                </button>
                            </div>
                        </form>

                        <div class="login-link">
                            <p>¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>