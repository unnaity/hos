<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;"></div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
							<div class="card-header border-bottom-0 p-0">
								<h3 class="text-start" style="float: left;">RM List</h3>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-raw-material" data-bs-toggle="modal" title="Add New RM"><i class="fe fe-plus"></i></a>
								</div>
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>RM Name</th>
											<th>RM Code</th>
											<th>Sustainability Score</th>
											<th>Weight</th>
											<th>Unit</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($this->raw_material_list): $i = 1;
											foreach ($this->raw_material_list as $obj): ?>
												<tr>
													<td> <?php echo $i++; ?> </td>
													<td><?php echo $obj->raw_material_name ?></td>
													<td><?php echo $obj->raw_material_code ?></td>
													<td><?php echo $obj->sustainability_score ?></td>
													<td><?php echo $obj->weight ?></td>
													<td><?php echo strtoupper($obj->unit) ?></td>
													<td>
														<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit RM" onclick="show_edit_popup(
																'<?php echo $obj->raw_material_id ?>',
																'<?php echo htmlspecialchars($obj->raw_material_name); ?>',
																'<?php echo htmlspecialchars($obj->raw_material_code); ?>',
																'<?php echo $obj->unit ?>',
																'<?php echo $obj->sustainability_score ?>',
																'<?php echo $obj->weight ?>'
															)"><i class="fe fe-edit"></i></a>

														<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL . 'raw-material-delete/' . $obj->raw_material_id ?>');" class="btn btn-sm btn-danger" title="Delete">
															<i class="fe fe-trash-2"> </i>
														</button>
													</td>
												</tr>
										<?php endforeach;
										else: echo "<tr><td colspan='6'>No record found!</td></tr>";
										endif;
										?>

									</tbody>
								</table>
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
<?php $this->load->view('Backend/Raw_material/add-raw-material'); ?>
<?php $this->load->view('Backend/Raw_material/raw-material-edit'); ?>

<?php if (validation_errors() || $this->session->flashdata('error_message')) { ?>
	<script type="text/javascript">
		$(window).on('load', function() {
			$('#add-raw-material').modal('show');
		});
		$(window).on('load', function() {
			$('#raw-material-edit').modal('show');
		});
	</script>
<?php } ?>