@extends('layouts/contentLayoutMaster')

@section('title', 'Add New Designation')

@section('content')
    <section id="column-selectors">
        <div class="row">
            <div class="col-12">
                <button type="button" class="btn bg-gradient-success mr-1 mb-1" data-toggle="modal"
                        data-target="#designation">
                    Create New Designation
                </button>
                <br><br>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Designations Lists</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                <table class="table table-striped dataex-html5-selectors ">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Number of Employee</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($role as $index => $roles)
                                        <tr>
                                            <td scope="row">{{ $index+1 }}</td>
                                            <td>
                                                {{ $roles->role_name }}
                                            </td>
                                            <td>
                                                {{ $roles->users->count() }}
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#deleteModal{{ $roles->role_id }}">
                                                    <i class="feather icon-trash-2"></i>
                                                </button>


                                                {{--            Delete Model--}}
                                                <div class="modal fade text-left" id="deleteModal{{ $roles->role_id }}" tabindex="-1" role="dialog"
                                                     aria-labelledby="myModalLabel120" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger white">
                                                                <h5 class="modal-title" id="myModalLabel120">Delete {{ $roles->role_name }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body" style="font-size: 16px; font-weight: 600; text-align: center;">
                                                                @if($roles->users->count() > 0)
                                                                    <span class="text-danger">
                                                                        You can't Delete "{{ $roles->role_name }}" because It has staffs assigned in!
                                                                    </span>
                                                                @else
                                                                        Are you sure wanna delete designation "<strong>{{ $roles->role_name }}</strong>"
                                                                @endif
                                                            </div>
                                                            <div class="modal-footer">
                                                                <form action="{{ route('delete-designation') }}" method="post">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <input type="hidden" value="{{ $roles->role_id }}" name="id">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    @if(!$roles->users || $roles->users->count() <= 0)
                                                                        <button type="submit" class="btn btn-danger">Confirm</button>
                                                                    @endif
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Number of Employee</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


{{--            Add Designation Model--}}
            <div class="modal fade text-left" id="designation" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel33" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                        <form action="{{ route('add-designation') }}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h4 class="modal-title" id="myModalLabel33">Add New Designation </h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="desName" class="font-weight-bold">Designation Name</label>
                                    <input type="text" name="role_name" id="desName" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="desName" class="font-weight-bold">Designation Slug</label>
                                    <input type="text" name="role_slug" id="desName" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


