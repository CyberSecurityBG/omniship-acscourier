<?php

namespace Omniship\Acscourier\Http;
use Infifni\FanCourierApiClient\Client;


class TrackingParcelRequest extends AbstractRequest
{

    public function getData()
    {
        return [
            'ACSAlias' => 'ACS_TrackingDetails',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Voucher_No' => $this->getBolId(),
                ]
            ];
    }

    public function sendData($data)
    {
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    protected function createResponse($data)
    {
        dd($data);
        return $this->response = new TrackingParcelResponse($this, $data);
    }
}
