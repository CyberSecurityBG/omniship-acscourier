<?php

namespace Omniship\Acscourier\Http;
use Doctrine\Common\Collections\ArrayCollection;

class ShippingQuoteRequest extends AbstractRequest
{

    public function getData()
    {
        return [
            'ACSAlias' => 'ACS_Price_Calculation',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Billing_Code' => $this->getBillingCode(),
                'Billing_Category' => 2,
                'Acs_Station_Origin' => $this->getSenderAddress()->getCity()->getId(),
                'Acs_Station_Destination' => $this->getReceiverAddress()->getCity()->getId(),
                'Weight' => $this->getWeight(),
                'Pickup_Date' => $this->getOtherParameters('pickup_date'),
                'Acs_Delivery_Products' => '',
                'Charge_Type' => 2,
                'Delivery_Zone' => '',
                'Insurance_Ammount' => $this->getInsuranceAmount(),
                'Dimension_X_In_Cm' => $this->getItems()->first()->getDepth(),
                'Dimension_Y_In_Cm' => $this->getItems()->first()->getWidth(),
                'Dimension_Z_In_Cm' => $this->getItems()->first()->getHeight(),
            ]
        ];
    }

    public function sendData($data)
    {
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    protected function createResponse($data)
    {
        return $this->response = new ShippingQuoteResponse($this, $data);
    }
}
