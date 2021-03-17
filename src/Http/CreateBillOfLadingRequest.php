<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 16:55 ч.
 */

namespace  Omniship\Acscourier\Http;

class CreateBillOfLadingRequest extends AbstractRequest
{
    /**
     * @return array
     */
    public function getData() {
        if($this->getPayer() == 'SENDER'){
            $payer = 2;
        } else{
            $payer = 4;
        }
        $addon = [];
        if($this->getCashOnDeliveryAmount() != null && $this->getCashOnDeliveryAmount() > 0){
            $addon[] = 'COD';
        }
        if($this->getInsuranceAmount() != null && $this->getInsuranceAmount() > 0){
            $addon[] = 'INS';
        }
        $data = [
            'ACSAlias' => 'ACS_Create_Voucher',
            'ACSInputParameters' => [
                'Company_ID' => $this->getCompanyId(),
                'Company_Password' => $this->getCompanyPassword(),
                'User_ID' => $this->getUsername(),
                'User_Password' => $this->getPassword(),
                'Billing_Code' => $this->getBillingCode(),
                'Pickup_Date' => $this->getOtherParameters('pickup_date'),
                'Sender' => $this->getSenderAddress()->getFullName(),
                'Recipient_Name' => $this->getReceiverAddress()->getFullName(),
                'Recipient_Address' => $this->getReceiverAddress()->getStreet()->getName(),
                'Recipient_Address_Number' => $this->getReceiverAddress()->getStreetNumber(),
                'Recipient_Zipcode' => $this->getReceiverAddress()->getPostCode(),
                'Recipient_Region' => $this->getReceiverAddress()->getState()->getName(),
                'Recipient_Phone' => $this->getReceiverAddress()->getPhone(),
                'Recipient_Cell_Phone' => $this->getReceiverAddress()->getPhone(),
                'Recipient_Floor' => $this->getReceiverAddress()->getFloor() ?? null,
                'Recipient_Company_Name' => $this->getReceiverAddress()->getCompanyName(),
                'Recipient_Country' => $this->getReceiverAddress()->getCountry()->getId(),
                'Acs_Station_Destination' => null,
                'Acs_Station_Branch_Destination' => null,
                'Charge_Type' => $payer,
                'Cost_Center_Code' => null,
                'Item_Quantity' => 1,
                'Weight' => $this->getWeight(),
                'Dimension_X_In_Cm' => $this->getItems()->first()->getDepth(),
                'Dimension_Y_in_Cm' => $this->getItems()->first()->getWidth(),
                'Dimension_Z_in_Cm' => $this->getItems()->first()->getHeight(),
                'Cod_Ammount' => $this->getCashOnDeliveryAmount(),
                'Cod_Payment_Way' => 0,
                'Acs_Delivery_Products' => implode(',', $addon),
                'Insurance_Ammount' => $this->getInsuranceAmount(),
                'Delivery_Notes' => $this->getReceiverAddress()->getNote(),
                'Appointment_Until_Time' => null,
                'Recipient_Email' => 'dasdas@abv.bg',
                'Reference_Key1' => null,
                'Reference_Key2' => null,
                'With_Return_Voucher' => 1,
                ]
            ];
        return $data;
    }

    public function sendData($data) {
        return $this->createResponse($this->getClient()->SendRequest($data));
    }

    /**
     * @param $data
     * @return ShippingQuoteResponse
     */
    protected function createResponse($data)
    {
        return $this->response = new CreateBillOfLadingResponse($this, $data);
    }

}
