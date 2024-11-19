<?php
    $print_page = 1;
    include("../../include.php");
    include("../../include/number2words.php");

    $view_receipt_id = "";
    if(isset($_REQUEST['view_receipt_id'])) {
        $view_receipt_id = $_REQUEST['view_receipt_id'];
        $view_receipt_id = trim($view_receipt_id);
    }

    $receipt_date = ""; $receipt_number = ""; $member_unique_number = ""; $member_name = ""; $year_amount = ""; $poojai_from_date = "";
    $poojai_to_date = ""; $poojai_amount = ""; $amount = ""; $form_name = "";

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
                if(!empty($data['year_amount'])) {
                    $year_amount = $data['year_amount'];
                }
                if(!empty($data['poojai_from_date']) && $data['poojai_from_date'] != "0000-00-00") {
                    $poojai_from_date = date("d-m-Y", strtotime($data['poojai_from_date']));
                }
                if(!empty($data['poojai_to_date']) && $data['poojai_to_date'] != "0000-00-00") {
                    $poojai_to_date = date("d-m-Y", strtotime($data['poojai_to_date']));
                }
                if(!empty($data['poojai_amount'])) {
                    $poojai_amount = $data['poojai_amount'];
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
        $pdf->Image('../images/'.$form_name.'.png', 0, 0, 210, 150);
    }

    $receipt_year = ""; $receipt_month = ""; $year_from_to = "";
    if(!empty($receipt_date)) {
        $receipt_year = date("Y", strtotime($receipt_date));
        $receipt_month = date("m", strtotime($receipt_date));
        if(!empty($receipt_month) && !empty($receipt_year)) {
            $prev_year = $receipt_year - 1;
            $next_year = $receipt_year + 1;
            if($receipt_month == 1 || $receipt_month == 2 || $receipt_month == 3 || $receipt_month == 4) {
                $year_from_to = $prev_year." - ".$receipt_year;
            }
            else if($receipt_month > 4 && $receipt_month <= 12) {
                $year_from_to = $receipt_year." - ".$next_year;
            }
        }
    }

    $poojai_date = "";
    if(!empty($poojai_from_date) && !empty($poojai_to_date)) {
        $poojai_date = $poojai_from_date." & ".$poojai_to_date;
    }

    $amount_in_words = "";
    if(!empty($amount)) {
        $amount_in_words = getIndianCurrency($amount);
    }

    $pdf->SetTextColor(0,0,0);
    $pdf->setFillColor(255,255,255);

    $receipt_number_y = 54.5; $receipt_date_y = 54; $member_unique_number_y = 63.5; $member_name_y = 63; $year_from_to_y = 80; $year_amount_y = 80;
    $poojai_amount_y = 90; $poojai_date_y = 94; $amount_in_words_y = 103;
    if(!empty($receipt_y)) {
        $receipt_number_y = $receipt_number_y + $receipt_y;
        $receipt_date_y = $receipt_date_y + $receipt_y;
        $member_unique_number_y = $member_unique_number_y + $receipt_y;
        $member_name_y = $member_name_y + $receipt_y;
        $year_from_to_y = $year_from_to_y + $receipt_y;
        $year_amount_y = $year_amount_y + $receipt_y;
        $poojai_amount_y = $poojai_amount_y + $receipt_y;
        $poojai_date_y = $poojai_date_y + $receipt_y;
        $amount_in_words_y = $amount_in_words_y + $receipt_y;
    }

    $pdf->SetY($receipt_number_y);
    $pdf->SetX(60);
    $pdf->Cell(40,5,$receipt_number,0,0,'L',1);

    $pdf->SetY($receipt_date_y);
    $pdf->SetX(140);
    $pdf->Cell(30,5,$receipt_date,0,0,'L',1);

    $pdf->SetY($member_unique_number_y);
    $pdf->SetX(60);
    $pdf->Cell(40,5,$member_unique_number,0,0,'L',1);

    $pdf->SetY($member_name_y);
    $pdf->SetX(140);
    $pdf->Cell(65,5,$member_name,0,0,'L',1);

    $pdf->SetY($year_from_to_y);
    $pdf->SetX(45);
    $pdf->Cell(40,5,$year_from_to,0,0,'C',1);

    $pdf->SetY($year_amount_y);
    $pdf->SetX(150);
    $pdf->Cell(53,5,$year_amount."/-",0,0,'C',1);

    $pdf->SetY($poojai_amount_y);
    $pdf->SetX(150);
    $pdf->Cell(53,5,$poojai_amount."/-",0,0,'C',1);

    $pdf->SetY($poojai_date_y);
    $pdf->SetX(57);
    $pdf->Cell(55,5,$poojai_date,0,0,'C',1);

    $pdf->SetY($amount_in_words_y);
    $pdf->SetX(60);
    $pdf->Cell(145,5,$amount_in_words,0,0,'L',1);

    $output = "receipt";
    if(!empty($receipt_number)) {
        $output = $receipt_number;
    }

    $pdf->Output($output.".pdf", 'I');
?>