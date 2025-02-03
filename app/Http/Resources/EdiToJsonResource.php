<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EdiToJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "receiver_id" => $this->receiver_id,
            "sender_id" => $this->sender_id,
            "group_receiver_id" => $this->group_receiver_id,
            "invoice_number" => $this->invoice_number,
            "invoice_date" => $this->invoice_date,
            "ship_to_name" => $this->ship_to_name,
            "ship_to_address" => $this->ship_to_address,
            "ship_to_city" => $this->ship_to_city,
            "ship_to_state" => $this->ship_to_state,
            "ship_to_zip" => $this->ship_to_zip,
            "quantity" => $this->quantity,
            "serial_number" => $this->serial_number
        ];
    }

    public function responseV2($resposonses)
    {
        return [
            [
                "ISA",
                "00",
                " ",
                "00",
                " ",
                "14",
                "006995419XXX",
                "14",
                "051983567WDC",
                "160624",
                "0341",
                "U",
                "00401",
                "000000462",
                "0",
                "P"
            ],
            [
                "GS",
                "PT",
                "006995419XXX",
                "051983567WDC",
                "20160624",
                "0341",
                "321",
                "X",
                "004010"
            ],
            [
                "BPT",
                "00",
                "201606236300",
                "20160623",
                "02"
            ],
            [
                "N1",
                "ST",
                "CUSTOMER NAME",
                "92",
                "0050039886"
            ],
            [
                "N3",
                "1075 MONTAGUE EXPY"
            ],
            [
                "N4",
                "MILPITAS",
                "CA",
                "950356818",
                "US"
            ],
            [
                "REF",
                "IV",
                "INV1234567"
            ],
            [
                "QTY",
                "39",
                "2",
                "EA"
            ],
            [
                "REF",
                "SE",
                "WMC1P0H5ATUT"
            ]
        ];
    }
}
