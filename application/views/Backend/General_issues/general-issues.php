<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Create" .' '.UCWORDS(str_replace('_',' ',$this->class_name));?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.'dispatch-list';?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->			
			<!-- Row -->
			<form action="<?php echo BASE_URL.$this->page_name?>" method="POST" data-parsley-validate="" onsubmit="return check_pick_list()">
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">							
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="row">	
								<div class="col-md-3">
									<div class="row">	
										<div class="col-md-12">
											<div class="form-group">
												<label class="form-label">Customer Name: <span class="tx-danger">*</span></label>
												<select class="form-control select2" name="customer_id" required id="customer_id">
													<option value="">Select customer</option>
													<?php echo $this->customer_option; ?>
												</select>
												<input type="hidden" name="act" id="act" value="get_order">
											</div>	
										</div>

										<div class="col-md-12">
											<div class="form-group">
												<label class="form-label">Sales Order No.: <span class="tx-danger">*</span></label>
												<select class="form-control select2" name="order_id" required id="order_id">
													<option value="">Select Order</option>
												</select>
											</div>	
										</div>

										<div class="col-md-12">
											<div class="form-group">
												<label class="form-label">Delivery Date: <span class="tx-danger">*</span></label>
												<input class="form-control" name="delivery_date" id="delivery_date" value="" type="date">
											</div>	
										</div> 
										<div class="col-md-12 hid-tbl">
											<label class="form-label">Scan Box: <span class="tx-danger">*</span></label>
											<input class="form-control" name="pick_list_box_no" id="pick_list_box_no" value="" type="number">
											<span id="error_msg" class="tx-danger"></span>
										</div>
									</div>
								</div>
								<div class="col-md-9 ">
									<div class="table-responsive hid-tbl">
										<h5>Sales Order Details</h5>
										<table id="" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
											<thead>
												<tr>
													<th>Sl. No.</th>
													<th>Product Name</th>
													<th>Order Quantity</th>
													<th>Location</th>
												</tr>
											</thead>
											<tbody id="prod_list">
											</tbody>
										</table>
									</div><br><br><br>	

									<div class="table-responsive pick-list-tbl">
										<h5>Pick List</h5>
										<table class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
											<thead>
												<tr>
													<th>Product Name</th>
													<th>Box No.</th>
													<th>Rack No.</th>
													<th>Box Quantity</th>
													<!--th>Action</th-->
												</tr>
											</thead>
											<tbody id="pick_list">
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-3">
									<div class="row" id="box_detail"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 hid-btn text-center">
									<label class="form-label"></label>										
									<button class="btn ripple btn-info" type="submit">Save Pick List</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->			
			</form>
		</div>
	</div>
</div>
<!-- End Main Content-->

<Style>
	.table>thead>tr>th{ font-size:13px; font-weight:500; text-transform:unset; }
	.no-border{ padding:0; border: unset; }
	.richText .richText-editor{ height:80px;}
	.hid-tbl,.h-id, .pick-list-tbl,.hid-btn{display:block;}	
	.box_detail{margin-top:20px;}
</style>