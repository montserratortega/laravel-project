@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card-group mb-0">
          <div class="card p-4">
          <form class="form-horizontal was-validated" method="POST" action="">

              <div class="card-body">
              <h3 class="text-center bg-success">Compras - Ventas</h3>

              <div class="form-group mb-3">
                <span class="input-group-addon"><i class="icon-user"></i></span>
                <input type="text" value="" name="usuario" id="usuario" class="form-control" placeholder="Usuario">

              </div>
              <div class="form-group mb-4">
                <span class="input-group-addon"><i class="icon-lock"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password">

              </div>
              <div class="row">
                <div class="col-6">
                  <button type="submit" class="btn btn-success px-4"><i class="fa fa-sign-in fa-2x"></i> Iniciar sesión</button>
                </div>
              </div>
            </div>
          </form>
          </div>

        </div>
      </div>
    </div>
@endsection
