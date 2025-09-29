<!-- Main Content -->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Create BOM "  ?></h4>
			</div>
			<?php $this->load->view('Backend/Elements/Alert.php'); ?>

			<!-- Row -->

			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
							<!-- ✅ FORM STARTS HERE -->


							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">FG: <span class="tx-danger">*</span></label>
										<select class="form-control select2" name="bom_id[]" required id="bom_id">
											<option value="">Select FG</option>
											<?php foreach ($fg_list as $key) { ?>
												<option value="<?php echo $key->fg_id; ?>">
													<?php echo $key->fg_code . ' / ' . $key->fg_discription; ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Material Type: <span class="tx-danger">*</span></label>
										<select class="form-control select2" required id="grn_type" name="grn_type[]">
											<option value="">Choose one</option>
											<option value="rm">RM</option>
											<option value="sfg">SFG</option>
										</select>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Item:<span class="tx-danger">*</span></label>
										<select class="form-control select2" name="item_id[]" required id="item_id">
											<option value="">Select sfg</option>
										</select>
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Qty(SFG/RM):<span class="tx-danger">*</span></label>
										<input class="form-control" name="sfg_qty[]" id="sfg_qty" value="" type="text" min="2" required>
									</div>
								</div>

								<div class="col-md-12 text-center">

									<button class="btn ripple btn-info" type="button" id="create-bom">Add</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<form action="<?php echo BASE_URL . $this->page_name ?>" method="POST" data-parsley-validate="">
				<div class="row hid-tbl">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div class="table-responsive">
									<table id="bom-tbl" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
										<thead>
											<tr>
												<th>BOM</th>
												<th>Material Type</th>
												<th>Item Name</th>
												<th>Qty</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<!-- Rows added dynamically via JavaScript -->
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
			</form>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content -->

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
</style>