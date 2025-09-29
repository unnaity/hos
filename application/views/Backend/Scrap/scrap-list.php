<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start" >Scrap List</h4>
				<div class="btn btn-list">
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'create-scrap'?>" title="Add Scrap"><i class="fe fe-plus"></i></a>
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
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>Box No</th>
											<th>Scrap Quantity</th>
											<th>Quantity</th>
											<th>Remark</th>
											<th> Create Date </th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($this->scrap_list)): $i=1;
										foreach($this->scrap_list as $obj): ?>
										<tr>
											<td><?php echo $i; $i++; ?></td>
                                            <td><?php echo $obj->scrap_box_no ?></td>
                                            <td><?php echo $obj->scrap_qty ?></td>
                                            <td><?php echo $obj->remaining_item ?></td>
											<td><?php echo $obj->remark ?></td>
											<td><?php echo $obj->created_date?></td>
											<td><button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'delete-scrap/'.$obj->scrap_id ?>');" class="btn btn-sm btn-danger" title="Delete">
													<i class="fe fe-trash-2"> </i>
												</button></td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='7' align='center'>No record found!</td></tr>";
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