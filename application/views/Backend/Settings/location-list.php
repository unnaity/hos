<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h4 class="text-start" >Location Details</h4>
				<div class="btn btn-list">
					<a class="btn ripple btn-info" href="<?php echo BASE_URL.'location-barcode/0'; ?>" title="Print All Barcode"><i class="fe fe-printer"></i></a>
					<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'add-location'?>" title="Add Supplier"><i class="fe fe-plus"></i></a>
				</div>
			</div>
			<!-- End Page Header -->
			<?php $this->load->view('Backend/Elements/Alert.php'); ?>
			<!-- Row -->
			<div class="row">
				<div class="col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
							
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>											
											<th>Floor</th>
											<th>Room</th>
											<th>Rack</th>
											<th>Shelf/Line</th>
											<th>Bin/Pallet</th>
											<th>Rack No.</th>
											<th>Remarks</th>
											<th>Barcode</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($location_list)):
										foreach($location_list as $obj): ?>
										<tr>											
											<td><?php echo $obj->floor_no ?></td>
											<td><?php echo $obj->room_no ?></td>
											<td><?php echo $obj->rack_no ?></td>
											<td><?php echo $obj->shelf_no ?></td>
											<td><?php echo $obj->bin_no ?></td>
											<td><?php echo $obj->location_name ?></td>
											<td><?php echo $obj->location_remarks ?></td>
											<td><a class="btn btn-sm btn-info" href="<?php echo BASE_URL.'location-barcode/'.$obj->location_id; ?>" target="_blank" title="Print Barcode"><i class="fe fe-printer"></i></a></td>
											<td>
											<!-- <a href="<?php echo BASE_URL.'location-edit/'.$obj->location_id?>">
													<button class="btn btn-sm btn-success" title="Edit">
														<i class="fa fa-edit"></i>
													</button> 
												</a> -->
											<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'location-delete/'.$obj->location_id?>');" class="btn btn-sm btn-danger" title="Delete">
													<i class="fe fe-trash-2"> </i>
												</button>
											</td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='5'>No record found!</td></tr>";
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