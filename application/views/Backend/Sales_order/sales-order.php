<?php $client_detail = $this->client_detail; 
//$client_address_array = explode(',',$client_detail->address);
//$client_address = array_chunk($client_address_array, 3);
//$billing_address = $this->sales_order->billing_address;
$sales_order_product = $this->sales_order_product;
$discount=0;
$total = 0;
?>
<!-- Main Content-->
<div class="main-content pt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <!-- Page Header -->
            <div class="page-header" style="min-height:unset;">
                <h4 class="text-start" >Sales Order Detail</h4>
            </div>
            <!-- End Page Header -->

            <!-- Row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-lg-flex">
                                <h2 class="card-title mb-1"><?php echo "#".$this->sales_order->sales_order_no ?></h2>
                                <div class="ms-auto">
                                    <p class="mb-1"><span class="font-weight-bold">Invoice Date :</span> <?php echo date('d M Y',strtotime($this->sales_order->order_date)); ?></p>
                                    <p class="mb-0"><span class="font-weight-bold">Delivery Date :</span> <?php echo date('d M Y',strtotime($this->sales_order->delivery_date)); ?> </p>
                                </div>
                            </div>
                            <hr class="mg-b-40">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p class="h3">Invoice From:</p>
                                    <address>
                                        <?php echo $client_detail->client_name;?><br>
                                        <?php echo $client_detail->address;?><br>
                                        <?php echo $client_detail->contact_person_email;?>
                                    </address>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <p class="h3">Invoice To:</p>
                                    <address>
                                    <b><?php echo strtoupper($this->sales_order->company_name); ?></b><br>
                                    <?php echo $this->sales_order->billing_address; ?><br>
                                    <?php echo $this->sales_order->email; ?>
                                    </address>
                                </div>
                            </div>
                            <div class="table-responsive mg-t-40">
                                <table class="table table-invoice table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="wd-20p">Product Description</th>
                                            <th class="wd-40p">HSN Code</th>
                                            <th class="tx-center">Qty</th>
                                            <th class="tx-end">Rate</th>
                                            <th class="tx-end">Amount</th>
                                            <th class="tx-end">Discount</th>
                                            <th class="tx-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($sales_order_product){
                                            //pr($sales_order_product); 
                                            foreach($sales_order_product as $obj){
                                                $total = $total + (($obj->quantity*$obj->price_per_unit)-$discount);
                                        ?>
                                        <tr>
                                            <td><?php echo (!empty($obj->product_alias)?$obj->product_alias:$obj->product_name); ?></td>
                                            <td><?php echo $obj->hsn_code; ?></td>
                                            <td><?php echo $obj->quantity.' '.$obj->unit;?></td>
                                            <td><?php echo $obj->price_per_unit ?></td>
                                            <td class="tx-end"><?php echo number_format(($obj->quantity*$obj->price_per_unit),2); ?></td>
                                            <td class="tx-end"><?php echo $discount; ?></td>
                                            <td class="tx-end"><?php echo number_format(($obj->quantity*$obj->price_per_unit)-$discount,2); ?></td>
                                        </tr>
                                        <?php } } ?>                                       
                                        <tr>
                                            <td class="valign-middle" colspan="4" rowspan="4">
                                                <div class="invoice-notes">
                                                    <label class="main-content-label tx-13">Notes</label>
                                                   <?php echo $this->sales_order->additional_info; ?>
                                                </div><!-- invoice-notes -->
                                            </td>
                                            <td class="tx-end">Sub-Total</td>
                                            <td class="tx-end" colspan="2">0</td-->
                                        </tr>
                                        <tr>
                                            <td class="tx-end">Tax</td>
                                            <td class="tx-end" colspan="2">0</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-end">Discount</td>
                                            <td class="tx-end" colspan="2">0</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-end tx-uppercase tx-bold tx-inverse">Total Amount</td>
                                            <td class="tx-end" colspan="2">
                                                <h4 class="tx-bold"><?php echo number_format($total,2);?></h4>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-end">                           
                            <button type="button" class="btn ripple btn-info mb-1"
                                onclick="javascript:window.print();"><i class="fe fe-printer me-1"></i> Print
                                Invoice</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Row -->
        </div>
	</div>
</div>
<!-- End Main Content-->