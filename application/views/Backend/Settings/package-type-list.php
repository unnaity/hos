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
								<h4 class="text-start" style="float: left;">Package Type</h4>
								<div class="btn btn-list" style="float: right;margin-top:-4px;margin-bottom: 10px;">
									<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>
								
									<a class="btn ripple btn-primary" href="#" data-bs-target="#add-package-type" data-bs-toggle="modal" title="Add Package Type"><i class="fe fe-plus"></i></a>
								</div>								
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:90%">Package type</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if($package_type_list):
										foreach($package_type_list as $obj): ?>
										<tr>
											<td><?php echo $obj->package_type ?></td>
											<td>
												<button id="bDel" type="button" class="btn  btn-sm btn-danger"><span class="fe fe-trash-2"> </span></button>
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

<?php $this->load->view('Backend/Settings/add-package-type');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-package-type').modal('show');
	});
</script>
<?php } ?>