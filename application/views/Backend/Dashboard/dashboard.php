<!-- Main Content-->


<div class="main-content pt-0">
	<div class="side-app">

		<div class="main-container container-fluid">

			<!-- Page Header -->
			<div class="page-header">
					<h2 class="main-content-title tx-24 mg-b-5">Welcome To Dashboard</h2>

			</div>
			<!-- End Page Header -->

			<!--Navbar-->
			<!-- <div class="responsive-background">
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<div class="advanced-search br-3">
						<div class="row align-items-center">
							<div class="col-md-12 col-xl-4">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group mb-lg-0">
											<label class="">From :</label>
											<div class="input-group">
												<div class="input-group-text">
													<i class="fe fe-calendar lh--9 op-6"></i>
												</div><input class="form-control fc-datepicker"
													placeholder="11/01/2019" type="text">
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group mb-lg-0">
											<label class="">To :</label>
											<div class="input-group">
												<div class="input-group-text">
													<i class="fe fe-calendar lh--9 op-6"></i>
												</div><input class="form-control fc-datepicker"
													placeholder="11/08/2019" type="text">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-6 col-xl-3">
								<div class="form-group mb-lg-0">
									<label class="">Sales By Country :</label>
									<select class="form-control select2-flag-search"
										data-placeholder="Select Contry">
										<option value="AF">Afghanistan</option>
										<option value="ZW">Zimbabwe</option>
									</select>
								</div>
							</div>
							<div class="col-md-6 col-xl-3">
								<div class="form-group mb-lg-0">
									<label class="">Products :</label>
									<select multiple="multiple" class="group-filter">
										<optgroup label="AVERY">
											<option value="1">Avery Chromo</option>
											
										<optgroup label="Other">
											<option value="1">Ribbon</option>
											
										</optgroup>
									</select>
								</div>
							</div>
							<div class="col-md-12 col-xl-2">
								<div class="form-group mb-lg-0">
									<label class="">Sales Type :</label>
									<select multiple="multiple" class="multi-select">
										<option value="1">Online</option>
										<option value="2">Offline</option>
										<option value="3">Reseller</option>
									</select>
								</div>
							</div>
						</div>
						<hr>
						<div class="text-end">
							<a href="#" class="btn btn-primary" data-bs-toggle="collapse"
								data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
								aria-expanded="false" aria-label="Toggle navigation">Apply</a>
							<a href="#" class="btn btn-secondary" data-bs-toggle="collapse"
								data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
								aria-expanded="false" aria-label="Toggle navigation">Reset</a>
						</div>
					</div>
				</div>
			</div>
			<!--End Navbar -->

			<!-- Row -->
			<div class="row row-sm">
				<div class="col-sm-4 col-xl-4">
					<div class="card custom-card">
						<div class="card-body dash1">
							<div class="d-flex">
								<p class="mb-1 tx-inverse">Inwards</p>
								<div class="ms-auto">
									<i class="fa fa-signal fs-20 text-info"></i>
								</div>
							</div>
							<div>
								<h3 class="dash-25"><a href="<?php echo BASE_URL . 'purchase-order-list' ?>"><?php echo ($this->purchase_count ?? 0); ?></a></h3>
							</div>
							<div class="progress mb-1">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70"
									class="progress-bar progress-bar-xs wd-40p bg-info" role="progressbar"></div>
							</div>
							<!-- <div class="expansion-label d-flex text-muted">
								<span class="text-muted">Last Month</span>
								<span class="ms-auto"><i class="fa fa-caret-up me-1 text-success"></i>0.9%</span>
							</div> -->
						</div>
					</div>
				</div>
				<!-- <div class="col-sm-6 col-xl-3 col-lg-6">
					<div class="card custom-card">
						<div class="card-body dash1">
							<div class="d-flex">
								<p class="mb-1 tx-inverse">Outward</p>
								<div class="ms-auto">
									<i class="fa fa-line-chart fs-20 text-primary"></i>
								</div>
							</div>
							<div>
								<h3 class="dash-25">568</h3>
							</div>
							<div class="progress mb-1">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70"
									class="progress-bar progress-bar-xs wd-70p" role="progressbar"></div>
							</div>
							<div class="expansion-label d-flex">
								<span class="text-muted">Last Month</span>
								<span class="ms-auto"><i class="fa fa-caret-up me-1 text-success"></i>0.7%</span>
							</div>
						</div>
					</div>
				</div> -->
				<div class="col-sm-4 col-xl-4">
					<div class="card custom-card">
						<div class="card-body dash1">
							<div class="d-flex">
								<p class="mb-1 tx-inverse">Dispatch Items</p>
								<div class="ms-auto">
									<i class="bi bi-truck fs-20 text-secondary"></i>
								</div>
							</div>
							<div>
								<h3 class="dash-25"><a href="<?php echo BASE_URL . 'dispatch-list' ?>"><?php echo ($this->dispatch_count ?? 0); ?></a></h3>
							</div>
							<div class="progress mb-1">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70"
									class="progress-bar progress-bar-xs wd-60p bg-secondary" role="progressbar">
								</div>
							</div>
							<!-- <div class="expansion-label d-flex">
								<span class="text-muted">Last Month</span>
								<span class="ms-auto"><i class="fa fa-caret-down me-1 text-danger"></i>0.43%</span>
							</div> -->
						</div>
					</div>
				</div>
				<div class="col-sm-4 col-xl-4">
					<div class="card custom-card">
						<div class="card-body dash1">
							<div class="d-flex">
								<p class="mb-1 tx-inverse">Sales Order</p>
								<div class="ms-auto">
									<i class="fa fa-line-chart fs-20 text-success"></i>
								</div>
							</div>
							<div>
								<h3 class="dash-25">
									<a href="<?php echo BASE_URL . 'sales-order-list' ?>"><?php echo ($this->sales_count ?? 0); ?></a>
							</div>
							<div class="progress mb-1">
								<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70"
									class="progress-bar progress-bar-xs wd-50p bg-success" role="progressbar"></div>
							</div>
							<!-- <div class="expansion-label d-flex text-muted">
								<span class="text-muted">Last Month</span>
								<span class="ms-auto"><i class="fa fa-caret-down me-1 text-danger"></i>1.44%</span>
							</div>  -->
						</div>
					</div>
				</div>

			</div>
			<!--End  Row -->

			<!-- Row -->
			 <?php 
			 $total_stock = $this->Stocks->total_stock_value(array('store_id' => $this->store_id));
			 $total_value = 0;
		 foreach ($total_stock as $ts) {
			$total_value = $total_value + $ts->total_value;
		}?>
			<div class="row row-sm">
				<div class="col-sm-12 col-xl-12 col-lg-12">
					<div class="card custom-card overflow-hidden">
						<div class="card-body">
							<div style="text-align:center">
								<h5>Total Value: <i class="fa fa-inr"></i> <span id=""><?php echo number_format($total_value, 2); ?></span></h5>
							</div>
							<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

							<canvas id="myChart" style="width:100%;max-width:950px"></canvas>


							<script>
								const xValues = <?php echo $this->category_name; ?>;
								const yValues = <?php echo $this->value_percentage; ?>;
								const barColors = Array.from({
										length: xValues.length
									},
									() =>
									'#' + '008000');

								new Chart("myChart", {
									type: "bar",
									data: {
										labels: xValues,
										datasets: [{
											backgroundColor: barColors,
											data: yValues
										}]
									},
									options: {
										legend: {
											display: false
										},
										title: {
											display: false,
											text: "Total Stock Value"
										},
										scales: {
											xAxes: [{
												gridLines: {
													display: false
												}
											}],
											yAxes: [{
												gridLines: {
													display: false
												}
											}]
										}


									}
								});
							</script>
						</div>

					</div>
				</div>
			</div>
			<!-- <div class="col-sm-12 col-lg-4 col-xl-4">
					<div class="card custom-card">
						<div class="card-body">
							<div>
								<h6 class="card-title mb-1">Cost BreakDown</h6>
								<p class="text-muted card-sub-title">Excepteur sint occaecat cupidatat non proident.
								</p>
							</div>
							<div class="row">
								<div class="col-6 col-md-6 text-center">
									<div class="mb-2">
										<span class="peity-donut"
											data-peity='{ "fill": ["#eb6f33", "#e1e6f1"], "innerRadius": 14, "radius": 20 }'>4/7</span>
									</div>
									<p class="mb-1 tx-inverse">Marketing</p>
									<h4 class="mb-1"><span>$</span>67,927</h4>
									<span class="text-muted fs-12"><i
											class="fa fa-caret-up me-1 text-success"></i>54% last month</span>
								</div>
								<div class="col-6 col-md-6 text-center">
									<div class="mb-2">
										<span class="peity-donut"
											data-peity='{ "fill": ["#01b8ff", "#e1e6f1"], "innerRadius": 14, "radius": 20 }'>2/7</span>
									</div>
									<p class="mb-1 tx-inverse">Sales</p>
									<h4 class="mb-1"><span>$</span>24,789</h4>
									<span class="text-muted fs-12"><i
											class="fa fa-caret-down me-1 text-danger"></i>33% last month</span>
								</div>
							</div>
						</div>
					</div>
					<div class="card custom-card">
						<div class="card-body">
							<div>
								<h6 class="card-title mb-1">Monthly Profits</h6>
								<p class="text-muted card-sub-title">Excepteur sint occaecat cupidatat non proident.
								</p>
							</div>
							<h3><span>$</span>22,534</h3>
							<div class="clearfix mb-3">
								<div class="clearfix">
									<span class="float-start text-muted">This Month</span>
									<span class="float-end">75%</span>
								</div>
								<div class="progress mt-1">
									<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="70"
										class="progress-bar progress-bar-xs wd-70p bg-primary" role="progressbar">
									</div>
								</div>
							</div>
							<div class="clearfix">
								<div class="clearfix">
									<span class="float-start text-muted">Last Month</span>
									<span class="float-end">50%</span>
								</div>
								<div class="progress mt-1">
									<div aria-valuemax="100" aria-valuemin="0" aria-valuenow="50"
										class="progress-bar progress-bar-xs wd-50p bg-success" role="progressbar">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- End Row -->
		

			<!-- Row-->
			<!-- <div class="row">
				<div class="col-sm-12 col-xl-12 col-lg-12">
					<div class="card custom-card">
						<div class="card-body">
							<div>
								<h6 class="card-title mb-1">Product Summary</h6>
								<p class="text-muted card-sub-title">Nemo enim ipsam voluptatem fugit sequi
									nesciunt.</p>
							</div>
							<div class="table-responsive br-3">
								<table class="table table-bordered text-nowrap mb-0">
									<thead>
										<tr>
											<th>#No</th>
											<th>Client Name</th>
											<th>Product ID</th>
											<th>Product</th>
											<th>Product Cost</th>
											<th>Payment Mode</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>#01</td>
											<td>Sean Black</td>
											<td>PRO12345</td>
											<td>Mi LED Smart TV 4A 80</td>
											<td>$14,500</td>
											<td>Online Payment</td>
											<td><span class="badge bg-success">Delivered</span></td>
										</tr>
										<tr>
											<td>#02</td>
											<td>Evan Rees</td>
											<td>PRO8765</td>
											<td>Thomson R9 122cm (48 inch) Full HD LED TV </td>
											<td>$30,000</td>
											<td>Cash on delivered</td>
											<td><span class="badge bg-primary">Add Cart</span></td>
										</tr>
										<tr>
											<td>#03</td>
											<td>David Wallace</td>
											<td>PRO54321</td>
											<td>Vu 80cm (32 inch) HD Ready LED TV</td>
											<td>$13,200</td>
											<td>Online Payment</td>
											<td><span class="badge bg-secondary">Pending</span></td>
										</tr>
										<tr>
											<td>#04</td>
											<td>Julia Bower</td>
											<td>PRO97654</td>
											<td>Micromax 81cm (32 inch) HD Ready LED TV</td>
											<td>$15,100</td>
											<td>Cash on delivered</td>
											<td><span class="badge bg-info">Delivering</span></td>
										</tr>
										<tr>
											<td>#05</td>
											<td>Kevin James</td>
											<td>PRO4532</td>
											<td>HP 200 Mouse &amp; Wireless Laptop Keyboard </td>
											<td>$5,987</td>
											<td>Online Payment</td>
											<td><span class="badge bg-danger">Shipped</span></td>
										</tr>
										<tr>
											<td>#06</td>
											<td>Theresa Wright</td>
											<td>PRO6789</td>
											<td>Digisol DG-HR3400 Router </td>
											<td>$11,987</td>
											<td>Cash on delivered</td>
											<td><span class="badge bg-secondary">Delivering</span></td>
										</tr>
										<tr>
											<td>#07</td>
											<td>Sebastian Black</td>
											<td>PRO4567</td>
											<td>Dell WM118 Wireless Optical Mouse</td>
											<td>$4,700</td>
											<td>Online Payment</td>
											<td><span class="badge bg-info">Add to Cart</span></td>
										</tr>
										<tr>
											<td>#08</td>
											<td>Kevin Glover</td>
											<td>PRO32156</td>
											<td>Dell 16 inch Laptop Backpack </td>
											<td>$678</td>
											<td>Cash On delivered</td>
											<td><span class="badge bg-success">Delivered</span></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				</div> -->
			<!-- End Row -->
		</div>
	</div>
</div>
<!-- End Main Content-->