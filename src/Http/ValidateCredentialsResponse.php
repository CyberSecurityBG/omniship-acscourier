<?php

namespace Omniship\Acscourier\Http;

class ValidateCredentialsResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData()
    {
        if(isset($this->data->ACSOutputResponce->ACSValueOutput) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message)){
            return $this->data->ACSOutputResponce->ACSValueOutput;
        }
        return true;
    }

}
