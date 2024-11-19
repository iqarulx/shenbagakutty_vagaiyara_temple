<?php
$print_page = 1;
    include("../../include.php");
    include("../../include/number2words.php");

    $view_receipt_id = "";
    if(isset($_REQUEST['view_receipt_id'])) {
        $view_receipt_id = $_REQUEST['view_receipt_id'];
        $view_receipt_id = trim($view_receipt_id);
    }

    $receipt_date = ""; $receipt_number = ""; $status_base_member_id = ""; $member_name = ""; $funeral_to = ""; $amount = ""; $form_name = "";

    $view_receipt_list = array();
    if(!empty($view_receipt_id)) {
        $view_receipt_list = $obj->getAllReceiptList('', '', '', '', '', '', '', '', '', '', '', $view_receipt_id);
        if(!empty($view_receipt_list)) {
            foreach($view_receipt_list as $data) {
                if(!empty($data['receipt_date']) && $data['receipt_date'] != "0000-00-00 00:00:00") {
                    $receipt_date = date("d-m-Y", strtotime($data['receipt_date']));
                }
                if(!empty($data['receipt_number'])) {
                    $receipt_number = $data['receipt_number'];
                }
                if(!empty($data['status_base_member_id']) && $data['status_base_member_id'] != $GLOBALS['null_value']) {
                    $status_base_member_id = $data['status_base_member_id'];
                }
                if(!empty($data['member_name'])) {
                    $member_name = $data['member_name'];
                }
                if(!empty($data['funeral_to'])) {
                    $funeral_to = $data['funeral_to'];
                }
                if(!empty($data['amount'])) {
                    $amount = $data['amount'];
                }
                if(!empty($data['form_name'])) {
                    $form_name = $data['form_name'];
                }
            }
        }
    }

    require_once('../../../fpdf/fpdf.php');

    $pdf = new FPDF('L','mm','A5');
    $pdf->AliasNbPages(); 
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false);
    $pdf->SetTitle('Receipt');

    $yaxis = $pdf->GetY();
    

    $pdf->SetFont('Arial','B',11);

    if(!empty($form_name)) {
        $pdf->Image('../images/'.$form_name.'.jpg', 0, 5, 215, 150);
    }

    $amount_in_words = "";
    if(!empty($amount)) {
        $amount_in_words = getIndianCurrency($amount);
    }

    $pdf->SetTextColor(0,0,0);
    $pdf->setFillColor(255,255,255);

    $receipt_number_y = 45.5; $receipt_date_y = 44.5; $member_name_y = 57; $funeral_to_y = 68; $amount_y = 122; $amount_in_words_y = 90;
    if(!empty($receipt_y)) {
        $receipt_number_y = $receipt_number_y + $receipt_y;
        $receipt_date_y = $receipt_date_y + $receipt_y;
        $member_name_y = $member_name_y + $receipt_y;
        $funeral_to_y = $funeral_to_y + $receipt_y;
        $amount_y = $amount_y + $receipt_y;
        $amount_in_words_y = $amount_in_words_y + $receipt_y;
    }

    $pdf->SetY($receipt_number_y);
    $pdf->SetX(20);
    $pdf->Cell(30,5,$receipt_number,0,0,'L',1);

    $pdf->SetY($receipt_date_y);
    $pdf->SetX(165);
    $pdf->Cell(30,5,$receipt_date,0,0,'C',1);

    $pdf->SetY($member_name_y);
    $pdf->SetX(30);
    if(!empty($status_base_member_id)) {
        $pdf->Cell(130,5,$status_base_member_id." - ".$member_name,0,0,'C',1);
    }
    else {
        $pdf->Cell(130,5,$member_name,0,0,'C',1);
    }

    $pdf->SetY($funeral_to_y);
    $pdf->SetX(40);
    $pdf->Cell(115,5,$funeral_to,0,0,'C',1);

    $pdf->SetY($amount_y);
    $pdf->SetX(20);
    $pdf->Cell(30,5,$amount."/-",0,0,'L',1);

    $pdf->SetY($amount_in_words_y);
    $pdf->SetX(50);
    $pdf->Cell(100,5,$amount_in_words,0,0,'C',1);

    $output = "receipt";
    if(!empty($receipt_number)) {
        $output = $receipt_number;
    }

    $pdf->Output($output.".pdf", 'I');
?>