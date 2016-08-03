@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Tipos de usuarios</div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tipo usuario</th>
                            <th>Description</th>
                            <th>Numero de usuarios</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users_types as $key=>$users_type)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $users_type->name}}</td>
                                <td>{{ $users_type->description}}</td>
                                <td>{{ \App\User::where('user_type',$users_type->id)->count() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
