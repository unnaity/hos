<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo UCFIRST($this->class_name)." GRN"; ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL.'product-grn-list'?>">
					<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>	
			</div>
			<!-- End Page Header -->
			
			<form action="<?php echo BASE_URL.$this->page_name?>" id="create-grn" method="POST" data-parsley-validate="">
				<!-- Row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">							
								<?php $this->load->view('Backend/Elements/Alert.php'); ?>
								<div class="row">	
									<div class="col-md-2">	
										<div class="form-group">
										<label class="form-label">GRN Type<span class="tx-danger">*</span></label>
											<select name="grn_type_id" id="grn_type_id" class="form-control select2 select2-show-search" required id="grn_type_id">
												<option value="">Select GRN Type</option>
												<?php foreach ($this->data['grn_type'] as $key) { ?>
													<option value="<?php echo $key->grn_type_id ?>"><?php echo $key->grn_type_name ?></option>
												<?php } ?>
											</select>
											<!-- <input type="hidden" class="form-control hid-row" name="grn_type_id" id="grn_type_id" value=""> -->
										</div>
									</div>	
									<div class="col-md-2">	
										<div class="form-group">
											<label class="form-label">GRN No: <span class="tx-danger">*</span></label>
											<input class="form-control" name="grn_no" id="grn_no" value="<?php echo $this->grn_no; ?>" required="" readonly type="text">
										</div>
									</div>	
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Invoice Type: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="invoice_type" required id="invoice_type">
												<option value="">Select Invoice</option>
												<option value="1">Bill No.</option>
												<option value="2">Delivery Challan</option>
												<option value="3">PO</option>
											</select>
											<input type="hidden" name="invoice_type_id" id="invoice_type_id" value="">
										</div>	
									</div>
									<div class="col-md-2">	
										<div class="form-group">
											<label class="form-label" id="bill_id">Bill No.: <span class="tx-danger">*</span></label>
											<input class="form-control" name="bill_no" id="bill_no" value="" type="text">
										</div>
									</div>	
									<div class="col-md-2">	
										<div class="form-group">
											<label class="form-label">Bill Date:</label>
											<input class="form-control" name="po_date" id="po_date" value="" type="date">
										</div>
									</div>	
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Vendor: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="supplier" required id="supplier">
												<option value="">Select vendor</option>
												<?php echo $this->supplier_option; ?>
											</select>
											<input type="hidden" name="supplier_id" id="supplier_id" value="">
											<input type="hidden" name="supplier_name" id="supplier_name" value="">
										</div>	
									</div>
									<div class="col-md-12 text-center nxt-btn">										
										<button class="btn ripple btn-info" type="button" id="add-product-grn">Next</button>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->

				<!-- Row -->
				<div class="row hid-row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Category: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="category_id" required id="category_id">
												<option value="">Select category</option>
												<?php echo $this->category_option; ?>
											</select>
										</div>	
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Subcategory name: </label>
											<div id="subcategory_dropdown">
												<select class="form-control select2" name="subcategory_id" onchange="show_input(this.value);" id="subcategory_id" >
													<option value="">Select Subcategory</option>						
												</select>
											</div>
										</div>	
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Product name: <span class="tx-danger">*</span></label>
											<div id="product_dropdown">
												<select class="form-control select2" name="product_id" onchange="show_input(this.value);" id="product_id" required>
													<option value="">Select Product</option>						
												</select>
											</div>
										</div>	
									</div>	
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Part Code</label>
											<input class="form-control" name="part_code" id="part_code" value="" type="text" >
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">Serial No.</label>
											<input class="form-control" name="serial_no" id="serial_no" value="" type="text" >
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">Lot No.</label>
											<input class="form-control" name="lot_no" id="lot_no" value="" type="text">
										</div>
									</div>
									<div class="col-md-2">	
										<div class="form-group">
											<label class="form-label">Packing:</label>
											<input class="form-control" name="packing" id="packing" value="" type="text">
										</div>
									</div>
									<div class="col-md-2">	
										<div class="form-group">
											<label class="form-label">MFG Date:</label>
											<input class="form-control" name="mfg_date" id="mfg_date" value="" type="date">
										</div>
									</div>	
									<div class="col-md-2">	
										<div class="form-group">
											<label class="form-label">Expiry Date:</label>
											<input class="form-control" name="expiry_date" id="expiry_date" value="" type="date">
										</div>
									</div>	
									<div class="col-md-2">
										<label class="form-label">No. of items: <span class="tx-danger">*</span></label>
										<input class="form-control h-cls" name="no_of_items" id="no_of_items" value="" pattern="[0-9]" type="number" min="1">
										<input name="category_name" id="category_name" value="" required="" type="hidden">
										<input name="product_name" id="product_name" value="" required="" type="hidden">
									</div>	
									<!--div class="col-md-2" id="box_no">
										<label class="form-label">No. of Boxes: <span class="tx-danger">*</span></label>
										<input class="form-control h-cls" name="no_of_boxes" id="no_of_boxes" value="" required="" type="number" min="1">
									</div-->
									<!--div class="col-md-2 rtn-id">
										<div class="form-group">
											<label class="form-label">Return type: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="return_type" required id="return_type">
												<option value="">Select </option>
												<option value="1">In Stock</option>
												<option value="2">Reject</option>
											</select>
										</div>	
									</div-->
									<div class="col-md-12 text-center">
										<label class="form-label">&nbsp;</label>
										<button class="btn ripple btn-info" type="button" id="create-product-grn">Add</button>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->

				<!-- Row -->
				<div class="row hid-tbl">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">							
								<div class="table-responsive">
									<table id="grn-tbl" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
										<thead>
											<tr>												
												<th>Vendor</th>
												<th>Category</th>
												<th>Product</th>
												<th>MFG Date</th>
												<th>Expiry Date</th>
												<th>No. of Items</th>
												<!--th>No. of Boxes</th-->
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<?php echo get_additional_info();?>
								<div class="tx-center-f">
									<button class="btn ripple btn-primary" type="submit" id="add-btn">Save</button>
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
	.hid-tbl,.h-id{display:none;}	
</style>