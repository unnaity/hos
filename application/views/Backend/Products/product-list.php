<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header">
				<h4 class="text-start"><?php echo UCWORDS(str_replace('-',' ',$this->page_name)); ?></h4>
				<div class="btn btn-list">
					<a class="btn ripple btn-secondary" href="#" title="Download"><i class="fe fe-download"></i></a>				
					<a class="btn ripple btn-success" href="<?php echo BASE_URL.'add-product'?>" title="Add Product"><i class="fe fe-plus"></i></a>
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
											<th>OEM</th>
											<th>Category</th>
											<th style="width:20% !important">Product name</th>
											<th>Model</th>
											<th>Material/Quality</th>
											<th>Size</th>
											<th>Unit</th>
											<!--th>SKU</th>
											<th>Article Code</th-->
											<th>Alias Name</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($this->product_list)): $i=1;
										foreach($this->product_list as $obj): ?>
										<tr>
											<td style="width:5%"><?php echo $i; ?></td>
											<td><?php echo $obj->company_name?></td>
											<td><?php echo $obj->category_name ?></td>
											<td style="width:20%"><?php echo $obj->product_name ?></td>
											<td style="width:10%"><?php echo $obj->model?></td>
											<td><?php echo $obj->quality?></td>
											<td style="width:12%"><?php echo $obj->size?></td>
											<td><?php echo $obj->unit?></td>	
											<!--td><?php //echo $obj->sku?></td>
											<td><?php //echo $obj->article_code?></td-->
											<td>
												<a href="<?php echo BASE_URL.'product-alias/'.$obj->product_id?>" class="btn ripple btn-info btn-sm">Add Alias</a>
											</td>
											<td>
												<a href="<?php echo BASE_URL.'product-edit/'.$obj->product_id?>">
													<button class="btn btn-sm btn-success" title="Edit">
														<i class="fa fa-edit"></i>
													</button> 
												</a>
												<button id="bDel" type="button" onclick="confirm_modal('<?php echo BASE_URL.'product-delete/'.$obj->product_id ?>');" class="btn btn-sm btn-danger" title="Delete">
													<i class="fe fe-trash-2"> </i>
												</button>
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