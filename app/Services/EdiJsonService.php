<?php

namespace App\Services;

class EdiJsonService
{

    public function ediToJsonV1($ediString)
    {
        $lines = explode("\n", $ediString);
        $data = [];

        foreach ($lines as $line) {
            $segments = explode("~", $line);
            foreach ($segments as $segment) {
                $elements = explode("*", $segment);
                $segmentType = $elements[0] ?? null;

                switch ($segmentType) {
                    case 'ISA':
                        $data['sender_id'] = $elements[6] ?? null;
                        $data['receiver_id'] = $elements[8] ?? null;
                        break;
                    case 'GS':
                        $data['group_receiver_id'] = $elements[3] ?? null;
                        break;
                    case 'BPT':
                        $data['invoice_number'] = $elements[2] ?? null;
                        $data['invoice_date'] = $elements[3] ?? null;
                        break;
                    case 'N1':
                        if ($elements[1] == 'ST') {
                            $data['ship_to_name'] = $elements[2] ?? null;
                        } elseif ($elements[1] == 'SN') {
                            $data['bill_to_name'] = $elements[2] ?? null;
                        }
                        break;
                    case 'N3':
                        $data['ship_to_address'] = $elements[1] ?? null;
                        break;
                    case 'N4':
                        $data['ship_to_city'] = $elements[1] ?? null;
                        $data['ship_to_state'] = $elements[2] ?? null;
                        $data['ship_to_zip'] = $elements[3] ?? null;
                        break;
                    case 'REF':
                        if ($elements[1] == 'IV') {
                            $data['invoice_number'] = $elements[2] ?? null;
                        } elseif ($elements[1] == 'SE') {
                            $data['serial_number'] = $elements[2] ?? null;
                        }
                        break;
                    case 'QTY':
                        $data['quantity'] = $elements[2] ?? null;
                        break;
                }
            }
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function ediToJsonV2($ediString)
    {
        $ediString = str_replace(["\r", "\n"], '', $ediString);
        $segments = explode('~', $ediString);

        $ediArray = [];
        foreach ($segments as $segment) {
            if (empty($segment)) continue;
            $elements = explode('*', $segment);
            $ediArray[] = $elements;
        }

        return json_encode($ediArray, JSON_PRETTY_PRINT);
    }

    public function jsonToEdiV1($json)
    {
        return $this->convertJsonToEdi(json_decode($json, true));
    }
    public function convertJsonToEdi(array $jsonData): string
    {
        // Buat segment ISA
        $isaSegment = $this->createIsaSegment($jsonData['sender_id'], $jsonData['receiver_id']);

        // Buat segment GS
        $gsSegment = $this->createGsSegment($jsonData['sender_id'], $jsonData['group_receiver_id']);

        // Buat segment BPT
        $bptSegment = $this->createBptSegment($jsonData['invoice_number'], $jsonData['invoice_date']);

        // Buat segment N1
        $n1Segment = $this->createN1Segment($jsonData['ship_to_name']);

        // Buat segment N3
        $n3Segment = $this->createN3Segment($jsonData['ship_to_address']);

        // Buat segment N4
        $n4Segment = $this->createN4Segment($jsonData['ship_to_city'], $jsonData['ship_to_state'], $jsonData['ship_to_zip']);

        // Buat segment QTY
        $qtySegment = $this->createQtySegment($jsonData['quantity']);

        // Buat segment REF (Serial Number)
        $refSegment = $this->createRefSegment($jsonData['serial_number']);

        // Gabungkan semua segment
        $ediString = $isaSegment . '~' .
            $gsSegment . '~' .
            $bptSegment . '~' .
            $n1Segment . '~' .
            $n3Segment . '~' .
            $n4Segment . '~' .
            $qtySegment . '~' .
            $refSegment . '~';

        return $ediString;
    }

    private function createIsaSegment(string $senderId, string $receiverId): string
    {
        return "ISA*00* *00* *14*$senderId*14*$receiverId*160624*0341*U*00401*000000462*0*P";
    }

    private function createGsSegment(string $senderId, string $groupReceiverId): string
    {
        return "GS*PT*$senderId*$groupReceiverId*20160624*0341*321*X*004010";
    }

    private function createBptSegment(string $invoiceNumber, string $invoiceDate): string
    {
        return "BPT*00*$invoiceNumber*$invoiceDate*02";
    }

    private function createN1Segment(string $shipToName): string
    {
        return "N1*ST*$shipToName*92*0050039886";
    }

    private function createN3Segment(string $shipToAddress): string
    {
        return "N3*$shipToAddress";
    }

    private function createN4Segment(string $city, string $state, string $zip): string
    {
        return "N4*$city*$state*$zip*US";
    }

    private function createQtySegment(string $quantity): string
    {
        return "QTY*39*$quantity*EA";
    }

    private function createRefSegment(string $serialNumber): string
    {
        return "REF*SE*$serialNumber";
    }

    public function jsonToEdiV2($json)
    {
        $ediArray = json_decode($json, true);

        $ediString = '';
        foreach ($ediArray as $segment) {
            $ediString .= implode('*', $segment) . '~';
        }

        return $ediString;
    }
}
