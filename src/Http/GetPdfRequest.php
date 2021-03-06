<?php
namespace Omniship\Acscourier\Http;

class GetPdfRequest extends AbstractRequest
{
    /**
     * @return integer
     */
    public function getData() {
        $type = 2;
        if($this->getOtherParameters('printer_type') == 'a6'){
            $type = 1;
        }
        return [
            'ACSAlias' => 'ACS_Print_Voucher',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Voucher_No' => $this->getBolId(),
                'Print_Type' => $type,
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
