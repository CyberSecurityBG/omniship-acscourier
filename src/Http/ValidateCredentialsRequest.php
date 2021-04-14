<?php

namespace Omniship\Acscourier\Http;
class ValidateCredentialsRequest extends AbstractRequest
{


    public function getData()
    {
    }

    public function sendData($data)
    {
        $data = [
            'ACSAlias' => 'ACS_Stations',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Language' => 'EN'
            ]
        ];
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    protected function createResponse($data)
    {
      //  dd($data);
        return $this->response = new ValidateCredentialsResponse($this, $data);
    }

}
