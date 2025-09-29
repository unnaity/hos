<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;"></div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-6">
					<div class="card custom-card">
						<div class="card-body">
							<div class="card-header border-bottom-0 p-0">
								<h3 class="text-start" style="float: left;"><?php echo UCFIRST($page_title); ?></h3>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-department" data-bs-toggle="modal" title="Add New Department"><i class="fe fe-plus"></i></a>
								</div>								
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:90%;">Department Name</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if($department_list): $i=1;
										foreach($department_list as $obj): ?>
										<tr>
											<td><?php echo $i; $i++; ?></td>
											<td><?php echo $obj->$department_name ?></td>
											<td>
											<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'department-delete/'.$obj->department_id ?>');" class="btn btn-sm btn-danger" title="Delete">
													<i class="fe fe-trash-2"> </i>
												</button>
											</td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='2'>No record found!</td></tr>";
									endif;
									?>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->

<?php $this->load->view('Backend/Settings/add-department');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-department').modal('show');
	});
</script>
<?php } ?>