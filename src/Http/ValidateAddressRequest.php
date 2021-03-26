<?php

namespace Omniship\Acscourier\Http;
use function Symfony\Component\Translation\t;

class ValidateAddressRequest extends AbstractRequest
{


    public function getData()
    {
    }

    public function sendData($data)
    {
        $GetAddress = $this->getAddress();
        $address = $GetAddress->getCountry()->getName().', '.$GetAddress->getCity()->getName().', '.$GetAddress->getStreet()->getName().' '.$GetAddress->getStreetNumber();
        $res = [
            'ACSAlias' => 'ACS_Address_Validation',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
              //  'Language' => 'EN',
               // 'Address' => 'Αβραάμ Αντώνιου 9',
            ]
        ];
        return $this->createResponse($this->getClient()->SendRequest($res));
    }

    protected function createResponse($data)
    {
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
