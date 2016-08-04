@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="device_types_model"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        $rootScope.device_types_model = new ModelService.DeviceTypes();
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
