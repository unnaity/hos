<?php
require_once FILE_PATH.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$code='';
$dispatch_list = $this->dispatch_list;
$pdf_detail = $this->pdf_size;
$box_detail = $this->box_detail;
//pr($this->session->userdata());
$label_count = $pdf_detail->label_count;
$pdf_size_id = $pdf_detail->pdf_size_id;  
$pdf_height = $pdf_detail->pdf_height;
$pdf_width = $pdf_detail->pdf_width;
$product_name = $dispatch_list->product_name;
$so_no = $dispatch_list->sales_order_no;
$po_no = $dispatch_list->purchase_order_id;
$no_of_items = $dispatch_list->no_of_items;
$no_of_stickers = $dispatch_list->no_of_stickers;

if($no_of_stickers > 0){
    $no_of_items = $no_of_stickers;
}

if(!empty($po_no)){
    $p_text = 'P.O No. ';
    $p_val = $po_no;
}else{
    $p_text = 'S.O No. ';
    $p_val = $so_no;
}

$date = date('d-m-Y');
$pdf_size = $pdf_width.'x'.$pdf_height;
$page_orientation = $pdf_detail->page_orientation;
$i=1;
$invoice_html = '<page format="'.$pdf_size.'">   
    <table style="width: 99%;border: none;">';    
    for($i=1;$i<=$no_of_items;$i++) {
        $next_series = str_pad($dispatch_list->pick_list_id, BARCODE_LENGTH, 0, STR_PAD_LEFT);
		$qrcode = strtoupper($code).$next_series;
        $invoice_html .= '<tr><td colspan=2 style="text-align:center;height:18px;width:70mm;font-weight:bold;padding-bottom:2px;font-size:13px;word-break: break-all;">'.$this->user_detail->client_name.'</td></tr>
        <tr>
        <td align="left" style="padding-right:15px;"><qrcode value="'.$qrcode.'" ec="Q" style="width: 15mm; height: 23mm; border: none;" label="none"></qrcode></td>
        <td align="top" style="text-align:center;width:45mm;">
        <span style="text-align:center;font-weight:bold;font-size:14px;">'.$product_name.'</span><br><br>
        <span style="text-align:center;font-weight:bold;font-size:13px;">D - '.$date.'</span><br>
        <span style="text-align:left;font-weight:bold;font-size:13px;">'.$p_text.' - '.$p_val.'</span><br>       
        <span style="text-align:left;font-weight:bold;font-size:13px;">P - '.$i.' / '.$no_of_items.' </span>        
        </td></tr>';
        //$i++;
    }
$invoice_html .= '</table></page>';
$width_in_mm = "250"; 
$height_in_mm = "500";
$html2pdf = new HTML2PDF('L', array($width_in_mm,$height_in_mm), 'en', true, 'UTF-8', array(2, 0, 0, 1));

$html2pdf->pdf->SetTitle("Dispatch Item Slip");
$html2pdf->pdf->SetDisplayMode('real');
$html2pdf->writeHTML($invoice_html); // pass in the HTML
$html2pdf->output('Dispatch item slip.pdf'); // Generate the PDF and start download

exit;
?>