<!-- <?php if(isset($product_grn_detail)):  $product_type = '';
//pr($product_grn_detail);
	foreach($product_grn_detail as $obj): 
        if($obj->grn_type_id == 1){
            $product_type = 'SFG';
        }else if($obj->grn_type_id == 2){
            $product_type = 'FG';
        }else if($obj->grn_type_id == 3){
            $product_type = 'Return';
        }
    ?> -->
	<tr>
		<td>
            <?php echo $obj->product_grn_no ?>
            <input type="hidden" id="product_grn_detail_id_<?php echo $obj->product_grn_detail_id ?>" name="product_grn_detail_id[]" value="<?php echo $obj->product_grn_detail_id?>" required>
        </td>
		<td><?php echo $obj->grn_date ?></td>
        <td><?php echo $product_type ?></td>
		<td><?php echo $obj->product_name ?></td>
		<td><?php echo $obj->company_name ?></td>
        <td>
            <?php echo $obj->no_of_items.' '.$obj->unit; ?>
            <input type="hidden" class="no_of_items" id="no_of_items_<?php echo $obj->product_grn_detail_id ?>" name="no_of_items[]" value="<?php echo $obj->no_of_items?>" required>
        </td>		
        <td><input type="number" class="form-control quality_checked_item" name="quality_checked_item[]" value="" required></td>
        <td><input type="text" class="form-control purchase_price_per_item" name="purchase_price_per_item[]" value="" required ></td>
		<td id="box_no_<?php echo $obj->product_grn_detail_id ?>">
            <input type="number" class="form-control no_of_boxes" id="no_of_boxes_<?php echo $obj->product_grn_detail_id ?>" name="no_of_boxes[]" value="" onchange="show_boxes('<?php echo $obj->product_grn_detail_id ?>')" required  >
            <div id="grn_id_<?php echo $obj->product_grn_detail_id ?>"></div>
        </td>		
		<!-- <td>
            <?php if($obj->grn_type_id == 3){ ?>
            <select name="product_status[]" class="form-control select2" required>
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
    var no_of_boxes = $("#no_of_boxes_"+grn_detail_id).val();
    
    if(no_of_boxes != ''){
        $.ajax ({
            type: 'POST',
            url: base_url+'show-input-box',
            data: {no_of_boxes:no_of_boxes,grn_detail_id:grn_detail_id},
            success : function(htmlresponse) {
                $('.rm-box-'+grn_detail_id).remove();
                //$(this).after(htmlresponse);
                $("#grn_id_"+grn_detail_id).append(htmlresponse); 
            }
        });
    }else{
        $('.rm-box-'+grn_detail_id).remove();
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
                $('.rm-box').remove();
                //$(this).after(htmlresponse);
                $(".grn_id").append(htmlresponse); 
            }
        });
    }else{
        $('.rm-box').remove();
    }
});
</script>