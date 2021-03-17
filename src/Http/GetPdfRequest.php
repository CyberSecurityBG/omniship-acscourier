<?php
namespace Omniship\Acscourier\Http;

class GetPdfRequest extends AbstractRequest
{
    /**
     * @return integer
     */
    public function getData() {
        return [
            'ACSAlias' => 'ACS_Print_Voucher',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Voucher_No' => $this->getBolId(),
                'Print_Type' => 2,
                'Start_Position' => 1
            ]
        ];
    }

    /**
     * @param mixed $data
     * @return GetPdfResponse
     */
    public function sendData($data) {
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    /**
     * @param $data
     * @return GetPdfResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new GetPdfResponse($this, $data);
    }

}
