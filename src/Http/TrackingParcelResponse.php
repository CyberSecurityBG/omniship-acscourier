<?php

namespace Omniship\Acscourier\Http;
use Carbon\Carbon;
use Omniship\Common\Component;
use Omniship\Common\ShippingQuoteBag;
use Omniship\Common\TrackingBag;

class ShippingQuoteResponse extends AbstractResponse
{
    public function getData()
    {
        if(isset($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) && !is_null($this->data->ACSOutputResponce->ACSValueOutput[0]->error_message) || isset($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message) && !empty($this->data->ACSOutputResponce->ACSValueOutput[0]->Error_Message)){
            return $this->data->ACSOutputResponce->ACSValueOutput;
        }
        $result = new ShippingQuoteBag();
        foreach ($this->data->ACSOutputResponce->ACSValueOutput as $data){
            $result = new TrackingBag();
            $track = $this->_getTrackPicking($track);
            $name = implode(' ', array_filter([$track->getSiteType(), $track->getSiteName()]));
            $name = explode('[', !$name && !$row ? $track->getOperationComment() : $name);
            $name = trim(array_shift($name));
            $result->push([
                'id' => $track->getOperationCode(),
                'name' => $name,
                'events' => $this->_getEvents($track),
                'shipment_date' => Carbon::createFromFormat('Y-m-d\TH:i:sP', $track->getMoment()),
                'estimated_delivery_date' => $this->_findEstimatedDeliveryDate($tracking[0]),
                'origin_service_area' => null,
                'destination_service_area' => $name ? new Component(['id' => md5($name), 'name' => $name]) : null,
            ]);
            $results->put($bol_id, $result);
        }
        return $result;
    }
}
