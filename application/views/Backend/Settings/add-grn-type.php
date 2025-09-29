<!-- Basic modal -->
<div class="modal" id="add-grn-type">
	<div class="modal-dialog " role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'grn-type-list'?>" method="POST" id="add-<?php echo $page_title?>" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add Material Type</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row row-xs align-items-center mg-b-20">
						<div class="col-md-4">
							<label class="form-label">Material Type Name: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-6 mg-t-5 mg-md-t-5">	
							<input class="form-control" name="grn_type_name" required="" placeholder="Enter material type name"></input>							
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