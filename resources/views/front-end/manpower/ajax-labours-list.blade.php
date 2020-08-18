<section id="column-selectors">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <br>
                <h4 style="text-align: center;" >All Staffs of <span style="color: #f91484;">{{$project->project_name}}</span> Project</h4>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped dataex-html5-selectors " id="allProjects">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Salary</th>
                                    <th>Total <br>Payable</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Mobile</th>
                                    <th>Added By</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($staffs as $index => $labour)
                                    <tr>
                                        <td scope="row">{{ $index+1 }}</td>
                                        <td>
                                            <a href="{{route('manpower-details', ['project' => $project->project_id, 'id' => $labour->id])}}">{{$labour->name}}</a>
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
                                        <td>
                                            <input type="checkbox"  data-id="{{ $labour->id }}" name="status" class="js-switch" {{ $labour->status == 1 ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            {{ number_format($labour->salary, 2) }}
                                        </td>
                                        @php($payable = $labour->attendances->sum('attendance_payable_amount'))
                                        @php($paid = $labour->staffPayments->sum('payment_amount'))

                                        <td>{{ number_format($payable, 2) }} </td>
                                        <td>{{ number_format($paid, 2) }} </td>
                                        <td>{{ number_format(($payable - $paid), 2) }} </td>
                                        <td>
                                            {{ \App\Helpers\Helper::mobileNumber($labour->mobile) }}
                                        </td>
                                        <td>{{ ($labour->addedBy()) ? $labour->addedBy()->name : 'N/A' }}</td>
                                        <td>
{{--                                            <a href="{{ route('man_power.edit', ['id' => $labour->id]) }}" class="btn btn-warning">--}}
                                                <i class="feather icon-edit-2"></i>
{{--                                            </a>--}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Status</th>
                                    <th>Salary</th>
                                    <th>Mobile</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>
            let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

            elems.forEach(function(html) {
                let switchery = new Switchery(html,  { size: 'small' });
            });

            $(document).ready(function(){

                $('#allProjects').on('change', '.js-switch', function(){
                    // ... skipped ...
                    let status = $(this).prop('checked') === true ? 1 : 0;
                    let userId = $(this).data('id');
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        url: '{{ route('change-staff-status') }}',
                        data: {'status': status, 'user_id': userId},
                        success: function (data) {
                            toastr.options.closeButton = true;
                            toastr.options.closeMethod = 'fadeOut';
                            toastr.options.closeDuration = 100;
                            toastr.success(data.message);
                        }
                    });
                });
            });

            $('#allProjects').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                language : {
                    sLengthMenu: "Show _MENU_"
                },
                buttons: [
                    'csv', 'pdf',
                    {
                        extend: 'print',
                    }
                ]
            });
            $('.buttons-csv, .buttons-print, .buttons-pdf').addClass('btn btn-success mr-1');
        </script>

    </div>
</section>