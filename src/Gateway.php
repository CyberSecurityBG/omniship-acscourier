<?php

namespace Omniship\Grabitmk;

use Carbon\Carbon;

use Omniship\Grabitmk\Http\CancelBillOfLadingRequest;
use Omniship\Grabitmk\Http\CreateBillOfLadingRequest;
use Omniship\Grabitmk\Http\GetPdfRequest;
use Omniship\Grabitmk\Http\TrackingParcelRequest;
use Omniship\Grabitmk\Http\ValidateCredentialsRequest;
use Omniship\Grabitmk\Http\ShippingQuoteRequest;
use Omniship\Grabitmk\Http\ValidateAddressRequest;
use Omniship\Grabitmk\Client;

use Omniship\Interfaces\RequestInterface;
use Omniship\Common\Address;
use Omniship\Common\AbstractGateway;

/**
 * @method RequestInterface deleteBillOfLading()
 * @method RequestInterface trackingParcels(array $bol_ids = [])
 * @method RequestInterface validatePostCode(Address $address)
 * @method RequestInterface requestCourier($bol_id, Carbon $date_start = null, Carbon $date_end = null)
 * @method RequestInterface codPayments(array $bol_ids)
 */
class Gateway extends AbstractGateway
{

    private $name = 'grabitmk';
    protected $client;
    const TRACKING_URL = 'https://naracki.els.mk/order/status/%s';

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
            'username' => '',
            'password' => '',
            'base_url' => '',
            'barear_token' => '',
            'request_type' => '',
            'reqeust_url' => ''
        ];
    }

    public function getBaseUrl()
    {
        return $this->getParameter('base_url');
    }

    public function setBaseUrl($value)
    {
        return $this->setParameter('base_url', $value);
    }

    public function getBarearToken()
    {
        return $this->getParameter('barear_token');
    }

    public function setBarearToken($value)
    {
        return $this->setParameter('barear_token', $value);
    }

    public function getRequestType()
    {
        return $this->getParameter('request_type');
    }

    public function setRequestType($value)
    {
        return $this->setParameter('request_type', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('username');
    }

    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }


    public function getRequestURI()
    {
        return $this->getParameter('reqeust_url');
    }

    public function setRequestURI($value)
    {
        return $this->setParameter('reqeust_url', $value);
    }

    public function setBackDocuments($value)
    {
        return $this->setParameter('back_documents', $value);
    }

    public function getBackDocuments()
    {
        return $this->getParameter('back_documents');
    }

    public function setSendType($value)
    {
        return $this->setParameter('send_type', $value);
    }

    public function getSendType()
    {
        return $this->getParameter('send_type');
    }

    public function getClient()
    {
        if (is_null($this->client)) {
            $this->client = new Client($this->getUsername(),
                $this->getPassword(),
                $this->getBaseUrl(),
                $this->getBarearToken(),
                $this->getRequestType(),
                $this->getRequestURI());
        }
        return $this->client;
    }


    public function supportsValidateAddress()
    {
        return false;
    }

    public function validateAddress(Address $address)
    {
        return $this->createRequest(ValidateAddressRequest::class, $this->setAddress($address)->getParameters());
    }


    public function supportsValidateCredentials()
    {
        return true;
    }

    public function validateCredentials(array $parameters = [])
    {

        return $this->createRequest(ValidateCredentialsRequest::class, $parameters);
    }


    public function supportsCreateBillOfLading()
    {
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

    public function supportsInsurance()
    {
        return true;
    }

    public function trackingParcel($bol_id)
    {
        return $this->createRequest(TrackingParcelRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    public function getQuotes($parameters = [])
    {
        if ($parameters instanceof ShippingQuoteRequest) {
            return $parameters;
        }
        if (!is_array($parameters)) {
            $parameters = [];
        }
        return $this->createRequest(ShippingQuoteRequest::class, $this->getParameters() + $parameters);
    }

    public function getPdf($bol_id)
    {
        return $this->createRequest(GetPdfRequest::class, $this->setBolId($bol_id)->getParameters());
    }

    /**
     * @param $bol_id
     * @param null $cancelComment
     * @return CancelBillOfLadingRequest
     */
    public function cancelBillOfLading($bol_id, $cancelComment = null)
    {
        $this->setBolId($bol_id);
        return $this->createRequest(CancelBillOfLadingRequest::class, $this->getParameters());
    }

    /**
     * @param $parcel_id
     * @return string
     */
    public function trackingUrl($parcel_id)
    {
        return sprintf(static::TRACKING_URL, $parcel_id);
    }


}
