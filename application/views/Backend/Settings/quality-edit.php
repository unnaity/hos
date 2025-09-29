<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start mb-0"><?php echo UCWORDS(str_replace('-', ' ', $page_name)); ?></h4>
				<div class="btn btn-list">
					<a href="<?php echo BASE_URL . 'quality-list' ?>">
						<button aria-label="Close" class="btn ripple btn-dark btn-rounded" type="button"><span aria-hidden="true">&times;</span></button></a>
				</div>
			</div>
			<!-- End Page Header -->
			<!-- Row -->

			<div class="row">
				<div class="col-lg-6">
					<div class="card custom-card">
						<div class="card-body">
							<?php $this->load->view('Backend/Elements/Alert.php'); ?>
							<div class="panel panel-primary">
								<div class="panel-body tabs-menu-body">
									<div class="tab-content">
										<form action="" method="POST" data-parsley-validate="">
											<div class="row">
												<div class="col-md-12">
													<div class="form-group">
														<label class="form-label">Category: <span class="tx-danger">*</span></label>
														<select class="form-control select2" name="category_id" required>
															<option value="">Select category</option>
															<?php echo $this->category_option; ?>
														</select>
													</div>
												</div>
												<div class="col-md-12">
													<div class="form-group">
														<label class="form-label">Quality name: <span class="tx-danger">*</span></label>
														<input class="form-control" name="quality_name" id="quality_name" value="<?php echo $quality_detail->quality; ?>" placeholder="Enter quality name" required="" type="text">
													</div>
												</div>
											</div>

											<input name="quality_id" id="quality_id" value="<?php echo $quality_detail->quality_id; ?>" required="" type="hidden">
											<button class="btn ripple btn-primary" type="submit">Update</button>
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

<Style>
	.table>thead>tr>th {
		font-size: 13px;
		font-weight: 500;
		text-transform: unset;
	}

	.no-border {
		padding: 0;
		border: unset;
	}

	.richText .richText-editor {
		height: 80px;
	}
</style>