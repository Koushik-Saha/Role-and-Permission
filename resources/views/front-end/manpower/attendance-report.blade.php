@extends('layouts/contentLayoutMaster')

@section('title', 'Staff List')

@section('page-style')
	<link rel="stylesheet" href="{{ asset('css/jqueryPreloader/css/preloader.css') }}">
	<link rel="stylesheet" href="{{ asset('css/jqueryPreloader/css/preloader.scss') }}">
{{--	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.css">--}}
{{--	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.semanticui.min.css">--}}
@endsection

@section('content')
	<!-- Content types section start -->
	<section id="content-types">
		<div class="row" id="reportPreloader">
			<div class="col-md-8 offset-2">
				<div class="card comp-card">
					<div class="card-body">
						<button type="button" class="btn mb-1 btn-outline-primary btn-icon btn-lg btn-block btn-sm" style="color: green">Attendance List</button>
						<form action="{{ route('manpower-attendance-report') }}" method="post" id="labSearch">
							@csrf
							<div class="form-body">
								<div class="form-group">
									<select class="select2 form-control" name="pid" id="pid">
										<option selected disabled>Select Project To See All Labour Report</option>
										@foreach($projects as $project)
											<option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
										@endforeach
									</select>
								</div>
								<br>
								<div class="form-group">
									<label class="">Start Date : </label>
									<input type="date" id="start" name="start" class="form-control">
								</div>
								<div class="form-group">
									<label class="">End Date : </label>
									<input type="date" id="end" name="end" class="form-control">
								</div>
								<br>
								<div class="form-group">
									<button type="submit" class="btn btn-primary mr-1 mb-1 text-center btn-block">
										Search
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
									<h4 class="m-b-0"><label class="badge badge-pill badge-success badge-lg mr-1 mb-1">Workers </label></h4></div>
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
	<script src="{{ asset('js/scripts/jquery.preloader.js') }}"></script>
	<script src="{{ asset('css/jqueryPreloader/js/jquery.preloader.min.js') }}"></script>
{{--	<script src="https://cdn.datatables.net/1.10.19/js/dataTables.semanticui.min.js"></script>--}}
{{--	<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.1/semantic.min.js"></script>--}}
{{--	<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>--}}
{{--	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"></script>--}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
{{--	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>--}}
{{--	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>--}}
{{--	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>--}}
{{--	<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js"></script>--}}
	<script type="text/javascript">
		$(document).ready(function() {
			$("#labSearch").submit(function(e) {
				e.preventDefault();

				$('#reportPreloader').preloader({ text: 'Processing Your Request' });

				let pid=$("#pid").val();
				let start=$("#start").val();
				let end=$("#end").val();
				//console.log(pid);
				$.ajax({
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					url  : "{{route('manpower-attendance-report')}}",
					type : "POST",
					data : {pid: pid, start: start, end: end},
					success : function(response){
						$('#reportPreloader').preloader('remove');
						$("#ajaxResult").html(response);

						let dt =$('#attendanceOfDay').DataTable( {
							responsive: true,
							//dom: '<".row"<".col-md-4"l><".col-md-4"f><".col-md-4"B>>rt<".row"<".col-md-5"i><".col-md-7"p>>',  //lBfrtip
							dom: "<'row my-3'<'col-sm-12 col-md-4 text-center text-md-left'l><'col-sm-12 col-md-4 text-center'f><'col-sm-12 col-md-4 text-center text-md-right'B>>" +
									"<'row'<'col-sm-12'<'table-responsive'tr>>>" +
									"<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
							language: {
								search: "_INPUT_",
								searchPlaceholder: "Search..."
							},
							buttons: [
								{
									extend: 'excelHtml5',
									charset: 'utf-8',
									exportOptions: {
										columns: [ 0, 1, 2, 3, 4, 5, 6 ]
									}
								},
								{
									extend: 'print',
									exportOptions: {
										columns: [ 0, 1, 2, 3, 4, 5, 6 ]
									}
								}
							]
						} );
						$('.dt-button').addClass('ui button');
						$('.dataTables_filter, .dataTables_filter span, .dataTables_filter label, .dataTables_filter input').css('width', '100%');
					},
					error : function(xhr, status) { console.log(xhr) }
				});
			});
		});
	</script>

@endsection