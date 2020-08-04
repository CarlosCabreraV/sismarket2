<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Laravel') }}</title>
  <link href="https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/login.css">
</head>
<body>
  <main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-6">
            <img src="dist/logo_images/canasta.jpg" alt="login" class="login-card-img">
            <!--<img src="dist/logo_images/canasta.jpg" alt="login" class="login-card-img">-->
           <!-- <img src="dist/logo_images/mercado.jpg" alt="login" class="login-card-img"> -->
          </div>
          <div class="col-md-6">
            <div class="card-body ">
              <div class="brand-wrapper">
                <!--img src="dist/logo_images/logo.svg" alt="logo" class="logo"-->
              </div>
              <p class="login-card-description">Inicia sesión con tu cuenta</p>
              <form method="POST" id='formLogin' action="{{ route('login') }}">
                @csrf
                  <div class="form-group">
                    <label for="login" class="sr-only">Login</label>
                    <input type="text" name="login" id="login" class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}"  value="{{ old('login') }}" placeholder="Login" required autofocus>
                    @if ($errors->has('login'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('login') }}</strong>
                    </span>
                    @endif
                </div>
                  <div class="form-group mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="***********">
                    @if ($errors->has('password'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif  
                </div>
                  <input class="btn btn-block login-btn mb-4" id='loginbtn' type="button" value="Login" onclick="event.preventDefault(); document.getElementById('formLogin').submit();" >
                  @if (Route::has('password.request'))
                  <a class="forgot-password-link" href="{{ route('password.request') }}">
                      {{ __('Forgot Your Password?') }}
                  </a>
                @endif
                </form>
                
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </main>
  <style>
      .invalid-feedback{
        margin-top: -15px;
        padding-left: 10px;
      }
      .login-card .login-btn {
        background-color: #ff4600;
      }
      .login-card .login-btn:hover {
          border: 1px solid #ff4600;
          background-color: transparent;
          color: #ff510f;
      }
      .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 151, 0, 0.25);
      }
  </style>
  <script src="plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
 
</body>
</html>
