<?php

namespace Omniship\Acscourier\Http;

class GetPdfResponse extends AbstractResponse
{

    /**
     * @return bool
     */
    public function getData(){
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) || isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !empty($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            $this->error = $this->data;
        }
        //dd(base64_decode($this->data->ACSOutputResponce->ACSValueOutput[0]->ACSObjectOutput));
        return base64_decode($this->data->ACSOutputResponce->ACSValueOutput[0]->ACSObjectOutput);
    }

}
