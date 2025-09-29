<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0">BOM Based Issue</h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL . 'general-issues-list'; ?>">
						<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>
			</div>
			<!-- End Page Header -->
			<!-- Row -->
			<?php $this->load->view('Backend/Elements/Alert.php'); ?>
			
			<form action="<?php echo BASE_URL . $this->page_name ?>" method="POST" data-parsley-validate="">
				<div class="row">
					<div class="col-md-4">
						<div class="card custom-card">
							<div class="card-body">
								<div class="row row-xs align-items-center mg-b-13">
									<div class="col-md-4">
										<label class="mg-b-0">Issues No.: <span class="tx-danger">*</span></label>
									</div>
									<div class="col-md-8 mg-t-5 mg-md-t-0">
										<input class="form-control" name="gi_no" id="gi_no"
											value="<?php echo isset($gi_detail->general_issues_no) ? $gi_detail->general_issues_no : $this->gi_no; ?>"
											type="text" readonly>

										<input class="form-control" name="general_issue_id" id="general_issue_id"
											value="<?php echo isset($gi_detail->general_issues_id) ? $gi_detail->general_issues_id : ''; ?>"
											type="hidden" readonly>
									</div>
								</div>

								<div class="row row-xs align-items-center mg-b-13">
									<div class="col-md-4">
										<label class="mg-b-0">BOM: <span class="tx-danger">*</span></label>
									</div>
									<div class="col-md-8 mg-t-5 mg-md-t-0">
										<select class="form-control select2" name="fg_id[]" required id="fg_id">
											<option value="">Select FG</option>
											<?php foreach ($fg_list as $key) { ?>
												<option value="<?php echo $key->fg_id ?>"
													<?php echo (isset($gi_detail->fg_id) && $gi_detail->fg_id == $key->fg_id) ? 'selected' : ''; ?>>
													<?php echo $key->fg_code ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>

								<div class="row row-xs align-items-center mg-b-13">
									<div class="col-md-4">
										<label class="mg-b-0">Quantity: <span class="tx-danger">*</span></label>
									</div>
									<div class="col-md-8 mg-t-5 mg-md-t-0">
										<input class="form-control" name="fg_quantity" id="fg_quantity"
											value="<?php echo isset($gi_detail->fg_quantity) ? $gi_detail->fg_quantity : ''; ?>"
											type="number" required>

										<span id="error_msg" class="tx-danger"></span>
									</div>
								</div>

								<div class="row row-xs align-items-center">
									<div class="col-md-4">
										<label class="mg-b-0">Scan Box: <span class="tx-danger">*</span></label>
									</div>
									<div class="col-md-8 mg-t-5 mg-md-t-0">
										<input class="form-control" name="gi_box_no_item" id="gi_box_no_item" value="" type="number" required>
										<span id="error_msg" class="tx-danger"></span>
									</div>
								</div>
								<input type="hidden" id="scanned_gi_box_no" name="scanned_gi_box_no" value="">

								<div class="row row-xs align-items-center">
									<div class="col-md-12">
										<div class="row" id="gi_box_detail"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-8">
						<div class="card custom-card">
							<div class="card-body">
								<div class="table-responsive">
									<h5>FG Detail</h5>
									<table id="" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
										<thead>
											<tr>
												<th>Sl. No.</th>
												<th>FG Name</th>
												<th>Material Type</th>
												<th>Item Name</th>
												<th>P/C Relationship</th>
												<th>Issued Quantity</th>
												<th>Scanned Quantity</th>
											</tr>
										</thead>
										<tbody id="fg_total_list">
											<?php if (isset($fg_detail)):
												$i = 1;
												foreach ($fg_detail as $obj):
													$grn_type = strtolower(trim($obj->grn_type));
													$grn_type_id = $grn_type . '_id_' . $obj->item_id;
													$scanned_qty_id = $grn_type . '_scanned_qty_id_' . $obj->item_id;
													
													$issued_qty = $obj->fg_quantity * $obj->quantity;
													$scanned_qty = !empty($obj->no_of_items) ? $obj->no_of_items : 0;
											?>
													<tr class="fg-row" data-item-id="<?= $obj->item_id ?>">
														<td><?= $i++ ?></td>
														<td><?= $obj->fg_code ?></td>
														<td><?= strtoupper($obj->grn_type) ?></td>
														<td><?= $obj->item_name ?></td>
														<td><?= $obj->quantity ?></td>
														<td class="issued-qty"><?= $issued_qty ?></td>
														<td class="scanned-qty"><?= $obj->scanned_qty ?></td>

														<!-- ✅ Required hidden inputs for JS -->
														<input type="hidden" id="<?= $grn_type_id ?>" value="<?= $issued_qty ?>">
														<input type="hidden" id="<?= $scanned_qty_id ?>" value="<?= $scanned_qty ?>">
														<input type="hidden" class="fg-item-id" value="<?= $obj->item_id ?>">
														<input type="hidden" id="rm_count_id" value="<?= !empty($obj->no_of_rm) ? $obj->no_of_rm : 0 ?>">
													</tr>
											<?php endforeach;
											endif; ?>
										</tbody>



									</table>
								</div>
							</div>
						</div>

						<div class="card custom-card">
							<div class="card-body">
								<div class="table-responsive">
									<h5> Scan Summary</h5>
									<table id="grn-tbl" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
										<thead>
											<tr>
												<th>FG Name</th>
												<th>Item Name</th>
												<th>Quantity</th>
												<th>Box No.</th>
											</tr>
										</thead>
										<tbody id="gi_list">
											<?php if (isset($box_detail)):
												$i = 1;
												foreach ($box_detail as $obj): ?>
													<tr>
														<td><?php echo $obj->fg_code; ?></td>

														<td><?php echo $obj->item_name; ?></td>

														<td><?php echo $obj->no_of_items; ?></td>
														<td><?php echo $obj->box_no; ?></td>
													</tr>
											<?php endforeach;

											endif; ?>
											<!-- Loaded dynamically in edit mode -->
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Main Content-->

<style>
	.richText .richText-editor {
		height: 80px;
	}
</style>