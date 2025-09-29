<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo " RM GRN"; ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL . 'rm-grn-list' ?>">
						<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>
			</div>
			<!-- End Page Header -->

			<form action="<?php echo BASE_URL . $this->page_name ?>" id="create-grn" method="POST" data-parsley-validate="">
				<!-- Row -->
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<?php $this->load->view('Backend/Elements/Alert.php'); ?>
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">GRN No: <span class="tx-danger">*</span></label>
											<input class="form-control" name="rm_grn_no" id="rm_grn_no" value="<?php echo $this->rm_grn_no; ?>" required="" readonly type="text">
										</div>
									</div>
									<div class="col-md-3">
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
											<select class="form-control select2" name="supplier" required id="supplier">
												<option value="">Select vendor</option>
												<?php echo $this->supplier_option; ?>
											</select>
											<input type="hidden" name="supplier_id" id="supplier_id" value="">
											<input type="hidden" name="supplier_name" id="supplier_name" value="">
										</div>
									</div>
									<div class="col-md-12 text-center nxt-btn">
										<button class="btn ripple btn-info" type="button" id="add-rm-grn">Next</button>
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
											<label class="form-label" class="alert alert-danger h-id" id="h-id">RM: <span class="tx-danger" id="js-error-msg">*</span></label>
											<select class="form-control select2" name="rm_id[]" required id="rm_id">
												<option value="">Select rm</option>
												<?php foreach ($rm as $key) { ?>
													<option value="<?php echo $key->raw_material_id ?>"><?php echo $key->raw_material_name ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label class="form-label">HSN Code: </label>
											<input class="form-control" name="hsn_code[]" id="hsn_code" value="" type="text">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">Qty: </label>
											<input class="form-control" name="quantity[]" id="quantity" value="" type="text">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label"> Unit</label>
											<input class="form-control" name="inward_unit_id" id="inward_unit_id" value="" type="text">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">Rate:</label>
											<input class="form-control" name="rate[]" id="rate" value="" type="number">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">Amount:</label>
											<input class="form-control" name="amount[]" id="amount" value="" type="number" readonly>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">Tax: </label>
											<select class="form-control select2" name="tax_id[]" id="tax_id">
												<option value="">Select Tax</option>
												<?php foreach ($tax_rate_list as $key) { ?>
													<option value="<?php echo $key->tax_id ?>"><?php echo $key->tax_rate ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<label class="form-label">After Tax:</label>
											<input class="form-control" name="after_tax[]" id="after_tax" value="" type="text" readonly>
										</div>
									</div>
									<div class="col-md-2">
										<label class="form-label">No. of boxes: <span class="tx-danger">*</span></label>
										<input class="form-control h-cls" name="no_of_boxes" id="no_of_boxes" value="" pattern="[0-9]" type="number" min="1">

										<input name="raw_material_name" id="raw_material_name" value="" type="hidden">
										<input name="rm_id" id="rm_id[]" value="" type="hidden">
									</div>
									<div class="col-md-12 text-center">
										<label class="form-label">&nbsp;</label>
										<button class="btn ripple btn-info" type="button" id="create-rm-grn">Add</button>
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
												<th>RM</th>
												<th>Quanity</th>
												<th>Amount</th>
												<th>PO Date</th>
												<th>No. of Boxes</th>
												<!--th>No. of Boxes</th-->
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
								</div>
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