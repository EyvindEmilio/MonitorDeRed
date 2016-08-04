@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="devices"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        $rootScope.devices = new ModelService.Devices();
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
