@extends('layouts/contentLayoutMaster')

@section('title', 'Manpower Details')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/pages/users.css')) }}">

    <link rel="stylesheet" href="{{ asset('css/fullcalendar/fullcalendar.min.css') }}">
@endsection

@section('content')
    <div id="user-profile">
        <div class="row">
            <div class="col-12">
                <div class="profile-header mb-2">
                    <div class="relative">
                        <div class="cover-container">
                            @if($labour->cover_image !== null)
                                <img class="img-fluid bg-cover rounded-0 w-100" src="{{ asset($labour->cover_image) }}"
                                     alt="{{ $labour->cover_image }}">
                            @else
                                <img class="img-fluid bg-cover rounded-0 w-100"
                                     src="{{ asset('images/profile/user-uploads/cover.jpg') }}"
                                     alt="User Profile Image">
                            @endif
                        </div>
                        <div class="profile-img-container d-flex align-items-center justify-content-between">
                            @if($labour->image !== null)
                                <img class="rounded-circle img-border box-shadow-1" src="{{ asset($labour->image) }}"
                                     alt="{{ $labour->image }}">
                            @else
                                <img class="img-fluid" src="{{asset('images/labour-image/man.png')}}" alt="None">
                            @endif
                            <div class="float-right">
                                <button id="lfm" type="button" data-input="thumbnail" data-preview="holder"
                                        class="btn btn-icon btn-icon rounded-circle btn-primary mr-1"
                                        name="project_image">
                                    <i class="feather icon-camera"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-items-center profile-header-nav">
                        <nav class="navbar navbar-expand-sm w-100 pr-0">
                            <button class="navbar-toggler pr-0" type="button" data-toggle="collapse"
                                    data-target="navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="Toggle navigation">
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
                            <p>{{ $labour->note }}</p>
                            <div class="mt-1">
                                <h6 class="mb-0">Name</h6>
                                <p>{{ $labour->name }}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">User Name</h6>
                                <p>{{ $labour->username }}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Joined:</h6>
                                <p>{{ \Carbon\Carbon::parse($labour->created_at)->format('d M Y') }}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Lives:</h6>
                                <p>{{$labour->address}}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Email:</h6>
                                <p>{{$labour->email}}</p>
                            </div>
                            <div class="mt-1">
                                <h6 class="mb-0">Mobile:</h6>
                                <p>{{\App\Helpers\Helper::mobileNumber($labour->mobile)}}</p>
                            </div>
                            <div class="mt-1">
                                <button type="button" class="btn btn-sm btn-icon btn-primary mr-25 p-25"><i
                                            class="feather icon-facebook"></i></button>
                                <button type="button" class="btn btn-sm btn-icon btn-primary mr-25 p-25"><i
                                            class="feather icon-twitter"></i></button>
                                <button type="button" class="btn btn-sm btn-icon btn-primary p-25"><i
                                            class="feather icon-instagram"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <h4 class="card-title">Pay Money</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('manpower-pay') }}" method="post" onsubmit="this.submit.disabled = true;">
                                    @csrf
                                    <div class="form-body">
                                        <div class="form-group">
                                            <label for="recipient-name">Date :<span style="color: red">*</span></label>
                                            <input type="date" class="form-control" id="recipient-name" name="date" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name">Amount: <span style="color: red">*</span></label>
                                            <input name="project_id" type="hidden" value="{{ $project->project_id }}">
                                            <input name="labour_id" type="hidden" value="{{ $labour->id }}">
                                            <input name="given_by" type="hidden" value="{{ \Illuminate\Support\Facades\Auth::id() }}">
                                            <input type="number" id="recipient-name" name="amount" class="form-control" required
                                                   data-validation-required-message="Expected Salary & min field must be at least 2 digit"
                                                   minlength="2" placeholder="Expected Salary" onblur="myCalculation()">
                                        </div>
                                        <div class="form-group">
                                            <label for="recipient-name">Note :<span style="color: red">*</span></label>
                                            <fieldset class="form-group">
                                                <textarea class="form-control" id="recipient-name" rows="3" placeholder="Note" name="note"></textarea>
                                            </fieldset>
                                        </div>
                                        <br>
                                        @php
                                            if(\Illuminate\Support\Facades\Auth::user()->isManager()) {
                                                    $income = \Illuminate\Support\Facades\Auth::user()->managerPayments()->whereIn('payment_purpose', ['employee_transfer', 'employee_refund'])->sum('payment_amount');
                                                }
                                        @endphp
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary mr-1">Pay</button>
                                        <button type="reset" class="btn btn-outline-warning">Cancel</button>
                                    </div>
                                </form>
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
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($labour->staffPayments as $index => $transaction)
                                            <tr>
                                                <th scope="row">{{ $index + 1 }}</th>
                                                <td>{{ $transaction->payment_date }}</td>
                                                <td>{{ number_format($transaction->payment_amount,2) }} tk</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Total Payable</h4>
                        </div>
                        <div class="card-header d-flex align-items-start pb-0">
                            <div>
                                <h2 class="text-bold-700 mb-0">৳ {{ number_format($payable,2) }}</h2>
                                <p>Total Payable : {{ $payable/100 }} %</p>
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
                                <h2 class="text-bold-700 mb-0">৳ {{ number_format($paid,2) }}</h2>
                                <p>Received : {{ $paid/100 }} %</p>
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
                                <h2 class="text-bold-700 mb-0" id="helloDue" >৳ {{ number_format($payable - $paid,2) }}</h2>
                                <p>Remaining Balance : {{  number_format((100 - (($paid/$payable) * 100)),2)  }} %</p>
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

                <br>
                <div class="row justify-content-center" align="center">
                    <div class="col-12" id='calendar'>
                        <div class="card comp-card">
                            <div class="card-body">
                                {!! $calendar->calendar() !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('page-script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
    {!! $calendar->script() !!}

    <script>
        let dueAmount = document.getElementById('helloDue').innerText;

        function myCalculation() {
            var valuess = document.getElementById('recipient-name');
            const a = valuess.value;
            console.log(a);
        }

        console.log(dueAmount);
    </script>

    {{-- Page js files --}}
    <script src="{{ asset(mix('js/scripts/pages/user-profile.js')) }}"></script>
@endsection
