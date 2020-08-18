<section id="column-selectors">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <br>
                <h4 style="text-align: center;" >Take Attendance For <span style="color: #f91484;">{{$project->project_name}}</span> Project</h4>
                <div align="center">
                    <div class="col-md-4">
                        <label for="date" style="font-weight: 600;">Date: </label>
                        <input class="form-control" name="date" type="date" id="date" required="">
                    </div>
                </div>
                <br>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped dataex-html5-selectors " id="Attendence">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Shift</th>
                                    <th>Status</th>
                                    <th>Food / Advance</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <input type="hidden" name="project_id" value="{{ $project->project_id }}">
                                @forelse($staffs as $index => $labour)
                                    <tr>
                                        <th scope="row">{{ $index+1 }}</th>
                                        <td>
                                            <a href="{{route('manpower-details', ['project' => $project->project_id, 'id' => $labour->id])}}">{{$labour->name}}</a>
                                            <input type="hidden" name="labour_id_{{ $labour->id }}" value="{{$labour->id}}">
                                        </td>
                                        <td>
                                            @if($labour->role->role_slug == 'machine')
                                                <span class="badge badge-pill badge-glow badge-success badge-md mr-1 mb-1">{{$labour->role->role_name}}</span>
                                            @elseif($labour->role_slug == 'labour')
                                                <span class="badge badge-pill badge-glow badge-warning badge-md mr-1 mb-1">{{$labour->role->role_name}}</span>
                                            @else
                                                <span class="badge badge-pill badge-glow badge-primary badge-md mr-1 mb-1">{{$labour->role->role_name}}</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 150px;">
                                            <select name="shift_{{ $labour->id }}" class="custom-select" required>
                                                <option selected disabled>--- Select Shift ---</option>
                                                @foreach($project->shifts as $shift)
                                                    <option value="{{ $shift->shift_id }}">
                                                        {{ $shift->shift_name }} &nbsp;&nbsp; --- &nbsp;&nbsp;
                                                        {{ \Carbon\Carbon::parse($shift->shift_start)->format('h:i A') }} TO
                                                        {{ \Carbon\Carbon::parse($shift->shift_end)->format('h:i A') }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            @if( $labour->status == 1)
                                                <span class="badge badge-pill badge-success">Active</span>
                                            @else
                                                <span class="badge badge-pill badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <input type="number" name="paid_{{$labour->id}}" class="form-control">
                                        </td>
                                        <td>
                                            <textarea class="form-control" name="note_{{ $labour->id }}" id="" cols="10" rows="1"></textarea>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary mr-1 mb-1 add-attendance-btn btn-block" id="{{ $labour->id }}">Add</button>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Shift</th>
                                    <th>Status</th>
                                    <th>Food / Advance</th>
                                    <th>Note</th>
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

<script>
    // Add Attendance
    $(document).ready(function() {

        $(".add-attendance-btn").click(function(e) {
            e.preventDefault();
            let err = false;

            let labour_id = e.target.id;
            let project_id = $("input[name=project_id]").val();
            let date = $("input[name=date]").val();
            let shift = $("select[name=shift_" + labour_id + "]").val();
            let paid = $("input[name=paid_" + labour_id + "]").val();
            let note = $("textarea[name=note_" + labour_id + "]").val();

            if(typeof date !== 'string' || date.length < 8) {
                toastr.error("Please Select A Date!");
                err = true;
            }
            if(typeof shift !== 'string') {
                toastr.error("Please Select A Shift!");
                err = true;
            }

            if(!err) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url  : "{{ route('manpower-store-attendance') }}",
                    type : "POST",
                    data : { labour_id, project_id, date, shift, paid, note},
                    success : function(res){
                        if(res.status === 'error') {
                            console.log(res);
                            toastr.error(res.msg);
                        }
                        if(res.status === 'success') {
                            console.log(res);
                            toastr.success(res.msg);
                        }
                    },
                    error : function(xhr, status){
                        console.log(xhr, status);
                        toastr.error('Something Wrong! Please try again later.');
                    }
                });
            }
        });
    });

    // $('#Attendence').DataTable({
    //     responsive: true,
    //     dom: 'Bfrtip',
    //     language : {
    //         sLengthMenu: "Show _MENU_"
    //     },
    //     buttons: [
    //         'csv', 'pdf',
    //         {
    //             extend: 'print',
    //         }
    //     ]
    // });
    // $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
</script>
