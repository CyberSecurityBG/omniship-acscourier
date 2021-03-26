<?php

namespace Omniship\Acscourier\Http;
use Omniship\Common\ShippingQuoteBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) || isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !empty($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            $this->error = $this->data;
        }
        $result = new ShippingQuoteBag();
        foreach ($this->data->ACSOutputResponce->ACSValueOutput as $data){
            $result->push([
                'id' => 1,
                'name' => 'Shipping to adress',
                'description' => null,
                'price' => (float)$data->Total_Ammount+$data->Total_Vat_Ammount,
                'pickup_date' => null,
                'pickup_time' => null,
                'delivery_date' => null,
                'delivery_time' => null,
                'currency' => $this->getRequest()->getCurrency(),
                'tax' => null,
                'insurance' => $this->getRequest()->getInsuranceAmount(),
                'exchange_rate' => null,
                'payer' =>$this->getRequest()->getPayer(),
                'allowance_fixed_time_delivery' => false,
                'allowance_cash_on_delivery' => true,
                'allowance_insurance' => true,
            ]);
        }
        return $result;
    }
}
