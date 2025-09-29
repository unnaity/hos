<!-- Basic modal -->
<div class="modal" id="add-trolley-type">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL.'trolley-type-list'?>" method="POST" id="add-<?php echo $page_title?>" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add <?php echo ucfirst($page_title)?></h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row row-xs align-items-center mg-b-20">
						<div class="col-md-4">
							<label class="form-label"><?php echo ucfirst($page_title)?>: <span class="tx-danger">*</span></label>
						</div>

						<div class="col-md-8 mg-t-5 mg-md-t-0">	
							<input class="form-control" name="trolley_type" id="trolley_type" value="<?php echo $trolley_type_detail->trolley_type ?? NULL; ?>" placeholder="Enter trolley type" required="" type="text">

							<input name="trolley_type_id" id="trolley_type_id" value="<?php echo $trolley_type_detail->trolley_type_id ?? 0; ?>" required="" type="hidden">
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