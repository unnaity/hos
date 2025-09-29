<?php
require_once FILE_PATH.'/vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

$location_detail = $this->location_list;
$pdf_detail = $this->pdf_size;

if($pdf_detail){
    $bar_width = $pdf_detail->pdf_width-20;
    $bar_height = $pdf_detail->pdf_height-25;
    $label_count = $pdf_detail->label_count;
    $pdf_size_id = $pdf_detail->pdf_size_id;          
    
    $pdf_height = $pdf_detail->pdf_height;
    $pdf_width = $pdf_detail->pdf_width;
    
}

    $pdf_size = $pdf_width.'x'.$pdf_height;
    $page_orientation = $pdf_detail->page_orientation;

$invoice_html = '<page format="'.$pdf_size.'" orientation="'.$page_orientation.'"><table style="width: 100%;border: none;">'; 
foreach($location_detail as $lObj){
    $invoice_html .= '<tr><td style="width:50mm;font-size:12px;font-weight:bold;padding-bottom:5px;text-align:center;">'.$lObj->branch_name.'@'.$lObj->store_name.'/'.$lObj->location_name.'</td></tr>';       
    $invoice_html .= '<tr>';
                    $invoice_html .= '<td style="justify-content: center;align-items: center;" ><barcode dimension="1D" type="C128" value="'.($lObj->location_no).'" label="none" style="vertical-align:middle;width:'.$bar_width.'mm; height:'.$bar_height.'mm;justify-content: center;align-items: center;"></barcode></td>';
    $invoice_html .= '</tr>';       
    $invoice_html .= '<tr><td style="padding-top:0px;padding-bottom:10px;font-weight:bold; text-align:center;">'.($lObj->location_no).'</td></tr>';       
}    
$invoice_html .= '</table></page>';
$width_in_mm = "250"; 
$height_in_mm = "500";
$html2pdf = new HTML2PDF('P', array($width_in_mm,$height_in_mm), 'en', true, '', array(10, 4, 0, 0));
$html2pdf->pdf->SetDisplayMode('real');
$html2pdf->writeHTML($invoice_html); // pass in the HTML
$html2pdf->output('Location Barcode.pdf'); // Generate the PDF and start download
exit;

?>
