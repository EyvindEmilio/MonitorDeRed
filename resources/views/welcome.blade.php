@extends('layouts.appMonitor')

@section('main-content')

@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor').run(function ($rootScope) {
            $rootScope.pcs = {!! $data !!} ;
        });
    </script>
@endsection

<table class="table table-bordered table-responsive">
    <tr ng-repeat="pc in pcs">
        <td>@{{ $index }}</td>
        <td>@{{ pc.mac }}</td>
        <td>@{{ pc.ip }}</td>
        <td>@{{ pc.manufacturer }}</td>
    </tr>
</table>
@endsection
