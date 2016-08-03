@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="users_model"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        $rootScope.users_model = new ModelService.Users();
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
