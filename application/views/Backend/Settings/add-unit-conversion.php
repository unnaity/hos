<!-- Basic modal -->
<div class="modal" id="add-unit-conversion">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'unit-conversion'?>" method="POST" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add Unit Conversion</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row">	
						<div class="col-md-3">	
							<div class="form-group">
								<label class="form-label">From Unit Value:<span class="tx-danger">*</span></label>
								<input class="form-control" name="from_unit_value" id="from_unit_value" value="" type="text" required>
							</div>
						</div>
						<div class="col-md-3">	
							<div class="form-group">
								<label class="form-label">From Unit:<span class="tx-danger">*</span></label>
								<select class="form-control select2" name="from_unit_id" required id="from_unit_id">
									<option value="">Select Unit</option>
									<?php echo $this->uom_option;?>
								</select>
							</div>
						</div>
						<div class="col-md-3">	
							<div class="form-group">
								<label class="form-label">To Unit Value:<span class="tx-danger">*</span></label>
								<input class="form-control" name="to_unit_value" id="to_unit_value" value="" type="text" required>
							</div>
						</div>
						<div class="col-md-3">	
							<div class="form-group">
								<label class="form-label">To Unit:<span class="tx-danger">*</span></label>
								<select class="form-control select2" name="to_unit_id" required id="to_unit_id">
									<option value="">Select Unit</option>
									<?php echo $this->uom_option;?>
								</select>
							</div>
						</div>
					</div>	
				</div>
				<div class="modal-footer">
					<button class="btn ripple btn-primary tx-center" type="submit">Save</button>
					<button id="form-reset" class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Basic modal -->
<style>
	.select2-container { z-index: 99999 !important;}
</style>