<?php
$print_page = 1;
    include("../../include.php");
    include("../../include/number2words.php");

    $view_receipt_id = "";
    if(isset($_REQUEST['view_receipt_id'])) {
        $view_receipt_id = $_REQUEST['view_receipt_id'];
        $view_receipt_id = trim($view_receipt_id);
    }

    $receipt_date = ""; $receipt_number = ""; $member_unique_number = ""; $member_name = ""; $amount = ""; $function_date = ""; $count_for_mudikanikai = "";
    $count_for_kadhu_kuthu = ""; $form_name = "";

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
                if(!empty($data['member_unique_number'])) {
                    $member_unique_number = $data['member_unique_number'];
                }
                if(!empty($data['member_name'])) {
                    $member_name = $data['member_name'];
                }
                if(!empty($data['amount'])) {
                    $amount = $data['amount'];
                }
                if(!empty($data['function_date']) && $data['function_date'] != "0000-00-00") {
                    $function_date = date("d/m/Y", strtotime($data['function_date']));
                }
                if(!empty($data['count_for_mudikanikai'])) {
                    $count_for_mudikanikai = $data['count_for_mudikanikai'];
                }
                if(!empty($data['count_for_kadhu_kuthu'])) {
                    $count_for_kadhu_kuthu = $data['count_for_kadhu_kuthu'];
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
        $pdf->Image('../images/'.$form_name.'.png', 0, 0, 210, 150);
    }

    $amount_in_words = "";
    if(!empty($amount)) {
        $amount_in_words = getIndianCurrency($amount);
    }

    $pdf->SetTextColor(0,0,0);
    $pdf->setFillColor(255,255,255);

    $receipt_number_y = 54.5; $receipt_date_y = 54; $member_unique_number_y = 63.5; $member_name_y = 73; $function_date_y = 81; 
    $count_for_kadhu_kuthu_y = 90; $amount_y = 99; $amount_in_words_y = 108;

    if(!empty($receipt_y)) {
        $receipt_number_y = $receipt_number_y + $receipt_y;
        $receipt_date_y = $receipt_date_y + $receipt_y;
        $member_name_y = $member_name_y + $receipt_y;
        $function_date_y = $function_date_y + $receipt_y;
        $count_for_kadhu_kuthu_y = $count_for_kadhu_kuthu_y + $receipt_y;
        $member_unique_number_y = $member_unique_number_y + $receipt_y;
        $amount_y = $amount_y + $receipt_y;
        $amount_in_words_y = $amount_in_words_y + $receipt_y;
    }

    $pdf->SetY($receipt_number_y);
    $pdf->SetX(60);
    $pdf->Cell(40,5,$receipt_number,0,0,'L',1);

    $pdf->SetY($receipt_date_y);
    $pdf->SetX(170);
    $pdf->Cell(30,5,$receipt_date,0,0,'L',1);

    $pdf->SetY($member_unique_number_y);
    $pdf->SetX(60);
    $pdf->Cell(40,5,$member_unique_number,0,0,'L',1);

    $pdf->SetY($member_name_y);
    $pdf->SetX(60);
    $pdf->Cell(85,5,$member_name,0,0,'L',1);
 
    $pdf->SetY($function_date_y);
    $pdf->SetX(60);
    $pdf->Cell(30,5,$count_for_mudikanikai." - ".$function_date,0,0,'L',1);

    $pdf->SetY($count_for_kadhu_kuthu_y);
    $pdf->SetX(60);
    $pdf->Cell(30,5,$count_for_kadhu_kuthu,0,0,'L',1);

    $pdf->SetY($amount_y);
    $pdf->SetX(60);
    $pdf->Cell(40,5,$amount."/-",0,0,'L',1);

    $pdf->SetY($amount_in_words_y);
    $pdf->SetX(60);
    $pdf->Cell(140,5,$amount_in_words,0,0,'L',1);

    $output = "receipt";
    if(!empty($receipt_number)) {
        $output = $receipt_number;
    }

    $pdf->Output($output.".pdf", 'I');
?>