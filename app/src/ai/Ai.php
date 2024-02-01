<?php

namespace app\ai;

class Ai
{
    public function postToApi(
        array $postData, CurlRequest $curlRequest = new CurlRequest("https://localhost:5000")
    ) {
        $postDataJson = json_encode($postData);

        $curlRequest->setOption(CURLOPT_HEADER, false);
        $curlRequest->setOption(CURLOPT_RETURNTRANSFER, true);
        $curlRequest->setOption(CURLOPT_POST, true);
        $curlRequest->setOption(CURLOPT_POSTFIELDS, $postDataJson);
        $curlRequest->setOption(CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        $json_response = $curlRequest->execute();
        $curlRequest->close();

        return json_decode($json_response);
    }



}