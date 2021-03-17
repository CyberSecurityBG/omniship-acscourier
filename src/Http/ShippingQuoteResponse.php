<?php

namespace Omniship\Acscourier\Http;
use Omniship\Common\ShippingQuoteBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) || isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !empty($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            return $this->data->ACSOutputResponce->ACSValueOutput;
        }
        $result = new ShippingQuoteBag();
        foreach ($this->data->ACSOutputResponce->ACSValueOutput as $data){
            $result->push([
                'id' => 1,
                'name' => null,
                'description' => null,
                'price' => (float)$data->Total_Ammount+$data->Total_Vat_Ammount,
                'pickup_date' => null,
                'pickup_time' => null,
                'delivery_date' => null,
                'delivery_time' => null,
                'currency' => null,
                'tax' => null,
                'insurance' => 0,
                'exchange_rate' => null,
                'payer' =>null,
                'allowance_fixed_time_delivery' => false,
                'allowance_cash_on_delivery' => true,
                'allowance_insurance' => true,
            ]);
        }
        return $result;
    }
}
