<?php
	// function getIndianCurrency(float $number)
	// {
	// 	$decimal = round($number - ($no = floor($number)), 2) * 100;
	// 	$hundred = null;
	// 	$digits_length = strlen($no);
	// 	$i = 0;
	// 	$str = array();
	// 	$words = array(0 => '', 1 => 'One', 2 => 'Two',
	// 		3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
	// 		7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
	// 		10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
	// 		13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
	// 		16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
	// 		19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
	// 		40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
	// 		70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
	// 	$digits = array('', 'hundred','thousand','lakh', 'crore');
	// 	while( $i < $digits_length ) {
	// 		$divider = ($i == 2) ? 10 : 100;
	// 		$number = floor($no % $divider);
	// 		$no = floor($no / $divider);
	// 		$i += $divider == 10 ? 1 : 2;
	// 		if ($number) {
	// 			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
	// 			$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
	// 			$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
	// 		} else $str[] = null;
	// 	}
	// 	$Rupees = implode('', array_reverse($str));
	// 	// $paise = ($decimal > 0) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	// 	$stri=array(); $j=''; $k='';
	// 	if($decimal>0)
	// 	{
	// 		$stri = (string)$decimal;
	// 		$j = $stri[0];
	// 		$k = $stri[1];
	// 	}
		
	// 	if($k==0){
	// 		$paise = ($decimal > 0) ? " and " . ($words[$decimal] ) . ' Paise' : '';
	// 	}
	// 	else
	// 	{
	// 		$paise = ($decimal > 0) ? " and " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
	// 	}
	// 	// $paise = ($decimal > 0) ? " and " . ($words[$j] .$words[$k]) . ' Paise' : '';
	// 	return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	// }

	function getIndianCurrency($number) {
		$no = round($number);
		$decimal = '';    
		$digits_length = strlen($no);    
		$i = 0;
		$str = array();
		$words = array(
			0 => '',
			1 => 'One',
			2 => 'Two',
			3 => 'Three',
			4 => 'Four',
			5 => 'Five',
			6 => 'Six',
			7 => 'Seven',
			8 => 'Eight',
			9 => 'Nine',
			10 => 'Ten',
			11 => 'Eleven',
			12 => 'Twelve',
			13 => 'Thirteen',
			14 => 'Fourteen',
			15 => 'Fifteen',
			16 => 'Sixteen',
			17 => 'Seventeen',
			18 => 'Eighteen',
			19 => 'Nineteen',
			20 => 'Twenty',
			30 => 'Thirty',
			40 => 'Forty',
			50 => 'Fifty',
			60 => 'Sixty',
			70 => 'Seventy',
			80 => 'Eighty',
			90 => 'Ninety');
		$digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
		while ($i < $digits_length) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
				$str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
			} else {
				$str [] = null;
			}  
		}
		
		$Rupees = implode(' ', array_reverse($str));
		
		$paise = ($decimal) ? "And  " . ($words[$decimal - $decimal%10]) ." " .($words[$decimal%10]).' Paise '  : '';
		// return ($Rupees ? 'Rupees ' . $Rupees : '') . $paise . " Only";
		return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
	}
?>