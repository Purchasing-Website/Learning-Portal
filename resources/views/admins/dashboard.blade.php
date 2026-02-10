@extends('layouts.admin_app')

@section('content')
<div class="container" style="padding: 0px;">
    <div class="row" style="margin: 25px 0px;margin-top: 0px;">
        <div class="col-md-3">
            <div class="card shadow py-2 border-start-success">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-success mb-1 fw-bold text-xs"><span>Students</span></div>
                            <div class="text-dark mb-0 fw-bold h5"><span>&nbsp;215,000</span></div>
                        </div>
                        <div class="col-auto"><i class="far fa-user fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow py-2 border-start-primary">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary mb-1 fw-bold text-xs"><span>Program</span></div>
                            <div class="text-dark mb-0 fw-bold h5"><span>134</span></div>
                        </div>
                        <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" viewBox="-32 0 512 512" width="1em" height="1em" fill="currentColor" class="fa-2x text-gray-300" style="font-size: 32px;">
                                <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
                                <path d="M219.3 .5c3.1-.6 6.3-.6 9.4 0l200 40C439.9 42.7 448 52.6 448 64s-8.1 21.3-19.3 23.5L352 102.9V160c0 70.7-57.3 128-128 128s-128-57.3-128-128V102.9L48 93.3v65.1l15.7 78.4c.9 4.7-.3 9.6-3.3 13.3s-7.6 5.9-12.4 5.9H16c-4.8 0-9.3-2.1-12.4-5.9s-4.3-8.6-3.3-13.3L16 158.4V86.6C6.5 83.3 0 74.3 0 64C0 52.6 8.1 42.7 19.3 40.5l200-40zM111.9 327.7c10.5-3.4 21.8 .4 29.4 8.5l71 75.5c6.3 6.7 17 6.7 23.3 0l71-75.5c7.6-8.1 18.9-11.9 29.4-8.5C401 348.6 448 409.4 448 481.3c0 17-13.8 30.7-30.7 30.7H30.7C13.8 512 0 498.2 0 481.3c0-71.9 47-132.7 111.9-153.6z"></path>
                            </svg></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow py-2 border-start-warning">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-warning mb-1 fw-bold text-xs"><span>Course</span></div>
                            <div class="text-dark mb-0 fw-bold h5"><span>2</span></div>
                        </div>
                        <div class="col-auto"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="1em" height="1em" fill="currentColor" class="fa-2x text-gray-300" style="font-size: 38px;">
                                <!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License) Copyright 2023 Fonticons, Inc. -->
                                <path d="M160 96a96 96 0 1 1 192 0A96 96 0 1 1 160 96zm80 152V512l-48.4-24.2c-20.9-10.4-43.5-17-66.8-19.3l-96-9.6C12.5 457.2 0 443.5 0 427V224c0-17.7 14.3-32 32-32H62.3c63.6 0 125.6 19.6 177.7 56zm32 264V248c52.1-36.4 114.1-56 177.7-56H480c17.7 0 32 14.3 32 32V427c0 16.4-12.5 30.2-28.8 31.8l-96 9.6c-23.2 2.3-45.9 8.9-66.8 19.3L272 512z"></path>
                            </svg></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow py-2 border-start-warning">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-warning mb-1 fw-bold text-xs"><span style="color: var(--bs-red);">class</span></div>
                            <div class="text-dark mb-0 fw-bold h5"><span>1</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin: 0px;padding: 45px 0px;">
        <div class="col-md-6 justify-content-center align-items-center" style="margin: 0px;">
            <div id="chart-student-by-program" class="chart-area"><canvas data-bss-chart="{&quot;type&quot;:&quot;doughnut&quot;,&quot;data&quot;:{&quot;labels&quot;:[],&quot;datasets&quot;:[{&quot;label&quot;:&quot;Donut&quot;,&quot;backgroundColor&quot;:[],&quot;borderColor&quot;:[],&quot;data&quot;:[]}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:true,&quot;labels&quot;:{&quot;fontStyle&quot;:&quot;normal&quot;},&quot;position&quot;:&quot;right&quot;,&quot;reverse&quot;:false},&quot;title&quot;:{&quot;fontStyle&quot;:&quot;normal&quot;,&quot;display&quot;:true,&quot;text&quot;:&quot;Student By Program&quot;,&quot;fontSize&quot;:15}}}"></canvas></div>
        </div>
        <div class="col-md-6" style="margin: 0px;">
            <div id="chart-user-by-membership" class="chart-area"><canvas data-bss-chart="{&quot;type&quot;:&quot;pie&quot;,&quot;data&quot;:{&quot;labels&quot;:[],&quot;datasets&quot;:[{&quot;label&quot;:&quot;Donut&quot;,&quot;backgroundColor&quot;:[&quot;#00ffa3&quot;,&quot;#00ffff&quot;,&quot;rgb(158,0,255)&quot;],&quot;borderColor&quot;:[&quot;#ffffff&quot;,&quot;#ffffff&quot;,&quot;#ffffff&quot;],&quot;data&quot;:[&quot;50&quot;,&quot;30&quot;,&quot;40&quot;]}]},&quot;options&quot;:{&quot;maintainAspectRatio&quot;:false,&quot;legend&quot;:{&quot;display&quot;:true,&quot;labels&quot;:{&quot;fontStyle&quot;:&quot;normal&quot;},&quot;position&quot;:&quot;right&quot;,&quot;reverse&quot;:false},&quot;title&quot;:{&quot;fontStyle&quot;:&quot;normal&quot;,&quot;display&quot;:true,&quot;text&quot;:&quot;Student By Course&quot;,&quot;fontSize&quot;:15}}}"></canvas></div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script src="{{ asset('js/DashboardCharts.js') }}"></script>
@endpush