<!-- Basic modal -->
<div class="modal" id="edit-sfg">
	<div class="modal-dialog " role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'sfg-list'?>" method="POST" id="Edit SFG" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Edit SFG</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row row-xs align-items-center mg-b-20">
						<div class="col-md-4">
							<label class="form-label">SFG Name: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="sfgs_name" id="sfgs_name" required="" placeholder="Enter sfg name">
							
							
						</div>
                        <div class="col-md-4">
							<label class="form-label">SFG Code: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="sfgs_code" id="sfgs_code" required="" placeholder="Enter sfg code">
						</div>
                        <div class="col-md-4">
							<label class="form-label">Sustanability Score: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="su_score" id="su_score" required="" placeholder="Enter sustanability score">
						</div>
                        <div class="col-md-4">
							<label class="form-label">Unit Weight(kgs): <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input type="text" class="form-control" name="u_weight" id="u_weight" required="" placeholder="Enter unit weight">
							
							<input type="hidden" name="sfg_id" id="sfg_id" required="" >
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