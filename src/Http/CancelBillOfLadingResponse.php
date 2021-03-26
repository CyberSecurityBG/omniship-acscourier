<?php
/**
 * Created by PhpStorm.
 * User: joro
 * Date: 10.5.2017 г.
 * Time: 17:22 ч.
 */

namespace Omniship\Acscourier\Http;

class CancelBillOfLadingResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) || isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !empty($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            $this->error = $this->data;
        }
        return $this->data;
    }

}
