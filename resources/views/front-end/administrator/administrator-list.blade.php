@extends('layouts/contentLayoutMaster')

@section('title', 'Administrator Lists')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Administrator Lists</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Role</th>
                                        <th>Name</th>
                                        <th>Father's Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Salary</th>
                                        <th>Address</th>
                                        <th>Note</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user as $index => $users)
                                        <tr>
                                            <td scope="row">{{ $index+1 }}</td>
                                            <td>
                                                <span class="badge badge-primary badge-lg mr-1 mb-1">{{ $users->role_name }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('administrator-details', ['id' => $users->id]) }}" title="See Administrator Details">
                                                    {{ $users->name }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $users->fathers_name }}
                                            </td>
                                            <td>
                                                {{ $users->username }}
                                            </td>
                                            <td>
                                                {{ $users->email }}
                                            </td>
                                            <td>
                                                {{ \App\Helpers\Helper::mobileNumber($users->mobile) }}
                                            </td>
                                            <td>
                                                {{ $users->salary }}
                                            </td>
                                            <td>
                                                {{ $users->address }}
                                            </td>
                                            <td>
                                                {{ $users->note }}
                                            </td>
                                            <td>
                                                <div class="avatar mr-1 avatar-xl">
                                                    <img src="{{ asset($users->image) }}" alt="avtar img holder">
                                                    @if($users->status != 1)
                                                        <span class="avatar-status-busy"></span>
                                                    @else
                                                        <span class="avatar-status-online"></span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td >
                                                <a type="button" class="btn btn-outline-success" title="Edit Client" href="{{ route('administrator-edit', ['id' => $users->id]) }}">
                                                    <i class="feather icon-edit-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Father's Name</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Salary</th>
                                        <th>Address</th>
                                        <th>Note</th>
                                        <th>Image</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
