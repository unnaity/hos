<!-- <?php if(isset($sfg_grn_detail)): ;
// pr($sfg_grn_detail);
foreach($sfg_grn_detail as $obj): 
?>  -->
<tr>
    <td>
        <?php echo $obj->sfg_grn_no ?>
        <input type="hidden" id="sfg_grn_detail_id_<?php echo $obj->sfg_grn_detail_id ?>" name="sfg_grn_detail_id[]" value="<?php echo $obj->sfg_grn_detail_id?>" required>
    </td>
    <td><?php echo date('Y-m-d',strtotime($obj->grn_date)) ?></td>
    <td><?php echo $product_type ?></td>
    <td><?php echo $obj->sfg_name ?></td>
    <td><?php echo $obj->company_name ?></td>
    <td>
        <?php echo $obj->quantity ; ?>
        <input type="hidden" class="no_of_items" id="no_of_items_<?php echo $obj->sfg_grn_detail_id ?>" name="no_of_items[]" value="<?php echo $obj->quantity?>" required>
    </td>
    <td>
        <?php echo $obj->no_of_boxes ?>
        <input type="hidden" class="no_of_boxes" id="no_of_boxes_<?php echo $obj->sfg_grn_detail_id ?>" name="no_of_boxes[]" value="<?php echo $obj->no_of_boxes?>" required>
    </td>		
    <td><input type="number" class="fosfg-control sfg_quality_checked_item" name="sfg_quality_checked_item[]" value="" required></td>
    <td><input type="number" class="fosfg-control sfg_quality_checked_boxes" name="sfg_quality_checked_boxes[]" value="" required></td>
    <td><input type="text" class="fosfg-control purchase_price_per_item" name="purchase_price_per_item[]" value="" required ></td>
    <td id="box_no_<?php echo $obj->sfg_grn_detail_id ?>">
        <input type="number" class="fosfg-control qc_no_of_boxes" id="qc_no_of_boxes_<?php echo $obj->sfg_grn_detail_id ?>" name="qc_no_of_boxes[]" value="" onchange="show_boxes('<?php echo $obj->sfg_grn_detail_id ?>')" required  >
        <div id="grn_id_<?php echo $obj->sfg_grn_detail_id ?>"></div>
    </td>		
    <!-- <td>
        <?php if($obj->grn_type_id == 3){ ?>
        <select name="product_status[]" class="fosfg-control select2" required>
            <option value="">Select</option>
            <option value="1">In Stock</option>
            <option value="2">Rejected</option>
        </select>
        <?php }else{
            echo "In Stock"; ?>
            <input type="hidden" name="product_status[]" value="1" required >
        <?php } ?>
    </td> -->
</tr>
<?php endforeach;
else: echo "<tr><td colspan='8'>No record found!</td></tr>";
endif;
?>
<script>

function show_boxes(grn_detail_id){
var no_of_boxes = $("#qc_no_of_boxes_"+grn_detail_id).val();

if(no_of_boxes != ''){
    $.ajax ({
        type: 'POST',
        url: base_url+'show-input-box',
        data: {no_of_boxes:no_of_boxes,grn_detail_id:grn_detail_id},
        success : function(htmlresponse) {
            $('.sfg-box-'+grn_detail_id).remove();
            //$(this).after(htmlresponse);
            $("#grn_id_"+grn_detail_id).append(htmlresponse); 
        }
    });
}else{
    $('.sfg-box-'+grn_detail_id).remove();
}
}

$(".no_of_boxes12").on('change', function() {
var no_of_boxes = $(this).val();
console.log(no_of_boxes);
alert('changes');
if(no_of_boxes != ''){
    $.ajax ({
        type: 'POST',
        url: base_url+'show-input-box',
        data: {no_of_boxes:no_of_boxes},
        success : function(htmlresponse) {
            $('.sfg-box').remove();
            //$(this).after(htmlresponse);
            $(".grn_id").append(htmlresponse); 
        }
    });
}else{
    $('.sfg-box').remove();
}
});
</script>