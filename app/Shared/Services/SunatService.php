<?php

namespace App\Shared\Services;

use Illuminate\Http\JsonResponse;

class SunatService
{
    public function dniConsultation($dni): mixed
    {
        // data
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        // start to API call
        $curl = curl_init();

        // search by DNI
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.apis.net.pe/v1/dni?numero=$dni",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 2,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Referer: https://apis.net.pe/consulta-dni-api',
                "Authorization: Bearer $token",
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        // Person data according to reduced register
        $persona = json_decode($response);
        return $persona;
    }

    public function rucConsultation($ruc): mixed
    {
        // data
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        // start to API call
        $curl = curl_init();

        // Search by RUC
        curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.apis.net.pe/v1/ruc?numero=' . $ruc,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => [
            'Referer: http://apis.net.pe/api-ruc',
            "Authorization: Bearer $token",
        ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        // Company data according to reduced register
        $empresa = json_decode($response);
        return $empresa;
    }
}
