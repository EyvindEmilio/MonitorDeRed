@extends('layouts.base')

@section('body-content')
    <div class="login-body">
        <div class="login-content-logo hidden-xs hidden-sm">
            <div class="logo-emi"></div>
        </div>
        <div class="login-content-logo hidden-xs hidden-sm">
            <div class="logo-carrera"></div>
        </div>
        <div class="container" style="z-index: 1;position: relative">
            <br>
            <br>
            <h2 class="text-center">SISTEMA DE MONITOREO DE RED PARA LA GESTION DE SEGURIDAD EN REDES BASADO
                EN LA NORMA NB/ISO/IEC 18028-1</h2>
            <h4 class="text-center">Trabajo de grado de la Escuela Militar de Ingenieria en la Carrera de Imngenieria de
                Sistemas<br>
                Desarrollado por la Estudiante: Judy Adriana Quispe Geronimo</h4>
            <form class="form-signin" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <h2 class="form-signin-heading">INGRESO AL SISTEMMA</h2>
                <div class="login-wrap">
                    <div class="user-login-info">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}"
                               placeholder="Correo electronico">
                        <input id="password" type="password" class="form-control" name="password"
                               placeholder="Contrasena">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <label class="checkbox">
                        <input type="checkbox" name="remember"> Recordarme
                        <span class="pull-right">
                    {{--<a data-toggle="modal" href="#myModal"> Olvidaste tu contrasena?</a>
--}}
                </span>
                    </label>
                    <button class="btn btn-lg btn-login btn-block" type="submit">Ingresar</button>

                    {{--<div class="registration">
                        Don't have an account yet?
                        <a class="" href="{{ url('/register') }}">
                            Crear una cuenta
                        </a>
                    </div>--}}

                </div>

                <!-- Modal -->
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal"
                     class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                        aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Forgot Password ?</h4>
                            </div>
                            <div class="modal-body">
                                <p>Enter your e-mail address below to reset your password.</p>
                                <input type="text" name="emailReset" placeholder="Email" autocomplete="off"
                                       class="form-control placeholder-no-fix">

                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                <button class="btn btn-success" type="button">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal -->

            </form>

        </div>
    </div>


    {{--<div class="container">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-8 col-md-offset-2">--}}
    {{--<div class="panel panel-default">--}}
    {{--<div class="panel-heading">Login</div>--}}
    {{--<div class="panel-body">--}}
    {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">--}}
    {{--{{ csrf_field() }}--}}

    {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
    {{--<label for="email" class="col-md-4 control-label">E-Mail Address</label>--}}

    {{--<div class="col-md-6">--}}
    {{--<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">--}}

    {{--@if ($errors->has('email'))--}}
    {{--<span class="help-block">--}}
    {{--<strong>{{ $errors->first('email') }}</strong>--}}
    {{--</span>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
    {{--<label for="password" class="col-md-4 control-label">Password</label>--}}

    {{--<div class="col-md-6">--}}
    {{--<input id="password" type="password" class="form-control" name="password">--}}

    {{--@if ($errors->has('password'))--}}
    {{--<span class="help-block">--}}
    {{--<strong>{{ $errors->first('password') }}</strong>--}}
    {{--</span>--}}
    {{--@endif--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
    {{--<div class="col-md-6 col-md-offset-4">--}}
    {{--<div class="checkbox">--}}
    {{--<label>--}}
    {{--<input type="checkbox" name="remember"> Remember Me--}}
    {{--</label>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    {{--<div class="form-group">--}}
    {{--<div class="col-md-6 col-md-offset-4">--}}
    {{--<button type="submit" class="btn btn-primary">--}}
    {{--<i class="fa fa-btn fa-sign-in"></i> Login--}}
    {{--</button>--}}

    {{--<a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
@endsection
