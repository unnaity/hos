<!-- Basic modal -->
<div class="modal" id="add-<?php echo $page_title ?>">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-content-demo">
			<form action="<?php echo BASE_URL . 'category-list' ?>" method="POST" id="add-<?php echo $page_title ?>" data-parsley-validate="">
				<div class="modal-header">
					<h6 class="modal-title">Add FG</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<?php $this->load->view('Backend/Elements/error-message.php'); ?>
					<div class="row row-xs align-items-center"style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">FG Code: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<input class="form-control" name="fg_code" id="fg_code" value="" placeholder="Enter FG Code" required="" type="text">
						</div>
					</div>
					<div class="row row-xs align-items-center"style="margin-bottom: 15px;">
						<div class="col-md-4">
							<label class="form-label">Sales Qty: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<input class="form-control" name="sales_qty" id="sales_qty" value="" placeholder="Enter Sales Qty" required="" type="number">
						</div>
					</div>
					<div class="row row-xs align-items-center">
						<div class="col-md-4">
							<label class="form-label">Description: <span class="tx-danger">*</span></label>
						</div>
						<div class="col-md-8 mg-t-5 mg-md-t-0">
							<textarea class="form-control" name="description" id="description" value="" placeholder="Enter description" required="" type="text"></textarea>
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