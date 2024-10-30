<section class="no-print">
    <nav class="navbar navbar-default bg-white m-4">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                {{-- 
                    <a class="navbar-brand" href="{{action([\Modules\Accounting\Http\Controllers\AccountingController::class, 'dashboard'])}}"><i class="fas fa fa-broadcast-tower"></i>شئون العاملين </a>

                    --}}
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">

                    <li @if (request()->segment(2) == 'employee') class="active" @endif>
                        <a href="{{ action([\Modules\HRAdvanced\Http\Controllers\EmployeeController::class, 'index']) }}">الموظفين</a>
                    </li>
                    
                    

                    <li @if (request()->segment(2) == 'timesheets') class="active" @endif>
                        <a href="{{ action([\Modules\HRAdvanced\Http\Controllers\TimesheetController::class, 'index']) }}">رواتب</a>
                    </li>
                    <!-- Settings Dropdown Menu -->
                    <li class="dropdown @if(in_array(request()->segment(2), ['penalties', 'projects', 'jobs'])) active @endif">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            Settings <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li @if (request()->segment(2) == 'penalties') class="active" @endif>
                                <a href="{{ action([\Modules\HRAdvanced\Http\Controllers\PenaltyController::class, 'index']) }}">الجزاءات</a>
                            </li>
                            <li @if (request()->segment(2) == 'projects') class="active" @endif>
                                <a href="{{ action([\Modules\HRAdvanced\Http\Controllers\ProjectController::class, 'index']) }}">المشاريع</a>
                            </li>
                            <li @if (request()->segment(2) == 'jobs') class="active" @endif>
                                <a href="{{ action([\Modules\HRAdvanced\Http\Controllers\JobController::class, 'index']) }}">الوظائف</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

        </div><!-- /.container-fluid -->
    </nav>
</section>

@if (session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('erorr'))
    <div class="alert alert-danger" role="alert">
        {{ session('danger') }}
    </div>
@endif
