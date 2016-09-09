@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="devices"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        @if(\App\User::isAdminCollaborator())
                                $rootScope.devices = new ModelService.Devices();
                        @else
                                $rootScope.devices = new ModelService.Devices({
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
