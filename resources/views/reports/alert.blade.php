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
        <td>Incidentes registrados en la red</td>
    </tr>
    <tr>
        <th class="left">Fecha:</th>
        <td>{{ strftime("%A %d de %B del %Y ") }}</td>
    </tr>
    <tr>
        <th class="left">En fechas:</th>
        <td>de {{ strftime("%A %d de %B del %Y ",(new DateTime($start_date))->getTimestamp()) }}
            a {{ strftime("%A %d de %B del %Y ",(new DateTime($end_date))->getTimestamp())  }}</td>
    </tr>
</table>

<div>
    <img src="{{ $graph64 }}" width="100%">
</div>
<br>
<table border="1" width="100%" cellspacing="0" cellpadding="0 10">
    <tr>
        <th>#</th>
        <th>Tipo de incidente</th>
        <th>Ip Origen</th>
        <th>Ip Destino</th>
        <th>Fecha</th>
    </tr>
    @foreach( $alerts_list as $index=>$alert)
        <tr>
            <td style="text-align: center">{{ $index + 1}}</td>
            <td>{{ $alert->type}}</td>
            <td>{{ $alert->ip_src}}</td>
            <td>{{ $alert->ip_dst}}</td>
            <td>{{ $alert->created_at}}</td>
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
</style>