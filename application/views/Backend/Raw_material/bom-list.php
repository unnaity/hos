<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start">BOM List</h4>
				<div class="btn btn-list">				
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'create-bom'?>" title="Add BOM"><i class="fe fe-plus"></i></a>
				</div>
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/success-message.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
							
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>FG Name</th>
											<th>FG Code</th>
											<th>Total Item</th>
											<th>Total Quantity</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($bom_list)): $i=1;
										foreach($bom_list as $obj): ?>
										<tr>
											<td style="width:5%"><?php echo $i; ?></td>
											<td><?php echo strtoupper($obj->fg_discription) ?></td>
											<td><?php echo strtoupper($obj->fg_code) ?></td>
											<td><?php echo $obj->item_count ?></td>
											<td><?php echo $obj->qty ?></td>
											<td>
                                                <a class="btn btn-sm btn-info" href="<?php echo BASE_URL.'bom-detail/'.$obj->fg_id; ?>" title="View Detail"><i class="fe fe-eye"></i><br>
												<a href="<?php echo BASE_URL.'bom-edit/' . $obj->fg_id; ?>">
													<button class="btn btn-sm btn-success" title="Edit">
														<i class="fa fa-edit"></i>
													</button> </a>
													<?php echo get_delete_btn(BASE_URL.'delete-bom/'.$obj->fg_id); ?>
											</td>
										</tr>
									<?php $i++; endforeach;
									else: echo "<tr><td colspan='11'>No record found!</td></tr>";
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