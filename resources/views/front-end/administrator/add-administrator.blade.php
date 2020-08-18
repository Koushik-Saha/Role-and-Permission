@extends('layouts/contentLayoutMaster')

@section('title', 'Add New Administrator')

@section('content')
    <section class="input-validation">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Create New Administrator</h4>
                    </div>
                    <form class="form form-vertical" novalidate action="{{route('add-administrator')}}"
                          method="post"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form-horizontal">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="role_id">Role: </label>
                                                <select class="select2 form-control" id="role_id"
                                                        name="role_id">
                                                    <option selected disabled>Select Role</option>
                                                    @foreach($role as $roles)
                                                        <option value="{{ $roles->role_id }}">{{ $roles->role_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Administrator Name:</label>
                                                <div class="controls">
                                                    <input type="text" name="name" class="form-control" required
                                                           data-validation-required-message="Client Full Name & only contain alphabetic characters. "
                                                           placeholder="Enter Client Full Name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Administrator Father's Name:</label>
                                                <div class="controls">
                                                    <input type="text" name="fathers_name" class="form-control" required
                                                           data-validation-required-message="Client Father's Name & only contain alphabetic characters. "
                                                           placeholder="Enter Client Father's Name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Email Address</label>
                                                <div class="controls">
                                                    <input type="email" name="email" class="form-control" required
                                                           data-validation-required-message="Must be a valid email"
                                                           placeholder="Email" >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <div class="controls">
                                                    <input type="password" name="password" class="form-control" required
                                                           data-validation-required-message="Password is required"
                                                           placeholder="Password">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <div class="controls">
                                                    <input type="password" name="password2" required
                                                           data-validation-match-match="password" class="form-control"
                                                           data-validation-required-message="Confirm password must match"
                                                           placeholder="Repeat Password">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Phone Number:</label>
                                                <div class="controls">
                                                    <input type="number" name="mobile" class="form-control" required
                                                           data-validation-regex-regex="([^a-z]*[A-Z]*)*"
                                                           data-validation-containsnumber-regex="([^0-9]*[0-9]+)+"
                                                           data-validation-required-message="The digits field must be numeric and exactly contain 11 digits"
                                                           maxlength="11" minlength="11"
                                                           placeholder="Enter Your Phone Number">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>User Name</label>
                                                <div class="controls">
                                                    <input type="text" name="username" class="form-control"
                                                           data-validation-regex-regex="^[-a-zA-Z_\d]+$"
                                                           data-validation-regex-message="Enter Your User Name, No Dash or Uderscore"
                                                           placeholder="User Name"
                                                           required >
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Expected Salary </label>
                                                <div class="controls">
                                                    <input type="number" name="salary" class="form-control" required
                                                           data-validation-required-message="Expected Salary & min field must be at least 3 digit"
                                                           minlength="3" placeholder="Expected Salary">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Facebook Link</label>
                                                <div class="controls">
                                                    <input type="text" name="fb_url" class="form-control"
                                                           data-validation-regex-regex="^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$"
                                                           data-validation-regex-message="Must be a valid url"
                                                           placeholder="Enter valid url" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Instagram Link</label>
                                                <div class="controls">
                                                    <input type="text" name="instagram_url" class="form-control"
                                                           data-validation-regex-regex="^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$"
                                                           data-validation-regex-message="Must be a valid url"
                                                           placeholder="Enter valid url" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="address">Address: </label>
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" id="address" rows="3" placeholder="Address" name="address"></textarea>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="note">Extra Note: </label>
                                                <fieldset class="form-group">
                                                    <textarea class="form-control" id="note" rows="3" placeholder="Note" name="note"></textarea>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card">
                                                <label for="contact-info-icon">Administrator Image Upload: </label>
                                                <div class="card-content">
                                                    <div class="input-group">
                                                    <span class="input-group-btn">
                                                       <a id="lfm" data-input="thumbnail" data-preview="holder" class="btn btn-primary text-white">
                                                            <i class="fa fa-picture-o"></i> Choose Images
                                                        </a>
                                                    </span>
                                                        <input id="thumbnail" class="form-control" type="text" name="image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card">
                                                <label for="contact-info-icon">Client Cover Image Upload: </label>
                                                <div class="card-content">
                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a id="lfm2" data-input="thumbnail2" data-preview="holder2" class="btn btn-primary text-white">
                                                                <i class="fa fa-picture-o"></i> Choose Images
                                                            </a>
                                                        </span>
                                                        <input id="thumbnail2" class="form-control" type="text" name="cover_image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12" hidden>
                                            <div class="form-group">
                                                <label for="status">Status: </label>
                                                <input id="status" class="form-control" type="number" name="status" value="1">
                                            </div>
                                        </div>
                                        <div class="col-12" hidden>
                                            <div class="form-group">
                                                <label for="can_login">Can Login: </label>
                                                <input id="can_login" class="form-control" type="number" name="can_login" value="1">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <button id="submit-all" type="submit" class="btn btn-primary mr-1 mb-1">
                                                Submit
                                            </button>
                                            <button type="reset" class="btn btn-outline-warning mr-1 mb-1">Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


