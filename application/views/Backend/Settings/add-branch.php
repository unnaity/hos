<!-- Basic modal -->
<div class="modal" id="add-branch">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'branches'?>" method="POST" id="add-<?php echo $page_title?>" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add <?php echo ucfirst($page_title)?></h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label">Branch name: <span class="tx-danger">*</span></label>
								<input class="form-control" name="branch_name" id="branch_name" value="<?php echo set_value('branch_name'); ?>" placeholder="Enter branch name" required="" type="text">
							</div>
							<div class="form-group">
								<label class="form-label">Address:</label>
								<textarea class="form-control" name="address" placeholder="Enter address"></textarea>
							</div>							
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="form-label">Legal name:</label>
								<input class="form-control" name="legal_name" value="" placeholder="Enter legal name" type="text">
							</div>
							<!--div class="form-group">
								<label class="form-label">Enabled functions:</label>
								<div class="row">
									<div class="col-lg-3">
										<label class="ckbox"><input type="checkbox" name="sell" checked value="Yes"><span>Sell</span></label>
									</div>
									<div class="col-lg-3 mg-t-20 mg-lg-t-0">
										<label class="ckbox"><input checked value="Yes" name="make" type="checkbox">
										<span>Make</span></label>
									</div>
									<div class="col-lg-3 mg-t-20 mg-lg-t-0">
										<label class="ckbox"><input checked value="Yes" name="buy" type="checkbox"><span>Buy</span></label>
									</div>
								</div>
							</div-->							
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