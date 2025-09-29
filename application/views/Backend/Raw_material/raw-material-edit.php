<!-- Basic modal -->
<div class="modal" id="raw-material-edit">
	<div class="modal-dialog " role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'raw-material-list'?>" method="POST" id="Edit RM" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Edit RM</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row row-xs align-items-center mg-b-20">
						<div class="col-md-4">
							<label class="form-label">RM Name: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="rm_name" id="rm_name" required="" placeholder="Enter raw material name">
							
							
						</div>
                        <div class="col-md-4">
							<label class="form-label">RM Code: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="rm_code" id="rm_code" required="" placeholder="Enter raw material code">
						</div>
                        <div class="col-md-4">
							<label class="form-label">Sustanability Score: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="s_score" id="s_score" required="" placeholder="Enter sustanability score">
						</div>
						<div class="col-md-4">
							<label class="form-label">Unit: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<select class="form-control" name="unit" id="unit" required>
								<option value="">Loading units...</option>
							</select>
						</div>
                        <div class="col-md-4">
							<label class="form-label">Unit Weight: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="unit_weight" id="unit_weight" required="" placeholder="Enter unit weight">
							
							<input type="hidden" name="raw_material_id" id="raw_material_id" required="" >
						</div>

					</div>
				</div>
				<div class="modal-footer">
					<button class="btn ripple btn-primary" type="submit">Edit</button>
					<button id="form-reset" class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Basic modal -->