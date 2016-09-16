@extends('layouts.appMonitor')

@section('main-content')
    <div class="col-md-12">
        <section class="panel">
            <div crud-directive="model_alerts"></div>
        </section>
    </div>

@endsection
@section('angular-scripts')
    <script type="text/javascript">
        angular.module('Monitor').run(function ($rootScope, $API, $resource, $http, $interval, toastr, ModelService) {
            $rootScope.data_capture = {};
            $rootScope.model_alerts = new ModelService.Logs();
        });
    </script>
@endsection
