<?php
// Include the main TCPDF library (search for installation path).
require_once('tcpdf_include.php');
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//set password protection if password protect
if ($RCMAIL->action == 'saveaspwdpdf') {
    // $pdf->SetProtection(array('print', 'copy', 'modify'), $_SESSION['username'], $_SESSION['username'] . "-master", 0, null);
    $pdf->SetProtection(array('print', 'copy', 'modify'), $PDF_PWD, $PDF_PWD . "-master", 0, null);
}

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('healthycareservice');
$pdf->SetTitle($HTML_PDF_TITLE);
$pdf->SetSubject($HTML_PDF_TITLE);
$pdf->SetKeywords('TCPDF, PDF, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $HTML_PDF_TITLE, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}


$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
$pdf->SetFont('helvetica', '', 14, '', true);

// Add a page
$pdf->AddPage();

// Print text using writeHTMLCell()
$pdf->writeHTML($HTML_PDF_SUMMARY);
$pdf->writeHTML($HTML_PDF_BODY, true, false, true, false, '');


// // Close and output PDF document
$pdf->Output($HTML_PDF_TITLE . '.pdf', 'D');

// //============================================================+
// // END OF FILE
// //============================================================+
