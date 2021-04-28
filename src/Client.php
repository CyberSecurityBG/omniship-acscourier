<?php

namespace Omniship\Acscourier;

use GuzzleHttp\Client AS HttpClient;
use http\Client\Response;
use Omniship\Address\City;
use Omniship\Address\Office;
use Omniship\Helper\Collection;
use App\Exceptions\Error;

class Client
{

    protected $company_id;
    protected $company_password;
    protected $username;
    protected $password;
    protected $key;
    protected $lang;
    protected $error;
    protected $billing_code;

    const SERVICE_PRODUCTION_URL = 'https://webservices.acscourier.net/ACSRestServices/api/ACSAutoRest';

    public function __construct($company_id, $company_password, $username, $password, $key, $billing_code)
    {
        $this->company_id = $company_id;
        $this->company_password = $company_password;
        $this->username = $username;
        $this->password = $password;
        $this->key = $key;
        $this->billing_code = $billing_code;
    }


    public function getError()
    {
        return $this->error;
    }

    public function getCities(){
        $collection = [];
        $post = $this->SendRequest([
            'ACSAlias' => 'ACS_Area_Find_By_Zip_Code',
            'ACSInputParameters' => [
                'Company_ID' => $this->company_id,
                'Company_Password' => $this->company_password,
                'User_ID' => $this->username,
                'User_Password' => $this->password,
                'Zip_Code' => 0,
                'Show_Only_Inaccessible_Areas' => 0,
                'Language' => $this->lang,
            ]
        ]);
        foreach ($post->ACSOutputResponce->ACSTableOutput->Table_Data as $city){
            $collection[] = [
                'id' => $city->Station_ID,
                'name' => $city->Area,
                'post_code' => $city->Zip_Code,
            ];
        }
        return new Collection($collection);
    }

    public function getOffices()
    {
        $collection = [];
        $post = $this->SendRequest([
            'ACSAlias' => 'ACS_Stations',
            'ACSInputParameters' => [
                'Company_ID' => $this->company_id,
                'Company_Password' => $this->company_password,
                'User_ID' => $this->username,
                'User_Password' => $this->password,
                'Language' => $this->lang
            ]
        ]);
        foreach ($post->ACSOutputResponce->ACSTableOutput->Table_Data as $office){
            $collection[] = [
                'name' => $office->ACS_SHOP_AREA_DESCR .' - '.$office->ACS_SHOP_ADDRESS,
                'latitude' => $office->ACS_SHOP_LAT,
                'longitude' => $office->ACS_SHOP_LONG,
                'phones' => $office->ACS_SHOP_PHONES,
                'address_string' => $office->ACS_SHOP_COUNTRY_DESCR.', '.$office->ACS_SHOP_AREA_DESCR.', '.$office->ACS_SHOP_ADDRESS,
                'city_id' => $office->ACS_SHOP_AREA_ID,
                'country_id' => $office->ACS_SHOP_COUNTRY_ID,
            ];
        }
        return new Collection($collection);
    }

    public function SendRequest($data = []){
        try {
            $client = new HttpClient(['base_uri' => self::SERVICE_PRODUCTION_URL]);
            $response = $client->request('POST', '', [
                'json' => $data,
                'headers' =>  [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/vnd.api+json',
                    'ACSApiKey' => $this->key
                ]
            ]);
            return json_decode($response->getBody()->getContents());
        } catch (\Exception $e) {
            $this->error = [
                'code' => $e->getCode(),
                'error' => $e->getResponse()->getBody()->getContents()
            ];
        }
    }

    public function PrintVoucher($voucher_id, $type = null){
        if($type == 'a6'){
            $type = 1;
        } else {
            $type = 2;
        }
        $post = $this->SendRequest([
            'ACSAlias' => 'ACS_Print_Voucher',
            'ACSInputParameters' => [
                'Company_ID' => $this->company_id,
                'Company_Password' => $this->company_password,
                'User_ID' => $this->username,
                'User_Password' => $this->password,
                'Voucher_No' => $voucher_id,
                'Print_Type' => $type,
                'Start_Position' => 1
            ]
        ]);
        return $post->ACSOutputResponce->ACSValueOutput[0]->ACSObjectOutput;
    }

    public function GeneratePickup($date){
        $post = $this->SendRequest([
            'ACSAlias' => 'ACS_Issue_Pickup_List',
            'ACSInputParameters' => [
                'Company_ID' => $this->company_id,
                'Company_Password' => $this->company_password,
                'User_ID' => $this->username,
                'User_Password' => $this->password,
                'Pickup_Date' => $date,
                'MyData' => null,
            ]
        ]);
        if($post->ACSOutputResponce->ACSValueOutput[0]->Unprinted_Found > 0){
            return [
                'for_print' => $post->ACSOutputResponce->ACSTableOutput->Table_Data,
                'total' => $post->ACSOutputResponce->ACSValueOutput[0]->Unprinted_Found
            ];
        } else {
            return [
                'pickuplist_number' => $post->ACSOutputResponce->ACSValueOutput[0]->PickupList_No,
                'total' => 0
            ];
        }
    }

    public function DeleteWawybill($walbill_number){
        $this->SendRequest([
             'ACSAlias' => 'ACS_Print_Voucher',
             'ACSInputParameters' => [
                 'Company_ID' => $this->company_id,
                 'Company_Password' => $this->company_password,
                 'User_ID' => $this->username,
                 'User_Password' => $this->password,
                 'Voucher_No' => $walbill_number,
             ]
         ]);
    }
}
