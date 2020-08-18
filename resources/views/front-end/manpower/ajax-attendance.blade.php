<br>
<section id="column-selectors">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 style="text-align: center;">Attendance of <span style="color: red" data-value="{{ $project->project_id }}">{{$project->project_name}}</span>
                        Project</h4>
                    <div align="center">
                        <div class="col-md-4" style="color: red">
                            Date : <span style="color: red" id="attendanceFrom"
										 data-value="{{ $start }}">{{ $start }}</span> - <span style="color: red" id="attendanceTo" data-value="{{ $end }}">{{ $end }}</span>
                        </div>
                    </div>
                    <br>

                    @if(count( $attendances ) <= 0)
                        <div>
                            <h3 style="text-align: center;margin-top: 20px;color: red;">No Attendance Found!!</h3>
                        </div>
                    @else
                        <div class="card-content">
                            <div class="card-body card-dashboard">
                                <div class="table-responsive">
                                    <table class="table table-striped dataex-html5-selectors" id="attendanceOfDay">
                                        <thead>
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col" class="text-center">Name</th>
                                            <th scope="col" class="text-center">Date</th>
                                            <th scope="col" class="text-center">Shifts</th>
                                            <th scope="col" class="text-center">Payable</th>
                                            <th scope="col" class="text-center">Paid</th>
                                            <th scope="col" class="text-center">Due</th>
                                            @can('manage-man-power')
                                                <th scope="col" class="text-center">Taken By</th>
                                                <th scope="col" class="text-center">Action</th>
                                            @endcan
                                            {{--                                    <th scope="col" style="width: 80px">Activity</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($attendances as $index => $attendance)
                                            <tr id="output">
                                                <td class="text-center">{{$index+1}}</td>
                                                <td class="text-center text-md-left">
                                                    <a href="{{ route('manpower-details', ['project' => $project->project_id, 'id' => $attendance->user['id']]) }}">
                                                        {{ $attendance->user['name'] }}
                                                    </a>
                                                </td>
                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($attendance->date)->toFormattedDateString() }}
                                                </td>
                                                <td class="text-center">
                                                    {{ implode(', ', collect($attendance->shifts)->sortBy('name')->pluck('name')->toArray()) }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="font-weight-bold">{{ $attendance->payable }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="font-weight-bold">{{ $attendance->paid }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="font-weight-bold">{{ $attendance->payable - $attendance->paid }}</span>
                                                </td>
                                                @can('manage-man-power')
                                                    <td class="text-center">
                                                        {{ $attendance->taken_by }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{--                                            <a href="{{ route('man_power.attendance.edit',['id' => $attendance->attendance_id]) }}" class="btn btn-warning p-1" title="Edit Attendance">--}}
                                                        <i class="feather icon-edit-1"></i>
                                                        {{--											</a>--}}
                                                        {{--                                                        <a class="btn btn-danger p-1 delete-attendance-btn" title="Delete Attendance" data-id="{{ $attendance->attendance_id }}" data-dismiss="modal">--}}
                                                        {{--                                                            <i class="feather icon-trash"></i>--}}
                                                        {{--                                                        </a>--}}
                                                        <button data-dismiss="modal"
                                                                data-id="{{ $attendance->attendance_id }}"
                                                                data-target="#dltBtnModal" title="Delete Attendance"
                                                                type="button" class="btn btn-link p-0 dltBtn"
                                                                style="width: auto; height: auto;">
                                                            <i class="feather icon-trash-2"
                                                               style="width: auto; height: auto;"></i>
                                                        </button>

                                                    </td>
                                                    {{--                                        <td class="text-center">--}}
                                                    {{--                                            <a class="btn btn-warning" href="{{ route('man_power.attendance.edit', ['id' => $project->project_id]) }}" title="Edit Project">--}}
                                                    {{--                                                <a href="{{ route('man_power.attendance.edit', ['id' => $attendance->attendance_id]) }}" class="btn btn-warning p-1" title="Edit Attendance">
                                                    {{--                                                <i class="feather icon-edit"></i>--}}
                                                    {{--                                            </a>--}}
                                                    {{--                                        </td>--}}
                                                @endcan
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <!-- Modal -->
        {{--	<div class="modal " id="deleteAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true" >--}}
        {{--		<div class="modal-dialog" role="document">--}}
        {{--			<div class="modal-content">--}}
        {{--				<div class="modal-header">--}}
        {{--					<h5 class="modal-title w-100 text-center" id="deleteModalLabel">Delete Attendance</h5>--}}
        {{--					<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
        {{--						<span aria-hidden="true">&times;</span>--}}
        {{--					</button>--}}
        {{--				</div>--}}
        {{--				<div class="modal-body text-center">--}}
        {{--					Are you sure wanna delete this attendance?--}}
        {{--				</div>--}}
        {{--				<div class="modal-footer">--}}
        {{--					<form action="{{ route('man_power.attendance.delete') }}" id="deleteAttendanceForm" method="POST">--}}
        {{--						@csrf--}}
        {{--						@method('DELETE')--}}
        {{--						<input type="hidden" name="attendance_id" id="attendanceId" value="">--}}
        {{--						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
        {{--						<button type="submit" class="btn btn-danger">Confirm</button>--}}
        {{--					</form>--}}
        {{--				</div>--}}
        {{--			</div>--}}
        {{--		</div>--}}
        {{--	</div>--}}
    </div>
</section>


<script>
    $('.delete-attendance-btn').click(function (e) {
        e.preventDefault();
        $('#attendanceId').val($(this).attr('data-id'));
        $('#deleteAttendanceModal').modal('show');
    });

</script>
