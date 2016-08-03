@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Usuarios registrados
                    <a class="btn btn-success btn-xs pull-right" href="{{ url('/register') }}">
                        <i class="glyphicon glyphicon-plus"></i> Registrar usuario
                    </a>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Correo</th>
                            <th>Cargo</th>
                            <th>Estado de cuenta</th>
                            <th>Fecha de creacion</th>
                            <th>-</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $key=>$user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->first_name }}</td>
                                <td>{{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->user_type()->first()['name']}}</td>
                                <td>
                                    @if($user->status === 'Y')
                                        <p class="btn btn-xs btn-success">Activa</p>
                                    @else
                                        <p class="btn btn-xs btn-warning">Inactiva</p>
                                    @endif
                                </td>
                                <td>{{ $user->created_at }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-xs" title="Editar">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </a>
                                    <a class="btn btn-danger btn-xs" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="row-fluid">
                        <div class="span6">
                            <div class="dataTables_info" id="dynamic-table_info">Vista de 1 - {{ $users->count()}}
                                de {{ $users->total()}} registros
                            </div>
                        </div>
                        <div class="span6">
                            <div class="dataTables_paginate paging_bootstrap pagination">
                                <ul>
                                    <li class="prev disabled"><a href="{{ $users->previousPageUrl() }}">← Anterior</a>
                                    </li>
                                    @for ($i = 1; $i <= $users->lastPage(); $i++)
                                        <li class="{{ ($users->currentPage() == $i) ? ' active' : '' }}">
                                            <a href="{{ $users->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="next"><a href="{{$users->nextPageUrl()}}">Siguiente → </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
