<!-- Main Content-->
<div class="main-content pt-0">
	<div class="side-app">
		<div class="main-container container-fluid">
			<!-- Page Header -->
			<div class="page-header" style="min-height:unset;">
				<h3 class="text-start" ><?php echo UCWORDS(str_replace('-', ' ',$this->page_name)); ?></h3>
				<div>
					<h5>Total Value: <i class="fa fa-inr" aria-hidden="true"></i> <span id="t_value">0.00</span></h5>
				</div>
				<div class="btn btn-list">
					<a class="btn ripple btn-info" href="<?php echo BASE_URL.'product-stock-export/'.$this->category_id?>" title="Export in excel"><i class="fe fe-download"></i></a>
					<!--a href="<?php echo BASE_URL.'total-stock-value';?>">
					<button aria-label="Close" class="btn btn-secondary ripple btn-sm" type="button">Back</button></a-->
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
								<table id="example2" class="table table-bordered text-wrap" style="border-left:1px solid #e1e6f1;">
									<thead>
										<tr>
											<th>Sl. No.</th>
											<th>OEM</th>
											<th>Category</th>
											<th>Product Name</th>
											<th>Customer Name</th>
											<th>Last Sale</th>
                                            <th>Total Item</th>
											<th>Total Price</th>
											<th>Rack Location</th>
										</tr>
									</thead>
									<tbody>
									<?php if(isset($product_stock)): $i=1;  $total_value = 0; $total_item=0;
										foreach($product_stock as $obj): 
											$total_item = $total_item+$obj->total_item;
											$stl = '';
											$total_value = $total_value + $obj->total_value;

											if($obj->last_sale_day > 30 && $obj->last_sale_day < 60){
												$stl = "style='background-color:yellow'";
											}elseif($obj->last_sale_day > 59){
												$stl = "style='background-color:red'";
											}
										?>
										<tr <?php echo $stl; ?>>
											<td style="width:5%;"><?php echo $i;?></td>
											<td style="width:8%"><?php echo $obj->company_name?></td>
											<td style="width:8%"><?php echo $obj->category_name ?></td>
											<td style="width:15%"><?php echo $obj->product_name ?></td>
											<td style="width:18%"><?php echo strtoupper(str_replace(',',', ', $obj->customer_name)); ?></td>
											<td style="width:8%"><?php 
												if($obj->last_sale_day > 0){ echo $obj->last_sale_date_day; } ?></td>
											<td style="width:8%"><?php echo $obj->total_item; ?></td>
											<td style="width:8%"><i class="fa fa-inr" aria-hidden="true"></i> <?php echo number_format($obj->total_value,2)?></td>
											<td><?php 
													$array = explode(',',$obj->location_name);
													$vals = array_flip(array_count_values($array));
													
													foreach($vals as $key => $v){
														$location = [];	
														//echo $v.' - <b>'.$key.' Box </b>, ';
													 	$location[] = $v.' - <b>'.$key.' Box </b>';
													 	echo implode(',',$location);
													}													
													//echo $obj->location_name; 
												?>
											</td>
										</tr>
									<?php $i++; endforeach;
									else: echo "<tr><td colspan='11'>No record found!</td></tr>";
									endif;
									?>
										
									</tbody>
								</table>
								<input type="hidden" value="<?php echo number_format($total_value,2);?>" name="total_value" id="total_value">
								<input type="hidden" value="<?php echo $total_item;?>" name="total_item" id="total_item">
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