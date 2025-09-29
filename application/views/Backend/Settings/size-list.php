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
								<h4 class="text-start" style="float: left;"><?php echo UCFIRST($page_title); ?>s</h4>
								<div class="btn btn-list" style="float: right;margin-top:-4px;">
									<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>								
									<a class="btn ripple btn-success" href="#" data-bs-target="#add-size" data-bs-toggle="modal" title="Add New Size"><i class="fe fe-plus"></i></a>
								</div>								
							</div>
							<div class="table-responsive">
								<table id="example2" class="table table-striped table-bordered text-nowrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th style="width:40%;">Size</th>
											<th style="width:40%;">Category Name</th>											
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php if($size_list):
										foreach($size_list as $obj): ?>
										<tr>
											<td><?php echo $obj->size ?></td>
											<td><?php echo $obj->category_name ?></td>
											<td>
											<a href="<?php echo BASE_URL.'size-edit/'.$obj->size_id?>">
													<button class="btn btn-sm btn-success" title="Edit">
														<i class="fa fa-edit"></i>
													</button> 
												</a>
												<?php echo get_delete_btn(BASE_URL.'delete-size/'.$obj->size_id); ?>
											</td>
										</tr>
									<?php endforeach;
									else: echo "<tr><td colspan='3' align='center'>No record found!</td></tr>";
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
<?php $this->load->view('Backend/Settings/add-size');?>

<?php if(validation_errors() || $this->session->flashdata('error_message')){ ?>
<script type="text/javascript">
    $(window).on('load', function() {
		$('#add-size').modal('show');
	});
</script>
<?php } ?>