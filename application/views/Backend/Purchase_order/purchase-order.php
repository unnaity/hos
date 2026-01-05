<?php $client_detail = $this->client_detail; 
$purchase_order_product = $this->purchase_order_product;
$discount=0;
$total = 0;
?>
<!-- Main Content-->
<div class="main-content pt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <!-- Page Header -->
            <div class="page-header" style="min-height:unset;">
                <h4 class="text-start" >Purchase Order Detail</h4>
            </div>
            <!-- End Page Header -->

            <!-- Row -->
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="tx-center"><span style="margin-left: 91px;"><b><u>Purchase Order</u></b></span><span style="float: right;"><i>Original Copy</i></span></div>
                            <div class="tx-center">
                                <h2 class="mb-0"><?php echo strtoupper($this->purchase_order->company_name); ?></h2>
                            </div>
                            <div class="tx-center">
                                <?php echo $this->purchase_order->billing_address; ?>
                            </div>
                            <div class="tx-center"><b><i><?php echo "email: ".$this->purchase_order->email; ?></i></b>
                            </div>
                            <!--div class="d-lg-flex">
                                <h2 class="card-title mb-1">P.O. No. : <?php echo "#".$this->purchase_order->purchase_order_no ?></h2>
                                <div class="ms-auto">
                                    <p class="mb-1"><span class="font-weight-bold">P.O. Date :</span> <?php echo date('d M Y',strtotime($this->purchase_order->po_date)); ?></p>
                                    <p class="mb-0"><span class="font-weight-bold">Delivery Date :</span> <?php echo date('d M Y',strtotime($this->purchase_order->delivery_date)); ?> </p>
                                </div>
                            </div-->
                            <hr class="mg-b-40">
                            <div class="row">
                                <div class="col-lg-6">
                                    <p class="h4">Party Details :</p>
                                    <address>
                                        <?php echo $client_detail->client_name;?><br>
                                        <?php echo $client_detail->address;?><br>
                                        <?php echo $client_detail->contact_person_email;?>
                                    </address>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <p class="h4">Order No : <?php echo "#".$this->purchase_order->purchase_order_no ?></p>
                                    <p class="h4">Date : <?php echo date('d M Y',strtotime($this->purchase_order->po_date)); ?></p>
                                </div>
                            </div>
                            <div class="table-responsive mg-t-40">
                                <table class="table table-invoice table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="wd-20p">Product Code</th>
                                            <th class="wd-40p">Product Description</th>
                                            <th class="wd-20p">HSN Code</th>
                                            <th class="tx-center">Qty</th>
                                            <th class="tx-end">Rate</th>
                                            <th class="tx-end">Amount</th>
                                            <th class="tx-end">Discount</th>
                                            <th class="tx-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if($purchase_order_product){
                                            foreach($purchase_order_product as $obj){
                                                $total = $total + (($obj->quantity*$obj->price_per_unit)-$discount);
                                        ?>
                                        <tr>
                                            <td><?php echo $obj->category_name ?></td>
                                            <td><?php echo $obj->product_name ?></td>
                                            <td><?php echo $obj->hsn_code; ?></td>
                                            <td><?php echo $obj->quantity; ?></td>
                                            <td><?php echo number_format($obj->price_per_unit,2)?></td>
                                            <td class="tx-end"><?php echo number_format(($obj->quantity*$obj->price_per_unit),2); ?></td>
                                            <td class="tx-end"><?php echo $discount; ?></td>
                                            <td class="tx-end"><?php echo number_format(($obj->quantity*$obj->price_per_unit)-$discount,2); ?></td>
                                        </tr>
                                        <?php } } ?>                                       
                                        <tr>
                                            <td class="valign-middle" colspan="5" rowspan="4">
                                                <div class="invoice-notes">
                                                    <label class="main-content-label tx-13">Notes</label>
                                                   <?php echo $this->purchase_order->additional_info; ?>
                                                </div><!-- invoice-notes -->
                                            </td>
                                            <!-- td class="tx-end">Sub-Total</td>
                                            <td class="tx-end" colspan="2">$400.00</td-->
                                        </tr>
                                        <!--tr>
                                            <td class="tx-end">Tax</td>
                                            <td class="tx-end" colspan="2">3%</td>
                                        </tr>
                                        <tr>
                                            <td class="tx-end">Discount</td>
                                            <td class="tx-end" colspan="2">10%</td>
                                        </tr-->
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