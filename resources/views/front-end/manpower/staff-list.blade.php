@extends('layouts/contentLayoutMaster')

@section('title', 'Staff List')

@section('content')
    <!-- Content types section start -->
    <section id="content-types">
        <div class="row">
            <div class="col-md-4 ">
                <div class="card comp-card">
                    <div class="card-body">
                        <form action="{{ route('manpower-search_staff') }}" method="post" id="labSearch">
                            @csrf
                            <div class="form-body">
                                <div class="form-group">
                                    <button type="button" class="btn mb-1 btn-outline-primary btn-icon btn-lg btn-block btn-sm" style="color: green">See Labour List</button>
                                    <label for="pid">Project: </label>
                                    <br>
                                    <select class="select2 form-control" id="pid"
                                            name="shift_project_id">
                                        <option selected disabled>Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 text-center btn-block">
                                        See List
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4 ">
                <div class="card comp-card">
                    <div class="card-body">
                        <form action="{{ route('manpower-staff-attendance') }}" method="post" id="labAttendence">
                            @csrf
                            <div class="form-body">
                                <div class="form-group">
                                    <button type="button" class="btn mb-1 btn-outline-primary btn-icon btn-lg btn-block btn-sm" style="color: green">Take Staff Attendance</button>
                                    <label for="pid">Project: </label>
                                    <br>
                                    <select class="select2 form-control" name="pid1" id="pid1">
                                        <option selected disabled>Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 text-center btn-block">
                                        Take Attendance
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4 ">
                <div class="card comp-card">
                    <div class="card-body">
                        <form action="{{ route('manpower-monthly') }}" method="post" id="salaryReportForm">
                            @csrf
                            <div class="form-body">
                                <div class="form-group">
                                    <button type="button" class="btn mb-1 btn-outline-primary btn-icon btn-lg btn-block btn-sm" style="color: green">Staff Monthly Salary Report</button>
                                    <label for="pids">Project: </label>
                                    <br>
                                    <select class="select2 form-control" name="pid" id="pids">
                                        <option selected disabled>Select Project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <br>
                                <div class="form-group text-center">
                                    <label for="month" class="btn mb-1 btn-outline-primary btn-icon btn-block btn-sm" style="color: green">Select Month</label>
                                    <input type="month" name="month" id="month" class="form-control " value="{{ date('Y-m') }}" required>
                                </div>
                                <br>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary mr-1 mb-1 text-center btn-block">
                                        See Report
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <div id="ajaxResult">
            <div class="row" align="center">
                <div class="col-md-12">
                    <div class="card proj-t-card">
                        <div class="card-body">
                            <div class="row align-items-center m-b-30">
                                <div class="col-auto" style="font-size: 24px; color: red">
                                    <i class="fa fa-search fa-xs"></i>
                                </div>
                                <div class="col p-l-0">
                                    <h2 class="m-b-5">Your Search Result Will Appear Here!</h2>
                                    <h6 class="badge badge-pill badge-danger">Live Update</h6>
                                </div>
                                <div class="col-auto" style="font-size: 24px; color: red">
                                    <i class="fa fa-users fa-xs"></i>
                                </div>
                            </div>
                            <div class="row align-items-center text-center">
                                <div class="col">
                                    <h4 class="m-b-0"><label class="badge badge-pill badge-success badge-lg mr-1 mb-1">Projects</label> </h4></div>
                                <div class="col"><i class="fa fa-exchange fa-sm"></i></div>
                                <div class="col">
                                    <h4 class="m-b-0"><label class="badge badge-pill badge-success badge-lg mr-1 mb-1">workers </label></h4></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Content types section end -->


@endsection

@section('page-script')

    <script type="text/javascript">

        // Staff List
        $(document).ready(function() {
            $("#labSearch").submit(function(e) {
                e.preventDefault();
                let pid=$("#pid").val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('manpower-search_staff') }}",
                    type : "POST",
                    data : {pid:pid},
                    success : function(response){
                        $("#ajaxResult").html(response);

                    },
                    error : function(xhr, status){
                    }
                });
            });
        });

        // ajax request for attendance
        $(document).ready(function () {
            $("#labAttendence").submit(function (e) {
                e.preventDefault();
                let pid = $("#pid1").val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: "{{ route('manpower-search-attendance') }}",
                    type: "POST",
                    data: {pid: pid},
                    success: function (response) {
                        $("#ajaxResult").html(response);
                        // alert(response);
                        // console.log(response);
                    },

                    error: function (xhr, status) {
                        alert('There is some error.Try after some time.');
                    }
                });
            });
        });

        //salary report
        $(document).ready(function() {
            $("#salaryReportForm").on('submit', function(e) {
                e.preventDefault();
                let pid = $("#pids").val();
                let month = $('#month').val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('manpower-salary-report') }}",
                    type : "POST",
                    data : { pid: pid, month: month },
                    success: function(response) {
                        console.log(response);
                        $("#ajaxResult").html(response);
                    },

                    error: function(xhr, status) {
                        // console.log(xhr, status);
                    }
                });
            });
        });
    </script>

@endsection