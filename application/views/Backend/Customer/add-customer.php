<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Add ".UCFIRST($this->class_name); ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.'customer-list'?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->			
			<!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="panel panel-primary">								
								<div class="panel-body tabs-menu-body">
									<div class="tab-content">
										<form action="<?php echo BASE_URL.$this->page_name?>" method="POST" data-parsley-validate="">
											<div class="row">	
												<div class="col-md-6">							
													<div class="form-group">
														<label class="form-label">First name: <span class="tx-danger">*</span></label>
														<input class="form-control" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" placeholder="Enter first name" required="" type="text">
													</div>	
													<div class="form-group">
														<label class="form-label">Contact no.: <span class="tx-danger">*</span></label>
														<input class="form-control" name="mobile_no" id="mobile_no" value="<?php echo set_value('mobile_no'); ?>" placeholder="Enter mobile name" required="" type="number" minlength="10">
													</div>
													<div class="form-group">
														<label class="form-label">Company name: <span class="tx-danger">*</span></label>
														<input class="form-control" name="company_name" id="company_name" value="<?php echo set_value('company_name'); ?>" placeholder="Enter company name" type="text" required>
													</div>
													<div class="form-group">
														<label class="form-label">GST no.: </label>
														<input class="form-control" name="gst_no" id="gst_no" value="<?php echo set_value('gst_no'); ?>" placeholder="Enter gst no." type="text">
													</div>
													<div class="form-group">
														<label class="form-label">Shipping address:</label>
														<textarea class="form-control" cols="50" name="shipping_address"></textarea>
													</div>
													<div class="form-group">
														<label class="ckbox"><input type="checkbox" name="same_address" value="Yes" id="same_address"><span>Same as Shipping Address</span></label>
													</div>
												</div>		
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Last name: <span class="tx-danger">*</span></label>
														<input class="form-control" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" placeholder="Enter last name" required="" type="text">
													</div>	
													<div class="form-group">
														<label class="form-label">Email: </label>
														<input class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" placeholder="Enter email id" type="email">
													</div>
													<div class="form-group">
														<label class="form-label">Display name: </label>
														<input class="form-control" name="display_name" id="display_name" value="<?php echo set_value('display_name'); ?>" placeholder="Enter display name" type="text">
													</div>
													<div class="form-group">
														<label class="form-label">PAN no.: </label>
														<input class="form-control" name="pan_no" id="pan_no" value="<?php echo set_value('pan_no'); ?>" placeholder="Enter pan no." type="text">
													</div>
													<div class="form-group">
														<label class="form-label">Billing address:</label>
														<textarea class="form-control" cols="50" name="billing_address"></textarea>
													</div>
												</div>
											</div>										
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="form-label mb-3">Additional info.:</label>
														<textarea class="content" rows="4" name="additional_info"></textarea>
													</div>
												</div>
											</div>
											<button class="btn ripple btn-primary" type="submit">Save</button>
										</form>										
									</div>
								</div>
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
<style>
	.richText .richText-editor{ height:75px;}
</style>