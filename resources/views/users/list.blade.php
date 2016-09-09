@extends('layouts.appMonitor')

@section('main-content')
    <div class="row">
        <div class="col-sm-12">

            <div crud-directive="users_model"></div>

            @section('angular-scripts')
                <script type="text/javascript">
                    angular.module('Monitor').run(function ($rootScope, ModelService) {
                        @if(\App\User::isAdmin())
                                $rootScope.users_model = new ModelService.Users();
                        @else
                                $rootScope.users_model = new ModelService.Users({
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
