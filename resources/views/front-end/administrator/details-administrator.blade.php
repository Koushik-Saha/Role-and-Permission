@extends('layouts/contentLayoutMaster')

@section('title', 'Administrator Details')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/pages/users.css')) }}">

@endsection
@section('content')
    <div id="user-profile">
        <div class="row">
            <div class="col-12">
                <div class="profile-header mb-2">
                    <div class="relative">
                        <div class="cover-container">
                            @if($client->cover_image !== null)
                                <img class="img-fluid bg-cover rounded-0 w-100" src="{{ asset($client->cover_image) }}" alt="{{ $client->cover_image }}">
                            @else
                                <img class="img-fluid bg-cover rounded-0 w-100" src="{{ asset('images/profile/user-uploads/cover.jpg') }}" alt="User Profile Image">
                            @endif
                        </div>
                        <div class="profile-img-container d-flex align-items-center justify-content-between">
                            @if($client->image !== null)
                                <img class="rounded-circle img-border box-shadow-1" src="{{ asset($client->image) }}" alt="{{ $client->image }}">
                            @else
                                <img class="img-fluid" src="{{asset('images/labour-image/man.png')}}" alt="None">
                            @endif
                            <div class="float-right">
                                <button id="lfm" type="button" data-input="thumbnail" data-preview="holder" class="btn btn-icon btn-icon rounded-circle btn-primary mr-1" name="project_image">
                                    <i class="feather icon-camera"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center profile-header-nav">
                        <nav class="navbar navbar-expand-sm w-100 pr-0">
                            <button class="navbar-toggler pr-0" type="button" data-toggle="collapse" data-target="navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"><i class="feather icon-align-justify"></i></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <ul class="navbar-nav justify-content-around w-75 ml-sm-auto">
                                    <li class="nav-item px-sm-0">
                                        <a href="#" class="nav-link font-small-3">Project</a>
                                    </li>
                                    <li class="nav-item px-sm-0">
                                        <a href="#" class="nav-link font-small-3">Payment</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <section id="profile-info">
            <div class="row">
                <div class="col-lg-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>About</h4>
                            <i class="feather icon-more-horizontal cursor-pointer"></i>
                        </div>
                        <div class="card-body">
                            <p>{{ $client->note }}</p>
                            <div class="mt-1">
                                <h6 class="mb-0">Name</h6>
                                <p>{{ $client->name }}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">User Name</h6>
                                <p>{{ $client->username }}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Joined:</h6>
                                <p>{{ \Carbon\Carbon::parse($client->created_at)->format('d M Y') }}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Lives:</h6>
                                <p>{{$client->address}}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Email:</h6>
                                <p>{{$client->email}}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Mobile:</h6>
                                <p>{{\App\Helpers\Helper::mobileNumber($client->mobile)}}</p>
                            </div>
                            <div class="mt-1">
                                <button type="button" class="btn btn-sm btn-icon btn-primary mr-25 p-25"><i class="feather icon-facebook"></i></button>
                                <button type="button" class="btn btn-sm btn-icon btn-primary mr-25 p-25"><i class="feather icon-twitter"></i></button>
                                <button type="button" class="btn btn-sm btn-icon btn-primary p-25"><i class="feather icon-instagram"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Transaction History</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-striped dataex-html5-selectors ">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Projects</th>
                                            <th>Payment Type</th>
                                            <th>Received Date</th>
                                            <th>Amount</th>
                                            <th>Note</th>
                                        </tr>
                                        </thead>
                                        <tbody>
{{--                                        @foreach($project as $index => $projects)--}}
{{--                                            <tr>--}}
{{--                                                <td scope="row">{{ $index+1 }}</td>--}}
{{--                                                <td>--}}
{{--                                                    <a href="{{ route('project-details', ['id' => $projects->project_id]) }}" title="See Project Details">--}}
{{--                                                        {{ $projects->project_name }}--}}
{{--                                                    </a>--}}
{{--                                                </td>--}}
{{--                                                <td>--}}
{{--                                                    {{ $projects->project_location }}--}}
{{--                                                </td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Projects</th>
                                            <th>Payment Type</th>
                                            <th>Received Date</th>
                                            <th>Amount</th>
                                            <th>Note</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12" >
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Payable</h4>
                        </div>
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ number_format(3000000,2) }}</h2>
                                <p>Total Payable : 30%</p>
                            </div>
                            <div class="avatar bg-rgba-primary p-50 m-0">
                                <div class="avatar-content">
                                    <i class="feather icon-cpu text-primary font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Received</h4>
                        </div>
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ number_format(3000000,2) }}</h2>
                                <p>Received : 30%</p>
                            </div>
                            <div class="avatar bg-rgba-success p-50 m-0">
                                <div class="avatar-content">
                                    <i class="feather icon-server text-success font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Remaining Balance</h4>
                        </div>
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">{{ number_format(3000000,2) }}</h2>
                                <p>Remaining Balance : 30%</p>
                            </div>
                            <div class="avatar bg-rgba-danger p-50 m-0">
                                <div class="avatar-content">
                                    <i class="feather icon-activity text-danger font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Project</h4>
                        </div>
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">2</h2>
                                <p>Total Project : 1</p>
                            </div>
                            <div class="avatar bg-rgba-warning p-50 m-0">
                                <div class="avatar-content">
                                    <i class="feather icon-alert-octagon text-warning font-medium-5"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('page-script')
    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/user-profile.js')) }}"></script>
@endsection
