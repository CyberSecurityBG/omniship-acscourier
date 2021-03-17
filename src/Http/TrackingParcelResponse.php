<?php

namespace Omniship\Acscourier\Http;
use Omniship\Common\Component;
use Omniship\Common\EventBag;
use Omniship\Common\TrackingBag;

class TrackingParcelResponse extends AbstractResponse
{
    public function getData()
    {
        $result = new TrackingBag();
        $DecodeResult = json_decode($this->data, true);
        if(is_null($DecodeResult['progressdetail'])) {
            return $result;
        }
        $row = 0;
        foreach($DecodeResult['progressdetail'] as $quote) {
            $result->push([
                'id' => md5($quote['deliverydate'].$quote['deliverytime']),
                'name' => $quote['deliverylocation'],
                'events' => $this->_getEvents($quote),
                'shipment_date' => null,
                'estimated_delivery_date' => null,
                'origin_service_area' => null,
                'destination_service_area' => new Component(['id' => md5(json_encode( $quote['deliverylocation'])), 'name' =>  $quote['deliverylocation']]),
            ]);
            $row++;
        }

        return $result;
    }

    protected function _getEvents( $data)
    {
        $result = new EventBag();
        if($data['status']) {
            $result->push(new Component([
                'id' => $data['status'],
                'name' => $data['deliverylocation'],
            ]));
        }
        return $result;
    }
}
