<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EdiToJsonResource;
use App\Services\EdiJsonService;
use Illuminate\Http\Request;

class EdiController extends Controller
{

    public function __construct(private EdiJsonService $ediJsonService) {}



    public function convertEDIToJsonV1(Request $request)
    {
        $request->validate([
            /**
             * @example "ISA*00* *00* *14*006995419XXX*14*051983567WDC*160624*0341*U*00401*000000462*0*P~GS*PT*006995419XXX*051983567WDC*20160624*0341*321*X*004010~BPT*00*201606236300*20160623*02~N1*ST*CUSTOMER NAME*92*0050039886~N3*1075 MONTAGUE EXPY~N4*MILPITAS*CA*950356818*US~REF*IV*INV1234567~QTY*39*2*EA~REF*SE*WMC1P0H5ATUT~"
             */
            'edi' => 'required'
        ]);

        $ediString = $request->input('edi');


        $jsonInput = $this->ediJsonService->ediToJsonV1($ediString);

        /**
         * The created convert EDI to Json send data to the client.
         *
         * @status 201
         */

        return response()->json($jsonInput);
    }

    public function convertEDIToJsonV2(Request $request)
    {
        $request->validate([
            /**
             * @example "ISA*00* *00* *14*006995419XXX*14*051983567WDC*160624*0341*U*00401*000000462*0*P~GS*PT*006995419XXX*051983567WDC*20160624*0341*321*X*004010~BPT*00*201606236300*20160623*02~N1*ST*CUSTOMER NAME*92*0050039886~N3*1075 MONTAGUE EXPY~N4*MILPITAS*CA*950356818*US~REF*IV*INV1234567~QTY*39*2*EA~REF*SE*WMC1P0H5ATUT~"
             */
            'edi' => 'required'
        ]);

        $ediString = $request->input('edi');


        $jsonInput = $this->ediJsonService->ediToJsonV2($ediString);

        /**
         * The created convert EDI to Json send data to the client.
         *
         * @status 201
         */

        return response()->json($jsonInput);
    }

    public function convertJsonToEdiV1(Request $request)
    {

        $request->validate([
            /**
             * @example {
             *  "sender_id": "006995419XXX",
             * "receiver_id": "051983567WDC",
             * "group_receiver_id": "051983567WDC",
             * "invoice_number": "201606236300",
             * "invoice_date": "20160623",
             * "ship_to_name": "CUSTOMER NAME",
             * "ship_to_address": "1075 MONTAGUE EXPY",
             * "ship_to_city": "MILPITAS",
             * "ship_to_state": "CA",
             * "ship_to_zip": "950356818",
             * "quantity": "2",
             * "serial_number": "WMC1P0H5ATUT"
             * }
             *
             */
            'edi' => 'required'
        ]);
        $jsonString = '{
            "sender_id": "006995419XXX",
            "receiver_id": "051983567WDC",
            "group_receiver_id": "051983567WDC",
            "invoice_number": "201606236300",
            "invoice_date": "20160623",
            "ship_to_name": "CUSTOMER NAME",
            "ship_to_address": "1075 MONTAGUE EXPY",
            "ship_to_city": "MILPITAS",
            "ship_to_state": "CA",
            "ship_to_zip": "950356818",
            "quantity": "2",
            "serial_number": "WMC1P0H5ATUT"
        }';
        $ediString = $this->ediJsonService->jsonToEdiV1($jsonString);
        return response()->json($ediString);
    }

    public function convertJsonToEdiV2(Request $request)
    {


        $jsonString = '[
            ["ISA","00"," ","00"," ","14","006995419XXX","14","051983567WDC","160624","0341","U","00401","000000462","0","P"],
            ["GS","PT","006995419XXX","051983567WDC","20160624","0341","321","X","004010"],
            ["BPT","00","201606236300","20160623","02"],
            ["N1","ST","CUSTOMER NAME","92","0050039886"],
            ["N3","1075 MONTAGUE EXPY"],
            ["N4","MILPITAS","CA","950356818","US"],
            ["REF","IV","INV1234567"],
            ["QTY","39","2","EA"],
            ["REF","SE","WMC1P0H5ATUT"]
        ]';

        $ediString = $this->ediJsonService->jsonToEdiV2($jsonString);
        return response()->json($ediString);
    }
}
