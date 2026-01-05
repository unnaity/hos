<!-- Main Content -->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo "Edit BOM "  ?></h4>
			</div>
			<?php $this->load->view('Backend/Elements/Alert.php'); ?>

			<!-- Row -->
			
				<div class="row">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div class="row">
									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">FG: <span class="tx-danger">*</span></label>
											<select class="form-control select2" name="bom_id[]" required id="bom_id">
												<option value="">Select FG</option>
												<?php foreach ($this->fg_list as $key) { ?>
													<option value="<?php echo $key->fg_id ?>"
														<?php echo (!empty($this->fg_id) && $this->fg_id == $key->fg_id) ? 'selected' : ''; ?>>
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
												<?php echo $this->bom_list_detail->grn_type; ?>
											</select>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Item:</label>
											<select class="form-control select2" name="item_id[]" required id="item_id">
												<option value="<?php echo $this->bom_list_detail->item_name; ?>">Select sfg</option>
											</select>
										</div>
									</div>

									<div class="col-md-3">
										<div class="form-group">
											<label class="form-label">Qty(SFG/RM):</label>
											<input class="form-control" name="sfg_qty[]" id="sfg_qty" value="<?php echo $this->bom_list_detail->quantity; ?>" type="text" min="2">
										</div>
									</div>

									<div class="col-md-12 text-center">
										<button class="btn ripple btn-info" type="button" id="edit-bom">Add</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<form action="<?php echo BASE_URL . 'bom-edit/' . $this->fg_id ?>" method="POST" data-parsley-validate="">
				<div class="row">
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
											<?php foreach ($this->bom_list_detail as $key): ?>
												<tr id="tr_<?php echo $key->fg_id; ?>">
													<td><?php echo $key->fg_code.'/'. $key->fg_discription; ?>
														<input type="hidden" name="bom_id[]" value="<?php echo $key->fg_id ?>">
													</td>
													<td>
														<?php echo strtoupper($key->grn_type) ?>
														<input type="hidden" name="grn_type[]" value="<?php echo $key->grn_type ?>">
													</td>
													<td>
														<?php echo $key->item_code.'/'.$key->item_name ?>
														<input type="hidden" name="item_id[]" value="<?php echo $key->item_id ?>">
													</td>
													<td>
														<input style="width:60px;" type="number" name="sfg_qty[]" value="<?php echo $key->quantity; ?>">
													</td>
													<td><button type="button " class="btn btn-danger" name="remove" onclick="delete_sales_tr(<?php echo $key->fg_id; ?>)"><i class="fe fe-trash-2"> </i></button></td>

												</tr>
											<?php endforeach; ?>
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
				<!-- ✅ DYNAMIC BOM TABLE ENDS -->
			</form>
			<!-- ✅ FORM ENDS HERE -->


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