@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="device_types_model"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        @if(\App\User::isAdminCollaborator())
                                $rootScope.device_types_model = new ModelService.DeviceTypes();
                        @else
                                $rootScope.device_types_model = new ModelService.DeviceTypes({
                            editable: false,
                            add_new: false,
                            delete: false
                        });
                        @endif
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
