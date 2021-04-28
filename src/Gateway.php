<?php

namespace Omniship\Acscourier;

use Carbon\Carbon;
use Omniship\Acscourier\Http\CreateBillOfLadingRequest;
use Omniship\Acscourier\Http\GetPdfRequest;
use Omniship\Acscourier\Http\TrackingParcelRequest;
use Omniship\Common\AbstractGateway;
use Omniship\Acscourier\Http\ValidateCredentialsRequest;
use Omniship\Acscourier\Http\ShippingQuoteRequest;

use Omniship\Acscourier\Client;
use Omniship\Common\Address;
use Omniship\Acscourier\Http\ValidateAddressRequest;
use Omniship\Interfaces\RequestInterface;

/**
 * @method RequestInterface deleteBillOfLading()
 * @method RequestInterface trackingParcels(array $bol_ids = [])
 * @method RequestInterface validatePostCode(Address $address)
 * @method RequestInterface requestCourier($bol_id, Carbon $date_start = null, Carbon $date_end = null)
 * @method RequestInterface codPayments(array $bol_ids)
 */
class Gateway extends AbstractGateway
{

    private $name = 'acscourier';
    protected $client;
    const TRACKING_URL = 'https://track.aftership.com/trackings?courier=acscourier&tracking-numbers=%s';

    /**
     * @return stringc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'company_id' => '',
            'key' => '',
            'company_password' => '',
            'username' => '',
            'password' => '',
            'billing_code' => ''
        ];
    }

    public function getCompanyId(){

        return $this->getParameter('company_id');
    }

    public function setCompanyId($value){
        return $this->setParameter('company_id', $value);
    }

    public function getCompanyPassword(){
        return $this->getParameter('company_password');
    }

    public function setCompanyPassword($value){
        return $this->setParameter('company_password', $value);
    }

    public function getUsername(){
        return $this->getParameter('username');
    }

    public function setUsername($value){
        return $this->setParameter('username', $value);
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

    public function getKey() {
        return $this->getParameter('key');
    }

    public function setKey($value) {
        return $this->setParameter('key', $value);
    }

    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client($this->getCompanyId(), $this->getCompanyPassword(), $this->getUsername(), $this->getPassword(), $this->getKey(), $this->getBillingCode());
        }
        return $this->client;
    }

    public function supportsValidateAddress()
    {
        return false;
    }
    public function validateAddress(Address $address){
        return $this->createRequest(ValidateAddressRequest::class, $this->setAddress($address)->getParameters());

    }

    public function supportsValidateCredentials(){
        return true;
    }
    public function validateCredentials(array $parameters = [])
    {
        return $this->createRequest(ValidateCredentialsRequest::class, $parameters);
    }

    public function supportsCreateBillOfLading(){
        return true;
    }
    public function createBillOfLading($parameters = [])
    {
        if ($parameters instanceof CreateBillOfLadingRequest) {
            return $parameters;
        }
        if (!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(CreateBillOfLadingRequest::class, $this->getParameters() + $parameters);
    }


    public function supportsCashOnDelivery()
    {
        return true;
    }

    public function supportsInsurance(){
        return true;
    }

    public function trackingParcel($bol_id)
    {
        return $this->createRequest(TrackingParcelRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    public function getQuotes($parameters = [])
    {
        if($parameters instanceof ShippingQuoteRequest) {
            return $parameters;
        }
        if(!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(ShippingQuoteRequest::class, $this->getParameters() + $parameters);
    }

    public function getPdf($bol_id)
    {
        return $this->createRequest(GetPdfRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    public function trackingUrl($parcel_id)
    {
        return sprintf(static::TRACKING_URL, $parcel_id);
    }

}
