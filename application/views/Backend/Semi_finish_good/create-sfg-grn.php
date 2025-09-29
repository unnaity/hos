<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "GRN"; ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL . 'sfg-grn-list' ?>">
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
								<div class="row">
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">GRN No: <span class="tx-danger">*</span></label>
											<input class="form-control" name="sfg_grn_no" id="sfg_grn_no" value="<?php echo $this->sfg_grn_no; ?>" required="" readonly type="text">
										</div>
									</div>
									<!-- GRN Type Dropdown -->
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">Material Type: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="grn_type" id="grn_type">
												<option value="">Select Material Type</option>
												<option value="fg">FG</option>
												<option value="sfg">SFG</option>
												<option value="rm">RM</option>
											</select>
										</div>
									</div>

									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">PO No: </label>
											<input class="form-control" name="po_no" id="po_no" value="" type="text">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">PO Date: </label>
											<input class="form-control" name="po_date" id="po_date" value="" type="date">
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Vendor: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="supplier" id="supplier">
												<option value="">Select vendor</option>
												<?php echo $this->supplier_option; ?>
											</select>
											<input type="hidden" name="supplier_id" id="supplier_id" value="">
											<input type="hidden" name="supplier_name" id="supplier_name" value="">
										</div>
									</div>
									<div class="col-md-12 text-center nxt-btn">
										<button class="btn ripple btn-info" type="button" id="add-sfg-grn">Next</button>
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
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Select Item: <span class="tx-danger">*</span></label>
											<select class="form-control select2" id="item_id">
												<option value="">Select item</option>
											</select>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Code: </label>
											<input class="form-control" id="hsn_code" value="" type="text" readonly>
										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Qty: <span class="tx-danger">*</span></label>
											<input class="form-control" id="quantity" value="" type="text" required min="0">
											
										</div>
									</div>

									<div class="col-md-3">
										<label class="form-label">No. of boxes: <span class="tx-danger">*</span></label>
										<input type="number" class="form-control grn_no_of_boxes" id="grn_no_of_boxes" name="grn_no_of_boxes[]" value="" required>
										
										<div class="col-md-12" id="box_no">
										</div>

									</div>
									
									<div class="col-md-12 text-center">
										<label class="form-label">&nbsp;</label>
										<button class="btn ripple btn-info" type="button" id="create-sfg-grn">Add</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->
				<form action="<?php echo BASE_URL . $this->page_name ?>" method="POST" data-parsley-validate="">
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
												<th>Item</th>
												<th>Quanity</th>
												<th>No. of Boxes</th>
												<!--th>No. of Boxes</th-->
												<th>Action</th>

											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
								<div id="hidden-inputs" style="display:none;"></div>
								<?php echo get_additional_info(); ?>
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
	.table>thead>tr>th {
		font-size: 13px;
		font-weight: 500;
		text-transform: unset;
	}

	.no-border {
		padding: 0;
		border: unset;
	}

	.richText .richText-editor {
		height: 80px;
	}

	.hid-tbl,
	.h-id {
		display: none;
	}
</style>