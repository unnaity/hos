<?php
require_once FILE_PATH.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$code='';
$grn_detail = $this->grn_detail;
$pdf_detail = $this->pdf_size;
$box_detail = $this->box_detail;
//  pr($box_detail);
$label_count = $pdf_detail->label_count;
$pdf_size_id = $pdf_detail->pdf_size_id;  
$pdf_height = $pdf_detail->pdf_height;
$pdf_width = $pdf_detail->pdf_width;
$no_of_box = $grn_detail->no_of_boxes;

$category_name = $grn_detail->category_name;
$vendor_name = $grn_detail->company_name;
$product_name = $grn_detail->product_name;

$pdf_size = $pdf_width.'x'.$pdf_height;
$page_orientation = $pdf_detail->page_orientation;
$i=1;
$invoice_html = '<page format="'.$pdf_size.'">   
    <table style="width: 99%;border: none;">';    
    foreach ($box_detail as $key) {
        $next_series = str_pad($key->box_no, BARCODE_LENGTH, 0, STR_PAD_LEFT);
		$qrcode = strtoupper($code).$next_series;
        $invoice_html .= '<tr><td colspan=2 style="text-align:center;height:18px;width:70mm;font-weight:bold;padding-bottom:2px;font-size:12px;word-break: break-all;">'.$product_name.'</td></tr>
        <tr>
        <td align="left"><qrcode value="'.$qrcode.'" ec="Q" style="width: 15mm; height: 21mm; border: none;" label="none"></qrcode></td>
        <td align="top" style="text-align:center;width:45mm;">
        <span style="text-align:center;font-weight:bold;font-size:12px;">Category - '.$category_name.'</span><br>
        <span style="text-align:left;font-weight:bold;font-size:12px;">Vendor - '.$vendor_name.'</span><br>
        <span style="text-align:left;font-weight:bold;font-size:12px;">Quantity - '.$key->remaining_item.'</span><br>
        <span style="text-align:left;font-weight:bold;font-size:12px;">GRN No. - '.$key->product_grn_no.'</span><br>
        <span style="text-align:left;font-weight:bold;font-size:14px;">This Box - '.$i.' / '.$no_of_box.' </span>        
        </td></tr>
        <tr><td align="middle" colspan=2 style="font-weight:bold;padding-left:26px;font-size:13px;">'.$qrcode.'</td></tr>';
        $i++;
    }
$invoice_html .= '</table></page>';
$width_in_mm = "250"; 
$height_in_mm = "500";
$html2pdf = new HTML2PDF('L', array($width_in_mm,$height_in_mm), 'en', true, 'UTF-8', array(2, 0, 0, 1));
$html2pdf->pdf->SetTitle("Box Slip");
$html2pdf->pdf->SetDisplayMode('real');
$html2pdf->writeHTML($invoice_html); // pass in the HTML
$html2pdf->output('Box-Slip.pdf'); // Generate the PDF and start download
exit;
?>