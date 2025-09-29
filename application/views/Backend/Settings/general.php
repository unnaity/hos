<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;"></div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/Alert.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-6">
					<div class="card custom-card">
						<div class="card-body">
							<div class="card-header border-bottom-0 p-0">
								<h3 class="text-start mb-4"><?php echo UCFIRST($page_title); ?></h3>
							</div>
							<div class="row">
								<div class="col-lg-7">
									<div class="table-responsive">
									<form action="<?php echo BASE_URL.'general'?>" method="POST" data-parsley-validate="">					
										<div class="form-group">
											<label class="form-label">Currency: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="currency_code_id" required>
											<option value="">Select Currency Code</option>
											<?php foreach($currency_list as $obj): ?>
												<option value="<?php echo $obj->currency_id;?>" <?php if($general_detail->currency_code_id == $obj->currency_id){ echo 'selected="selected"'; }?>>
												<?php echo $obj->currency_code;?>
												</option>
												<?php endforeach; ?>
											</select>
										</div>
										<div class="form-group">
											<label class="form-label">Default delivery time for sales orders:</label>
											<div class="input-group mb-3">
												<input aria-describedby="basic-addon2" class="form-control" name="sales_order" type="number" min="1" max="99" value="<?php echo isset($general_detail->sales_order) && ($general_detail->sales_order > 0) ? $general_detail->sales_order : DEFAULT_DELIVERY_TIME_SALES_ORDER ?>">
												<div class="input-group-text">
													<span class="rounded-start-0" id="basic-addon2">days</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="form-label">Default lead time for purchase orders:</label>
											<div class="input-group mb-3">
												<input aria-describedby="basic-addon2" class="form-control" name="purchase_order" type="number" id="purchase_order" min="1" max="99" value="<?php echo isset($general_detail->sales_order) && ($general_detail->sales_order > 0) ? $general_detail->sales_order : DEFAULT_LEAD_TIME_PURCHASE_ORDER ?>">
												<div class="input-group-text">
													<span class="rounded-start-0" id="basic-addon2">days</span>
												</div>
											</div>
										</div>	
										<div class="form-group pull-right">
											<button class="btn ripple btn-primary" type="submit">Update</button>
										</div>	
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