<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;"></div>
			<!-- End Page Header -->
			<!-- Row -->
			<div class="row">
				<div class="col-lg-8">
					<div class="card custom-card">
						<div class="card-body">
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="card-header border-bottom-1 p-0" style="height:30px;">
								<h4 class="text-start mb-0" style="float: left;"><?php echo "Add Location"; ?></h4>
								<div class="btn btn-list" style="float: right;margin-top:-8px;">
									<a href="<?php echo BASE_URL . 'location-list' ?>">
										<button aria-label="Close" class="btn ripple btn-outline-light me-2 mb-2 btn-sm" type="button"><span aria-hidden="true">&times;</span></button></a>
								</div>
							</div>

							<div class="panel panel-primary">
								<div class="panel-body tabs-menu-body">
									<div class="tab-content">
										<form action="<?php echo BASE_URL . 'add-location' ?>" method="POST" data-parsley-validate="">
											<div class="row">
												
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Floor <span class="tx-danger">*</span></label>
														<select class="form-control select2-no-search show_location" name="floor_no" id="floor_no" required>
															<option value="">--Select--</option>
															<option value="Ground floor">Ground floor</option>
															<?php for ($i = 1; $i <= $store_detail->no_of_floor; $i++): ?>
																<option value="<?php echo "Floor " . $i; ?>">
																	<?php echo "Floor " . $i; ?>
																</option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Room <span class="tx-danger">*</span></label>
														<select class="form-control select2-no-search show_location" name="room_no" id="room_no" required>
															<option value="">--Select--</option>
															<?php for ($i = 1; $i <= $store_detail->no_of_room; $i++): ?>
																<option value="<?php echo "Room - " . $i; ?>">
																	<?php echo "Room " . $i; ?>
																</option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Rack <span class="tx-danger">*</span></label>
														<select class="form-control select2-no-search show_location" name="rack_no" id="rack_no" required>
															<option value="">--Select--</option>
															<?php for ($i = 1; $i <= $store_detail->no_of_rack; $i++): ?>
																<option value="<?php echo "Rack - " . $i; ?>">
																	<?php echo "Rack " . $i; ?>
																</option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Shelf/Line <span class="tx-danger">*</span></label>
														<select class="form-control select2-no-search show_location" name="shelf_no" id="shelf_no" required>
															<option value="">--Select--</option>
															<?php for ($i = 1; $i <= $store_detail->no_of_shelf; $i++): ?>
																<option value="<?php echo "Shelf - " . $i; ?>">
																	<?php echo "Shelf " . $i; ?>
																</option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Bin/Pallet <span class="tx-danger">*</span></label>
														<select class="form-control select2-no-search show_location" name="bin_no" id="bin_no" required>
															<option value="">--Select--</option>
															<?php for ($i = 1; $i <= $store_detail->no_of_bin; $i++): ?>
																<option value="<?php echo "Bin - " . $i; ?>">
																	<?php echo "Bin " . $i; ?>
																</option>
															<?php endfor; ?>
														</select>
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label">Location</label>
														<input type="text" class="form-control" name="location_name" id="location_name" value="" />
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="form-label mb-3">Location Remarks</label>
														<textarea class="form-control" rows="2" name="location_remarks"></textarea>
													</div>
												</div>
											</div>
											<button class="btn ripple btn-primary" type="submit">Save</button>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->
<style>
	.richText .richText-editor {
		height: 75px;
	}
</style>