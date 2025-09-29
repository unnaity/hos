<!-- Basic modal -->
<div class="modal" id="add-operations">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'tax-rates'?>" method="POST" id="add-<?php echo $page_title?>" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add <?php echo $page_title?></h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row row-xs align-items-center mg-b-20">
						<div class="col-md-4">
							<label class="form-label">Tax name: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0 mg-b-10">	
							<input class="form-control" name="tax_name" value="" placeholder="Enter tax name" required="" type="text">
						</div>

						<div class="col-md-4">
							<label class="form-label">Tax rate: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">	
							<input class="form-control" name="tax_rate" value="" placeholder="Enter tax rate" required="" type="number">
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