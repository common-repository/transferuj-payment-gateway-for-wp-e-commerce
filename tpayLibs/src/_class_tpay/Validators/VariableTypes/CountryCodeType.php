<?php

/*
 * Created by tpay.com.
 * Date: 19.06.2017
 * Time: 14:39
 */
namespace tpayLibs\src\_class_tpay\Validators\VariableTypes;

use tpayLibs\src\_class_tpay\Utilities\TException;
use tpayLibs\src\_class_tpay\Validators\VariableTypesInterface;
use tpayLibs\src\Dictionaries\ISO_codes\CountryCodesDictionary;

class CountryCodeType implements VariableTypesInterface
{

    public function validateType($value, $name)
    {
        if (!is_string($value)
            || (strlen($value) !== 2 && strlen($value) !== 3)
            || (!in_array($value, CountryCodesDictionary::CODES))
        ) {
            throw new TException(
                sprintf('Field "%s" has invalid country code', $name)
            );
        }
    }
}
