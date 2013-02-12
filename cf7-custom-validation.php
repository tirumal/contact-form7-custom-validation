<?php

//url regex
function get_valid_url( $url ) {
 
    $regex = "((https?|ftp)\:\/\/)?"; // Scheme
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?"; // Anchor
 
    return preg_match("/^$regex$/", $url);
 
}


// CF7 Custom Validation Rules

function compare_date($date1, $date2) {
  $date1 = explode('/', $date1);
	$date2 = explode('/', $date2);
	
	$output = false;
	
	if($date2[2] > $date1[2]) {
		$output = true;
	} else {
		$output = false;
	}
	
	if($date2[2] == $date1[2]) {
		if($date2[0] > $date1[0]) {
			$output = true;
		} else {
			$output = false;
		}
		
		if($date2[0] == $date1[0]) {
			if($date2[1] >= $date1[1]) {
				$output = true;
			} else {
				$output = false;
			}
		}
	}


	if ($output) {
		return true;
	} else { 
		return false;
	}
	

}


function cf7_custom_form_validation($result,$tag) {
	$type = $tag['type'];
	$name = $tag['name'];
	
	
	if($type == 'text*' && $_POST[$name] == ''){
			$result['valid'] = false;
			$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
	}

//__________________________________________________________________________________________________
	//Comparision date

	$date1 = $_POST['date1'];
	$date2 = $_POST['date2'];
	
	//CheckInDate
	if($name == 'date1'){
		if(!compare_date($date1, $date2)) {
			$result['valid'] = false;
			$result['reason'][$name] = 'Logical Error: Check in date should be before check out date';
		}
	}
	
//__________________________________________________________________________________________________

	//CheckOutDate
	if($name == 'date2'){
		if(!compare_date($date1, $date2)) {
			$result['valid'] = false;
			$result['reason'][$name] = 'Logical Error: Check out date should be After check in date';
		}
	}
	
//__________________________________________________________________________________________________

	//url
	if($name == 'url') {
		$url = $_POST['url'];
		
		if($url != '') {
			if(get_valid_url($url)){
				$result['valid'] = true;
			} else {
				$result['valid'] = false;
				$result['reason'][$name] = 'Entered URL is invalid.';
			}
		}
	}
	
	

	
//__________________________________________________________________________________________________
	
	//emailAddress
	if($name == 'emailAddress') {
		$emailAddress = $_POST['emailAddress'];
		
		if($emailAddress != '') {
			if(substr($emailAddress, 0, 1) == '.' || !preg_match('/^([*+!.&#$¦\'\\%\/0-9a-z^_`{}=?~:-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,4})$/i', $emailAddress)) {	
					$result['valid'] = false;
					$result['reason'][$name] = 'Entered Email is Invalid.';
			}
		}
	}

//__________________________________________________________________________________________________
	
	//datemdy
	if($name == 'datemdy'){
		$datemdy = $_POST['datemdy'];
		
		if($datemdy != '') {
			if(!preg_match('/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/', $datemdy)) {
				$result['valid'] = false;
				$result['reason'][$name] = 'Enter Date in MM/DD/YYYY Format';
			}
		}
	}
//__________________________________________________________________________________________________
	//US Zip code validation
	//USZipCode
	if($name == 'USZipCode') {
		$USZipCode = $_POST['USZipCode'];
		
		if($USZipCode != '') {
		//	if(!preg_match('/^([0-9]{5})(-[0-9]{4})?$/i', $USZipCode)) {
				if(!preg_match('/^\d{5}(-\d{4})?$/', $USZipCode)) {
				$result['valid'] = false;
				$result['reason'][$name] = 'Entered Zipcode is Invalid';
			}
		}
	}
	
	
//__________________________________________________________________________________________________
	// CANADA Zip code validation
	//CANZipCode
	if($name == 'CANZipCode') {
		$CANZipCode = $_POST['CANZipCode'];
		
		if($CANZipCode != '') {
				if(!preg_match('/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/', $CANZipCode)) {
				$result['valid'] = false;
				$result['reason'][$name] = 'Entered Zipcode is Invalid';
			}
		}
	}
	
//__________________________________________________________________________________________________
	// US and CANADA Zip code validation
	//USCANZipCode
	if($name == 'USCANZipCode') {
		$USCANZipCode = $_POST['USCANZipCode'];
		
		if($USCANZipCode != '') {
				if(!preg_match('/^[ABCEGHJKLMNPRSTVXY]{1}\d{1}[A-Z]{1} *\d{1}[A-Z]{1}\d{1}$/', $USCANZipCode)) {
						if(!preg_match('/^\d{5}(-\d{4})?$/', $USCANZipCode)) {
						$result['valid'] = false;
						$result['reason'][$name] = 'Entered Zipcode is Invalid';
					}
				}
		}
	}
	
	
//__________________________________________________________________________________________________
	// Indian Postal code validation
	//inPostalCode
	if($name == 'inPostalCode') {
		$inPostalCode = $_POST['inPostalCode'];
		
		if($inPostalCode != '') {
				if(!preg_match('/^[0-9]{6,6}$/', $inPostalCode)) {
						$result['valid'] = false;
						$result['reason'][$name] = 'Entered Pin code for India is Invalid';
				}
		}
	}
	
//__________________________________________________________________________________________________
	
	//fullName
	$allNames = array('fullName', 'fullName1');
	foreach($allNames as $uniNames) {
		if($name == $uniNames) {
			$fullName = $_POST[$uniNames];
			
				if($fullName != '') {
					//if(!preg_match('/^(\w+([\s-]\w+)?)+$/', $fullName) || is_numeric($fullName)) {
					$containsLettersOrNumbers = (preg_match('~[0-9a-z]~i', $fullName) > 0);
					if(!$containsLettersOrNumbers
					|| is_numeric($fullName)
					|| strlen($fullName > 64) ) {
						$result['valid'] = false;
						$result['reason'][$name] = 'Please Enter a Valid Name';
					}
				
					if(is_numeric($fullName)){
						$result['valid'] = false;
						$result['reason'][$name] = 'Please Enter a Valid Name';
					}
				}
			
		}
	}
//__________________________________________________________________________________________________		

	//acceptNum
	$acceptNumbers = array('acceptNumber1', 'acceptNumber2', 'acceptNumber3', 'acceptNumber4', 'acceptNumber5', 'acceptNumber6');
	
	foreach($acceptNumbers as $acceptNumber){
		if($name == $acceptNumber) {
			$acceptNum = $_POST[$acceptNumber];
			
			if($acceptNum != '') {
				if(ctype_digit($acceptNum)) {
					$result['valid'] = true;
				} else {
					$result['valid'] = false;
					$result['reason'][$name] = 'Please Enter Only Numbers';
				}
			}
		}
	}
	
	
	//__________________________________________________________________________________________________

	//faxNumber
	$faxNumbers = array('faxNumber1', 'faxNumber2', 'faxNumber3', 'faxNumber4', 'faxNumber5', 'faxNumber6');
	foreach($faxNumbers as $faxNum) {
		if($name == $faxNum) {
			$faxNumber = $_POST[$faxNum];
			$contRegex = '/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/';
			if ($faxNumber != '') {
				if(!preg_match($contRegex, $faxNumber) && strlen($faxNumber) > 10 && strlen($faxNumber) < 18) {
						$result['valid'] = false;
						$result['reason'][$name] = 'Entered Fax Number is Invalid';
				}
			}
		}
	}
	
//__________________________________________________________________________________________________


	//Only Characters	
	$OnlyChars = array('onlyChar1', 'onlyChar2');
	foreach($OnlyChars as $OnlyChar){
		if($name == $OnlyChar) {
			$onlyChar = $_POST[$OnlyChar];

			if($onlyChar != '') {
				$containsLettersOrNumbers = (preg_match('~[0-9a-z]~i', $onlyChar) > 0);
				if(!$containsLettersOrNumbers
					|| is_numeric($onlyChar)
					|| strlen($onlyChar > 64) ) {
						$result['valid'] = false;
						$result['reason'][$name] = 'Please Enter Only Characters';
				}
	
		  		if(is_numeric($onlyChar)){
			  			$result['valid'] = false;
			  			$result['reason'][$name] = 'Please Enter Only Characters';
		  		}
			}

		}
	}


//__________________________________________________________________________________________________

	//validPhone
	/*
	49-4312 / 777 777
	+1 (305) 613-0958 x101
	(305) 613 09 58 ext 101
	3056130958
	+33 1 47 37 62 24 extension 3
	(016977) 1234
	04312 - 777 777
	91-12345-12345
	+58 295416 7216
	*/
	
	$phoneNumbersAll = array('validPhone1', 'validPhone2', 'validPhone3', 'validPhone4', 'validPhone5', 'validPhone6');
	
	foreach($phoneNumbersAll as $validPhoneVal) {
		if($name == $validPhoneVal) {
			$validPhone = $_POST[$validPhoneVal];
			
			if($validPhone != '') {
				if(preg_match('/^(?:1(?:[. -])?)?(?:\((?=\d{3}\)))?([2-9]\d{2})(?:(?<=\(\d{3})\))? ?(?:(?<=\d{3})[.-])?([2-9]\d{2})[. -]?(\d{4})(?: (?i:ext)\.? ?(\d{1,5}))?$/', $validPhone) 
				
				|| preg_match('/^([\+][0-9]{1,3}[\ \.\-])?([\(]{1}[0-9]{2,6}[\)])?([0-9\ \.\-\/]{3,20})((x|ext|extension)[\ ]?[0-9]{1,4})?$/', $validPhone) 
				
				&& strlen($validPhone) > 9 
				
				&& strlen($validPhone) < 30 
				
				&& (int)($validPhone)) {
					//$result['valid'] = true;
				} else {
					$result['valid'] = false;
					$result['reason'][$name] = 'Entered Phone Number is Invalid';
				}
			}
		}
	}

//__________________________________________________________________________________________________
	return $result;
}

//add filter for text field validation
add_filter('wpcf7_validate_text','cf7_custom_form_validation', 10, 2); // text field
add_filter('wpcf7_validate_text*', 'cf7_custom_form_validation', 10, 2); // Req. text field


/*
TEXTAREA VALIDATIONS
*/

function cf7_custom_textarea_validation($result, $tag) {
	$type = $tag['type'];
	$name = $tag['name'];
	
	//if empty give required field error
	if($type == 'textarea*' && $_POST[$name] == ''){
			$result['valid'] = false;
			$result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
	}
	
	//validations begins
//__________________________________________________________________________________________________
// Address text area validation	

	if($name == 'addressTa'){
		$addressTa = $_POST['addressTa'];
		
		if($addressTa != '') {
			if(strlen($addressTa) > 300) {
				$result['valid'] = false;
				$result['reason'][$name] = 'You should not exceed 300 characters length';
			}
		}
	}
	
	return $result;
	
}

//add fiter for text area validation
add_filter( 'wpcf7_validate_textarea', 'cf7_custom_textarea_validation', 10, 2 );
add_filter( 'wpcf7_validate_textarea*', 'cf7_custom_textarea_validation', 10, 2 );

?>
