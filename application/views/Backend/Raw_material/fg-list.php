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
								<h3 class="text-start" style="float: left;">FG List</h3>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">

									<a class="btn ripple btn-success" href="#" data-bs-target="#add-fg" data-bs-toggle="modal" title="Add New Fg"><i class="fe fe-plus"></i></a>
								</div>
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>FG Code</th>
											<th>Sales Qty</th>
											<th>FG Description</th>
											<th>Alias Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php if ($this->fg_list): $i = 1;
											foreach ($this->fg_list as $obj): ?>
												<tr>
													<td> <?php echo $i++; ?> </td>
													<td><?php echo $obj->fg_code ?></td>
													<td><?php echo $obj->sales_qty ?></td>
													<td><?php echo $obj->fg_discription ?></td>
													<td>
														<a href="<?php echo BASE_URL . 'fg-alias/' . $obj->fg_id ?>" class="btn ripple btn-info btn-sm">Add Alias</a>
													</td>
													<td>
														<a class="btn btn-sm btn-success" href="javascript:void(0)" title="Edit FG" onclick="show_fg_edit_popup(
															'<?php echo $obj->fg_id ?>',
															'<?php echo htmlspecialchars($obj->fg_discription); ?>',
															'<?php echo htmlspecialchars($obj->fg_code); ?>',
															'<?php echo $obj->sales_qty ?>'
														)"><i class="fe fe-edit"></i></a>

														<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL . 'fg-delete/' . $obj->fg_id ?>');" class="btn btn-sm btn-danger" title="Delete">
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
<?php $this->load->view('Backend/Raw_material/add-fg'); ?>

<?php if (validation_errors() || $this->session->flashdata('error_message')) { ?>
	<script type="text/javascript">
		$(window).on('load', function() {
			$('#add-fg').modal('show');
		});
	</script>
<?php } ?>