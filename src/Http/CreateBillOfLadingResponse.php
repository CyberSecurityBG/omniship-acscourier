<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Acscourier\Http;

use Carbon\Carbon;
use Omniship\Common\Bill\Create;

class CreateBillOfLadingResponse extends AbstractResponse
{
    /**
     * @var Parcel
     */
    protected $data;
    /**
     * @return Create
     */
    public function getData()
    {
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) || isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !empty($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            return $this->data->ACSOutputResponce->ACSValueOutput;
        }
        $respons = $this->data->ACSOutputResponce->ACSValueOutput[0];
        $result = new Create();
        $result->setBolId($respons->Voucher_No);
        $result->setBillOfLadingSource($this->getClient()->PrintVoucher($respons->Voucher_No));
        $result->setBillOfLadingType($result::PDF);
        return $result;
    }

}
