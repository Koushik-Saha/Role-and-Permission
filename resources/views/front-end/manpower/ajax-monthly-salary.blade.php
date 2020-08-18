<section id="column-selectors">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <br>
                <h4 style="text-align: center;">
                    Salary Report of
                    <span class="text-success">{{ $month }}</span>
                    for
                    <span style="color: #f91484;">{{ $project->project_name }}</span>
                </h4>
                <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped dataex-html5-selectors " id="allProjects">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Salary</th>
                                    <th>Total Payable</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th style="max-width: 150px;">Note</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $totalPayable = 0;
                                    $totalPaid = 0;
                                    $totalDue = 0;
                                @endphp

                                @foreach($staffs as $index => $labour)
                                    <tr>
                                        <th scope="row">{{ $index + 1 }}</th>
                                        <td>
                                            <a href="{{route('manpower-details', ['project' => $project->project_id, 'id' => $labour->id])}}">{{$labour->name}}</a>
{{--                                            {{$labour->name}}--}}
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
                                        <td>{{ number_format($labour->salary, 2) }}/-</td>

                                        @php
                                            $payable = \App\Helpers\Helper::getMonthlyAttendancesOfUser($reqMonth, $labour)->sum('attendance_payable_amount');
                                            $totalPayable += $payable;
                                            $paid = \App\Helpers\Helper::getMonthlyPaymentsToStaff($reqMonth, $labour)->sum('payment_amount');
                                            $totalPaid += $paid;
                                            $totalDue += ($payable - $paid);
                                        @endphp

                                      <td>{{ number_format($payable, 2) }}/-</td>
                                      <td>{{ number_format($paid, 2) }}/-</td>
                                      <td>{{ number_format(($payable - $paid), 2) }}/-</td>
                                        <td style="max-width: 150px; white-space: normal; text-align: justify;">{!! $labour->note !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                  <td class="sr-only d-print-none">{{ $staffs->count() + 1 }}</td>
                                  <td></td>
                                  <td></td>
                                  <td class="font-weight-bold" style="color: red">Total</td>
                                  <td class="font-weight-bold" style="color: red">{{ number_format($totalPayable, 2) }}/-</td>
                                  <td class="font-weight-bold" style="color: red">{{ number_format($totalPaid, 2) }}/-</td>
                                  <td class="font-weight-bold" style="color: red">{{ number_format($totalDue, 2) }}/-</td>
                                  <td></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $('#allProjects').DataTable({
                responsive: true,
                dom: 'Bfrtip',
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