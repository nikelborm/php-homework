<?php
error_reporting(E_ALL);

/**
 * Validate credit card number
 * Returns true if $ccNum is in the proper credit card format.
 *
 * @param string $ccNum credit card number to validate
 * @param string|array $type if $type is set to 'fast', it validates the data against the major credit cards’ numbering formats.
 *        If $type is set to 'all', it validates the data against with all the credit card types.
 *        $type can also be set as array to validate against types you wish to match. For more accurate result use all.
 *		  Example: array('amex', 'bankcard', 'maestro')
 * @param string $regex A custom regex can also be passed, this will be used instead of the defined regex values.
 * @return bool Success
 *
 */
function validate_cc($ccNum, $type = 'all', $regex = null) {

	$ccNum = str_replace(array('-', ' '), '', $ccNum);
	if (mb_strlen($ccNum) < 13) {
		return false;
	}

	if ($regex !== null) {
		if (is_string($regex) && preg_match($regex, $ccNum)) {
			return true;
		}
		return false;
	}

	$cards = array(
		'all' => array(
			'amex'		=> '/^3[4|7]\\d{13}$/',
			'bankcard'	=> '/^56(10\\d\\d|022[1-5])\\d{10}$/',
			'diners'	=> '/^(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})$/',
			'disc'		=> '/^(?:6011|650\\d)\\d{12}$/',
			'electron'	=> '/^(?:417500|4917\\d{2}|4913\\d{2})\\d{10}$/',
			'enroute'	=> '/^2(?:014|149)\\d{11}$/',
			'jcb'		=> '/^(3\\d{4}|2100|1800)\\d{11}$/',
			'maestro'	=> '/^(?:5020|6\\d{3})\\d{12}$/',
			'mc'		=> '/^5[1-5]\\d{14}$/',
			'solo'		=> '/^(6334[5-9][0-9]|6767[0-9]{2})\\d{10}(\\d{2,3})?$/',
			'switch'	=>
			'/^(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)$/',
			'visa'		=> '/^4\\d{12}(\\d{3})?$/',
			'voyager'	=> '/^8699[0-9]{11}$/'
		),
		'fast' =>
		'/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})$/'
	);

	if (is_array($type)) {
		foreach ($type as $value) {
			$regex = $cards['all'][strtolower($value)];

			if (is_string($regex) && preg_match($regex, $ccNum)) {
				return true;
			}
		}
	} elseif ($type === 'all') {
		foreach ($cards['all'] as $value) {
			$regex = $value;

			if (is_string($regex) && preg_match($regex, $ccNum)) {
				return true;
			}
		}
	} else {
		$regex = $cards['fast'];

		if (is_string($regex) && preg_match($regex, $ccNum)) {
			return true;
		}
	}
	return false;
}


$test_cc = array(
	'', // in valid
	'34534trdsgfdgdfxgfdg3w653w45', // in valid
	'378282246310005', // valid
	'371449635398431', // valid
	'134449635398431', // in valid
	'6011111111111117',// valid
	'5105105105105100', // valid
	'4222222222222', // valid
	'4012888888881881', // valid
	'asdfasdsadsadsdas', // in valid
	'30569309025904', // valid
	'3530111333300000',  // valid
	'12345678912345',  // in valid
	'4070912798591', // valid
	'4716699760542841', // valid
	'3528503483993101',  // valid
	'180000193805365',  // valid
);

foreach($test_cc as $card_num) {
	if(validate_cc($card_num, 'all')) {
		echo $card_num.' <span style="color:green;">VALID</span>';
	} else {
		echo $card_num.' <span style="color:red;">INVALID</span>';
	}
	echo '<br>';
}
