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
								<h3 class="text-start" style="float: left;">Stores</h3>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>
								
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-store" data-bs-toggle="modal" title="Add New Store"><i class="fe fe-plus"></i></a>
								</div>
								<!--p class="text-muted card-sub-title" style="display:inline-flex;">The categories here can be assigned to items for better organizing and grouping of products and materials.</p-->
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Store Name</th>
											<th>Branch Name</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if($store_list):
										foreach($store_list as $obj): ?>
										<tr>
											<td><?php echo $obj->store_name ?></td>
											<td><?php echo $obj->branch_name ?></td>
											<td>
												<?php 
													if($this->store_id !== $obj->store_id):
														echo get_delete_btn(BASE_URL.'delete-store/'.$obj->store_id); 
													endif;
												?>

											</td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='3'>No record found!</td></tr>";
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

<?php $this->load->view('Backend/Settings/add-store');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-store').modal('show');
	});
</script>
<?php } ?>