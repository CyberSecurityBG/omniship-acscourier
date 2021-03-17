<?php


namespace Omniship\Acscourier\Http;

use Omniship\Acscourier\Client;
use Omniship\Message\AbstractResponse AS BaseAbstractResponse;

class AbstractResponse extends BaseAbstractResponse
{

    protected $error;

    protected $errorCode;

    protected $client;


    /**
     * Get the initiating request object.
     *
     * @return AbstractRequest
     */
    public function getRequest()
    {
       return  $this->request;
    }

    /**
     * @return null|string
     */
    public function getMessage()
    {
        $message = null;
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message)){
            $message = $this->data->ACSOutputResponce->ACSValueOutput[0]->error_message;
        }
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            $message = $this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message;
        }
        return $message;
    }

    /**
     * @return null|string
     */
    public function getCode()
    {
       return null;
    }

    /**
     * @return null|Client
     */
    public function getClient()
    {
        return $this->getRequest()->getClient();
    }

    /**
     * @param mixed $client
     * @return AbstractResponse
     */


}
