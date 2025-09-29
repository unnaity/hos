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
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
							<div class="card-header border-bottom-0 p-0">
								<h3 class="text-start" style="float: left;">FG List</h3>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">

									<a class="btn ripple btn-success" href="#" data-bs-target="#add-category" data-bs-toggle="modal" title="Add New Category"><i class="fe fe-plus"></i></a>
								</div>
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>FG Code</th>
											<th>Sales Qty</th>
											<th>FG Description</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										
										<?php
										 echo "<tr><td colspan='5'>No record found!</td></tr>";
										
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
<?php $this->load->view('Backend/Settings/add-category'); ?>

<?php if (validation_errors() || $this->session->flashdata('error_message')) { ?>
	<script type="text/javascript">
		$(window).on('load', function() {
			$('#add-category').modal('show');
		});
	</script>
<?php } ?>