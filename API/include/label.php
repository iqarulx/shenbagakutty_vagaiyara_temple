<?php	
	date_default_timezone_set('Asia/Calcutta');
	$GLOBALS['create_date_time_label'] = date('Y-m-d H:i:s');
	
	$GLOBALS['site_name_user_prefix'] = "temple_".date("d-m-Y"); $GLOBALS['user_id'] = ""; $GLOBALS['creator'] = "";
	$GLOBALS['creator_details'] = ""; $GLOBALS['user_type'] = ""; $GLOBALS['user_name'] = ""; $GLOBALS['user_mobile_number'] = "";
	$GLOBALS['user_email'] = ""; $GLOBALS['user_login_id'] = ""; $GLOBALS['ip_address'] = ""; $GLOBALS['null_value'] = "NULL";

	$GLOBALS['admin_user_type'] = "Admin"; $GLOBALS['member_user_type'] = "Member";

	$GLOBALS['page_number'] = 1; $GLOBALS['page_limit'] = 10; $GLOBALS['page_limit_list'] = array("10", "25", "50", "100", "500", "1000");

	$GLOBALS['admin_folder_name'] = 'admin_temple_13122022'; $GLOBALS['backup_folder_name'] = 'backup';
	$GLOBALS['log_backup_file'] = $GLOBALS['backup_folder_name']."/logs/".date("Y").".csv";

	// Tables
	$GLOBALS['table_prefix'] = "temple"; $GLOBALS['private_settings_table'] = $GLOBALS['table_prefix'].'_private_settings';

	$GLOBALS['user_table'] = $GLOBALS['table_prefix'].'_user'; $GLOBALS['otp_send_phone_numbers_table'] = $GLOBALS['table_prefix'].'_otp_send_phone_numbers';
	$GLOBALS['login_table'] = $GLOBALS['table_prefix'].'_login'; $GLOBALS['settings_table'] = $GLOBALS['table_prefix'].'_settings';

	$GLOBALS['company_table'] = $GLOBALS['table_prefix'].'_company'; $GLOBALS['city_table'] = $GLOBALS['table_prefix'].'_city';

	$GLOBALS['status_table'] = $GLOBALS['table_prefix'].'_status'; $GLOBALS['profession_table'] = $GLOBALS['table_prefix'].'_profession';
	$GLOBALS['member_table'] = $GLOBALS['table_prefix'].'_members'; $GLOBALS['member_child_table'] = $GLOBALS['table_prefix'].'_member_child';

	$GLOBALS['mandapam_table'] = $GLOBALS['table_prefix'].'_mandapam'; $GLOBALS['mandapam_booking_table'] = $GLOBALS['table_prefix'].'_mandapam_booking';

	$GLOBALS['receipt_type_table'] = $GLOBALS['table_prefix'].'_receipt_type';
	$GLOBALS['new_thalakattu_table'] = $GLOBALS['table_prefix'].'_rpt_new_thalakattu';
	$GLOBALS['old_thalakattu_table'] = $GLOBALS['table_prefix'].'_rpt_old_thalakattu';
	$GLOBALS['new_member_registration_table'] = $GLOBALS['table_prefix'].'_rpt_new_member_registration';
	$GLOBALS['gold_silver_dollar_table'] = $GLOBALS['table_prefix'].'_rpt_gold_silver_dollar';
	$GLOBALS['pooja_donation_table'] = $GLOBALS['table_prefix'].'_rpt_pooja_donation';
	$GLOBALS['mudi_kaanikai_table'] = $GLOBALS['table_prefix'].'_rpt_mudi_kaanikai';
	$GLOBALS['nandavanam_table'] = $GLOBALS['table_prefix'].'_rpt_nandavanam';
	$GLOBALS['personal_savings_table'] = $GLOBALS['table_prefix'].'_rpt_personal_savings';
	$GLOBALS['general_donation_table'] = $GLOBALS['table_prefix'].'_rpt_general_donation';

	$GLOBALS['volunteer_table'] = $GLOBALS['table_prefix'].'_volunteer';
	$GLOBALS['notification_table'] = $GLOBALS['table_prefix'].'_notification';

	// Session Variables	
	if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'])) {
		$GLOBALS['user_id'] = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'];
		$GLOBALS['creator'] = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_id'];
	}	
	if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_name'])) {
		$GLOBALS['user_name'] = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_name'];
	}		
	if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_mobile_number'])) {
		$GLOBALS['user_mobile_number'] = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_mobile_number'];
	}
	if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_login_record_id'])) {
		$GLOBALS['user_login_record_id'] = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_login_record_id'];
	}
	if(!empty($_SESSION[$GLOBALS['site_name_user_prefix'].'_user_ip_address'])) {
		$GLOBALS['ip_address'] = $_SESSION[$GLOBALS['site_name_user_prefix'].'_user_ip_address'];
	}

	$GLOBALS['max_remarks_length'] = 200;

	$GLOBALS['rasi_list'] = array('Aries' => 'மேஷம்', 'Taurus' => 'ரிஷபம்', 'Gemini' => 'மிதுனம்', 'Cancer' => 'கடகம்', 'Leo' => 'சிம்மம்', 'Virgo' => 'கன்னி', 'Libra' => 'துலாம்', 'Scorpio' => 'விருச்சிகம்', 'Sagittarius' => 'தனுசு', 'Capricorn' => 'மகரம்', 'Aquarius' => 'கும்பம்', 'Pisces' => 'மீனம்');
	$GLOBALS['natchathiram_list'] = array('Aswinini' => 'அசுவினி', 'Barani' => 'பரணி', 'Krithikai' => 'கிருத்திகை', 'Rohini' => 'ரோகிணி', 'Mirukasheerisham' => 'மிருகசிரீஷம்', 'Tiruvadhirai' => 'திருவாதிரை', 'Punarpoosam' => 'புனர்பூசம்', 'Poosam' => 'பூசம்', 'Ayilyam' => 'ஆயில்யம்', 'Makam' => 'மகம்', 'Pooram' => 'பூரம்', 'Uthiram' => 'உத்திரம்', 'Astham' => 'அஸ்தம்', 'Chithirai' => 'சித்திரை', 'Swathi' => 'சுவாதி', 'Vishaakam' => 'விசாகம்', 'Anushyam' => 'அனுஷம்', 'Keattai' => 'கேட்டை', 'Moolam' => 'முலம்', 'Pooradam' => 'பூராடம்', 'Uthiraadam' => 'உத்திராடம்', 'Thiruvonam' => 'திருவோணம்', 'Avittam' => 'அவிட்டம்', 'Sathayam' => 'சதயம்', 'Poorattathi' => 'பூரட்டாதி', 'Uthiraadathi' => 'உத்திரட்டாதி', 'Revathi' => 'ரேவதி');

	$GLOBALS['rasi_english_list'] = array('Aries', 'Taurus', 'Gemini', 'Cancer', 'Leo', 'Virgo', 'Libra', 'Scorpio', 'Sagittarius', 'Capricorn', 'Aquarius', 'Pisces');
	$GLOBALS['natchathiram_english_list'] = array('Aswinini', 'Barani', 'Krithikai', 'Rohini', 'Mirukasheerisham', 'Tiruvadhirai', 'Punarpoosam', 'Poosam', 'Ayilyam', 'Makam', 'Pooram', 'Uthiram', 'Astham', 'Chithirai', 'Swathi', 'Vishaakam', 'Anushyam', 'Keattai', 'Moolam', 'Pooradam', 'Uthiraadam', 'Thiruvonam', 'Avittam', 'Sathayam', 'Poorattathi', 'Uthiraadathi', 'Revathi');

	$GLOBALS['rasi_aries_natchathiram_list'] = array('Aswinini' => 'அசுவினி', 'Barani' => 'பரணி', 'Krithikai' => 'கிருத்திகை');
	$GLOBALS['rasi_taurus_natchathiram_list'] = array('Krithikai' => 'கிருத்திகை', 'Rohini' => 'ரோகிணி', 'Mirukasheerisham' => 'மிருகசிரீஷம்');
	$GLOBALS['rasi_gemini_natchathiram_list'] = array('Mirukasheerisham' => 'மிருகசிரீஷம்', 'Tiruvadhirai' => 'திருவாதிரை', 'Punarpoosam' => 'புனர்பூசம்');
	$GLOBALS['rasi_cancer_natchathiram_list'] = array('Punarpoosam' => 'புனர்பூசம்', 'Poosam' => 'பூசம்', 'Ayilyam' => 'ஆயில்யம்');
	$GLOBALS['rasi_leo_natchathiram_list'] = array('Makam' => 'மகம்', 'Pooram' => 'பூரம்', 'Uthiram' => 'உத்திரம்');
	$GLOBALS['rasi_virgo_natchathiram_list'] = array('Uthiram' => 'உத்திரம்', 'Astham' => 'அஸ்தம்', 'Chithirai' => 'சித்திரை');
	$GLOBALS['rasi_libra_natchathiram_list'] = array('Chithirai' => 'சித்திரை', 'Swathi' => 'சுவாதி', 'Vishaakam' => 'விசாகம்');
	$GLOBALS['rasi_scorpio_natchathiram_list'] = array('Vishaakam' => 'விசாகம்', 'Anushyam' => 'அனுஷம்', 'Keattai' => 'கேட்டை');
	$GLOBALS['rasi_sagittarius_natchathiram_list'] = array('Moolam' => 'முலம்', 'Pooradam' => 'பூராடம்', 'Uthiraadam' => 'உத்திராடம்');
	$GLOBALS['rasi_capricorn_natchathiram_list'] = array('Uthiraadam' => 'உத்திராடம்', 'Thiruvonam' => 'திருவோணம்', 'Avittam' => 'அவிட்டம்');
	$GLOBALS['rasi_aquarius_natchathiram_list'] = array('Avittam' => 'அவிட்டம்', 'Sathayam' => 'சதயம்', 'Poorattathi' => 'பூரட்டாதி');
	$GLOBALS['rasi_pisces_natchathiram_list'] = array('Poorattathi' => 'பூரட்டாதி', 'Uthiraadathi' => 'உத்திரட்டாதி', 'Revathi' => 'ரேவதி');

	$GLOBALS['max_remarks_length'] = 200;

	$GLOBALS['gender_values'] = array("Male", "Female");

	$GLOBALS['receipt_type_corpus'] = "Corpus"; $GLOBALS['receipt_type_ordinary'] = "Ordinary";
	$GLOBALS['receipt_type_options'] = array($GLOBALS['receipt_type_corpus'], $GLOBALS['receipt_type_ordinary']);

	$GLOBALS['notification_type_general'] = "General"; $GLOBALS['notification_type_speical_date'] = "Special Date";
	$GLOBALS['notification_type_natchathiram'] = "Natchathiram";
	$GLOBALS['notification_type_options'] = array($GLOBALS['notification_type_general'], $GLOBALS['notification_type_speical_date'], $GLOBALS['notification_type_natchathiram']);
?>