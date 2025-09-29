<!-- Basic modal -->
<div class="modal" id="add-sfg">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL . 'add-sfg' ?>" method="POST" id="add-sfg" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add SFG</h6>
					<button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>

					<div class="row row-xs align-items-center" style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">SFG Name: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<input class="form-control" name="sfg_name" id="sfg_name" value="" placeholder="Enter SFG Name" required="" type="text">
						</div>
					</div>

					<div class="row row-xs align-items-center" style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">SFG Code: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<input class="form-control" name="sfg_code" id="sfg_code" value="" placeholder="Enter SFG Code" required="" type="text">
						</div>
					</div>

					<div class="row row-xs align-items-center" style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">Sustanability Score: </label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<input class="form-control" name="sustainability_score" id="sustainability_score" value="" placeholder="Enter Sustansbility Score" type="text">
						</div>
					</div>
					<div class="row row-xs align-items-center" style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">Unit: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<select class="form-control" name="unit_id" id="unit_id" required>
								<option value="">Loading units...</option>
							</select>

						</div>
					</div>

					<div class="row row-xs align-items-center" style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">Unit Weight: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<input class="form-control" name="weight" id="weight" value="" placeholder="Enter weight" required="" type="text">
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button class="btn ripple btn-primary" type="submit">Save</button>
					<button id="form-reset" class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Basic modal -->