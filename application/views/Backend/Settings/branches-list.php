<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">				
				
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-8">
					<div class="card custom-card">
						<div class="card-body">
							<h4 class="text-start" style="float: left;"><?php echo UCFIRST($page_title); ?></h4>
							<div class="btn btn-list" style="float: right;margin-top:-4px;">
								<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>				
								<a class="btn ripple btn-success" href="#" data-bs-target="#add-branch" data-bs-toggle="modal" title="Add New Branch"><i class="fe fe-plus"></i></a>
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:30%;">Branch name</th>
											<th style="width:30%;">Legal name</th>
											<th style="width:30%;">Address</th>
											<!--th style="width:28%;">Enabled functions</th-->
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if($branch_list):
										foreach($branch_list as $obj): ?>
										<tr>
											<td><?php echo $obj->branch_name ?></td>
											<td><?php echo $obj->legal_name ?></td>
											<td><?php echo $obj->address ?></td>
											<!--td>
												<div class="row">
													<div class="col-lg-3">
														<label class="ckbox"><input type="checkbox" name="sell" <?php if($obj->sell == "Yes"){ echo "checked"; } ?>  value="Yes"><span>Sell</span></label>
													</div>
													<div class="col-lg-3">
														<label class="ckbox"><input <?php if($obj->make == "Yes"){ echo "checked"; } ?> value="Yes" name="make" type="checkbox">
														<span>Make</span></label>
													</div>
													<div class="col-lg-3">
														<label class="ckbox"><input <?php if($obj->buy == "Yes"){ echo "checked"; } ?> value="Yes" name="buy" type="checkbox"><span>Buy</span></label>
													</div>
												</div>
											</td-->
											<td>
												<?php 
													if($this->branch_id !== $obj->branch_id):
														echo get_delete_btn(BASE_URL.'delete-branch/'.$obj->branch_id); 
													endif;
												?>

											</td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='5'>No record found!</td></tr>";
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
<?php $this->load->view('Backend/Settings/add-branch');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-branch').modal('show');
	});
</script>
<?php } ?>