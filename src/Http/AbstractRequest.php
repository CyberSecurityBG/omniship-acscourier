<?php

namespace Omniship\Acscourier\Http;

use Omniship\Acscourier\Client as AcscourierClient;

use Omniship\Message\AbstractRequest as BaseAbstractRequest;

abstract class AbstractRequest extends BaseAbstractRequest
{
    protected $client;


    public function getKey(){
        return $this->getParameter('key');
    }

    public function setKey($value){
        return $this->setParameter('key', $value);
    }


    public function getClient()
    {
        if(is_null($this->client)) {
            $this->client = new AcscourierClient($this->getCompanyId(), $this->getCompanyPassword(), $this->getUsername(), $this->getPassword(), $this->getKey(), $this->getBillingCode());
        }
        return $this->client;
    }

    public function getCompanyId(){
        return  $this->getParameter('company_id');
    }

    public function setCompanyId($value){
        return $this->setParameter('company_id', $value);
    }

    public function setCompanyPassword($value){
        return $this->setParameter('company_password', $value);
    }

    public function getCompanyPassword(){
        return $this->getParameter('company_password');
    }

    public function setUsername($value){
        return $this->setParameter('username', $value);
    }

    public function getUsername(){
        return $this->getParameter('username');
    }

    public function getPassword(){
        return $this->getParameter('password');
    }

    public function setPassword($value){
        return $this->setParameter('password', $value);
    }

    public function getBillingCode()
    {
        return $this->getParameter('billing_code');
    }

    public function setBillingCode($value)
    {
        return $this->setParameter('billing_code', $value);
    }

    abstract protected function createResponse($data);

}
