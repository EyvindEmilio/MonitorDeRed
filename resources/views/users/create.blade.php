@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Nuevo usuario</div>
                <div class="panel-body">
                    {!! Form::open([
                        'route' => 'auth.register'
                    ]) !!}

                    <div class="form-group">
                        {!! Form::label('first_name', 'Nombres:', ['class' => 'control-label']) !!}
                        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('last_name', 'Apellidos:', ['class' => 'control-label']) !!}
                        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('email', 'Correo electronico:', ['class' => 'control-label']) !!}
                        {!! Form::text('email', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('status', 'Estado de la cuenta:', ['class' => 'control-label']) !!}
                        {!! Form::checkbox('status', null, ['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('user_type', 'Tipo de usuario:', ['class' => 'control-label']) !!}
                        {!! Form::select('user_type', $users_types, null,['class' => 'form-control']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('password', 'Contrasena:', ['class' => 'control-label']) !!}
                        {!! Form::text('password', null,['class' => 'form-control']) !!}
                    </div>

                    {!! Form::submit('Registrar usuario', ['class' => 'btn btn-primary']) !!}

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
