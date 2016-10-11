<table style="width: 100%">
    <tr>
        <td width="20%" style="text-align: left"><img src="images/icono-emi.svg" width="70"></td>
        <td width="60%" style="text-align: center"><h4 style="margin-bottom: 0">SISTEMA DE MONITOREO DE RED</h4>
            <p style="margin-top: 0">Escuela Militar de Ingeniería - Departamento de Informática</p></td>
        <td width="20%" style="text-align: right"><img src="images/icono-emi.svg" width="70"></td>
    </tr>
</table>
<hr>
<table>
    <tr>
        <th class="left">Reporte:</th>
        <td>Usuarios registrados en el sistema</td>
    </tr>
    <tr>
        <th class="left">Fecha:</th>
        <td>{{ strftime("%A %d de %B del %Y ") }}</td>
    </tr>
</table>

<br>
<table border="1" width="100%" cellspacing="0" cellpadding="0 10" class="small">
    <tr>
        <th>#</th>
        <th width="38">Foto Perfil</th>
        <th>Nombre completo</th>
        <th>Email</th>
        <th>Tipo de usuario</th>
        <th>Estado</th>
        <th>Fecha de registro</th>
    </tr>
    @foreach( $user_list as $index=>$user)
        <tr>
            <td style="text-align: center">{{ $index + 1}}</td>

            <td style="margin: 0; padding: 0">
                @if($user->image)
                    <img src="images/users/{{$user->image}}" width="71">
                @else
                    --
                @endif
            </td>
            <td>{{ $user->first_name}} {{ $user->last_name}}</td>
            <td>{{ $user->email}}</td>
            <td>
                @if( $user->user_type == 1)
                    Administrador
                @elseif($user->user_type ==2)
                    Colaborador
                @else
                    Jefe
                @endif
            </td>
            <td>
                @if($user->status == 'Y')
                    Habilitado
                @else
                    Inhabilitado
                @endif
            </td>
            <td>{{ $user->created_at}}</td>
        </tr>
    @endforeach
</table>

<div id="footer">
    <hr>
    <p class="page" style="text-align: center">Página <?php $PAGE_NUM ?></p>
</div>


<style>
    @page {
        /*margin: 180px 50px;*/
    }

    #header {
        position: fixed;
        left: 0px;
        top: -180px;
        right: 0px;
        height: 150px;
        background-color: orange;
        text-align: center;
    }

    #footer {
        position: fixed;
        left: 0px;
        bottom: -130px;
        right: 0px;
        height: 150px;
        width: 100%;
        /*background-color: lightblue;*/
    }

    #footer .page:after {
        content: counter(page, upper-roman);
    }

    .left {
        text-align: left;
    }

    .small {
        font-size: 14px;
    }
</style>