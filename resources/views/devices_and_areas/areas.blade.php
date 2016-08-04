@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="areas_model"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        $rootScope.areas_model = new ModelService.Areas();
                    });
                </script>
            @endsection
        </div>
    </div>
@endsection
