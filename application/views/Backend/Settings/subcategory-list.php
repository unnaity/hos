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
				<div class="col-lg-6">
					<div class="card custom-card">
						<div class="card-body">
							<div class="card-header border-bottom-0 p-0">
								<h4 class="text-start" style="float: left;"><?php echo ucfirst(str_replace('-',' ',$page_title))?></h4>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-subcategory" data-bs-toggle="modal" title="Add Subcategory"><i class="fe fe-plus"></i></a>
								</div>								
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:10%;">Sl. No.</th>
											<th style="width:40%;">Sub-Category Name</th>
											<th style="width:40%;">Category Name</th>											
											<th style="width:40%;">Action</th>
										</tr>
									</thead>
									<tbody>
									 <?php if($subcategory_list): $i=1;
										foreach($subcategory_list as $obj): ?>
										<tr>
											<td><?php echo $i; $i++; ?></td>
											<td><?php echo $obj->subcategory ?></td>
											<td><?php echo $obj->category_name ?></td>
											<td>
												<a href="<?php echo BASE_URL.'subcategory-edit/'.$obj->subcategory_id?>">
													<button class="btn btn-sm btn-success" title="Edit">
														<i class="fa fa-edit"></i>
													</button> 
												</a>
												<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'subcategory-delete/'.$obj->subcategory_id ?>');" class="btn btn-sm btn-danger" title="Delete">
													<i class="fe fe-trash-2"> </i>
												</button>
											</td>
										</tr>
									<?php endforeach;									
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
<?php $this->load->view('Backend/Settings/add-subcategory');?>


<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-subcategory').modal('show');
	});
</script>
<?php } ?>