<?php
error_reporting(E_ALL);

class CCValidator
{
  /**
   * Validate credit card number
   * Returns true if $ccNum is in the proper credit card format.
   *
   * @param string $ccNum credit card number to validate
   * @param string|array $type if $type is set to 'fast', it validates the data against the major credit cards numbering formats.
   *        If $type is set to 'all', it validates the data against with all the credit card types.
   *        $type can also be set as array to validate against types you wish to match. For more accurate result use all.
   *        Example: array('amex', 'bankcard', 'maestro')
   * @param string $regex A custom regex can also be passed, this will be used instead of the defined regex values.
   * @return bool Success
   *
   */
  function validate_cc_vendor($ccNum)
  {
    $ccNum = str_replace(array('-', ' '), '', $ccNum);
    if (mb_strlen($ccNum) < 13) {
      return false;
    }

    $cards = array(
        'visa'       => '/^(4\\d|14)\\d{11}(\\d{3})?$/',
        'mastercard' => '/^(5[1-5]|62|67)\\d{14}$/',
        'amex'       => '/^3[4|7]\\d{13}$/',
        'bankcard'   => '/^56(10\\d\\d|022[1-5])\\d{10}$/',
        'diners'     => '/^(?:3(0[0-5]|[68]\\d)\\d{11})|(?:5[1-5]\\d{14})$/',
        'discover'   => '/^(?:6011|650\\d)\\d{12}$/',
        'electron'   => '/^(?:417500|4917\\d{2}|4913\\d{2})\\d{10}$/',
        'enroute'    => '/^2(?:014|149)\\d{11}$/',
        'jcb'        => '/^(3\\d{4}|2100|1800)\\d{11}$/',
        'maestro'    => '/^(?:5020|6\\d{3})\\d{12}$/',
        'solo'       => '/^(6334[5-9][0-9]|6767[0-9]{2})\\d{10}(\\d{2,3})?$/',
        'switch'     => '/^(?:49(03(0[2-9]|3[5-9])|11(0[1-2]|7[4-9]|8[1-2])|36[0-9]{2})\\d{10}(\\d{2,3})?)|(?:564182\\d{10}(\\d{2,3})?)|(6(3(33[0-4][0-9])|759[0-9]{2})\\d{10}(\\d{2,3})?)$/',
        'voyager'    => '/^8699[0-9]{11}$/'
      );

      foreach ($cards as $bank => $regex) {
        if (is_string($regex) && preg_match($regex, $ccNum)) {
          return $bank;
        }
      }

    return false;
  }

  function isChecksumValid($cc) {
    $ccNum = preg_replace('/\D/', '', $cc);
    if (mb_strlen($ccNum) < 13) {
      return false;
    }
    $checksum = 0;
    for ($i = strlen($cc) - 1; $i >= 0; $i--) {
      $digit = (int) (((string)($cc))[$i]);
      if ((strlen($cc) - $i) % 2 == 0) {
          $digit *= 2;
          if ($digit > 9) {
              $digit -= 9;
          }
      }
      $checksum += $digit;
    }
    return $checksum % 10 == 0;
  }

  /**
   * test itself
   */
  function test_itself()
  {
    $test_cc = array( //vendor tests
      ''                             => false,
      '34534trdsgfdgdfxgfdg3w653w45' => false,
      '3782 82246310 005'            => true,
      '3782 8224 6310 006'           => true,
      '371449635398431'              => true,
      '4716639769075468'             => true,
      '5424308324237837'             => true,
      '134449635398431'              => false,
      '6011111111111117'             => true,
      '6011111111111118'             => true,
      '5105105105105100'             => true,
      '4222222222222'                => true,
      '4222222222223'                => false,
      '4012888888881881'             => true,
      'asdfasdsadsadsdas'            => false,
      '30569309025904'               => true,
      '3530111333300000'             => true,
      '12345678912345'               => false,
      '4070912798591'                => true,
      '4716699760542841'             => true,
      '3528503483993101'             => true,
      '180000193805365'              => true,
    );


    foreach ($test_cc as $card_num => $shouldBeValidForVendorCheck) {
      $bank = $this->validate_cc_vendor($card_num);
      echo '<br/>';
      if(!!$bank xor $shouldBeValidForVendorCheck) {
        echo $card_num . ' <span style="color:red;">Failed test card type:' . $bank . '</span>';
        continue;
      }
      if ($this->isChecksumValid($card_num)) {
        if ($bank) {
          echo $card_num . ' <span style="color:green;">VALID checksum and vendor = ' . $bank . '</span>';
        } else {
          echo $card_num . ' <span style="color:green;">VALID checksum, but vendor unknown</span>';
        }
      } else {
        echo $card_num . ' <span style="color:red;">INVALID CHECKSUM</span>';
      }
    }
  }
}

$instance = new CCValidator();
$instance->test_itself();

?>
