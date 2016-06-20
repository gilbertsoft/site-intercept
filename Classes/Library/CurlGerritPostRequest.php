<?php
declare(strict_types = 1);

namespace T3G\Intercept\Library;

use T3G\Intercept\LogManager;

class CurlGerritPostRequest
{
    protected $baseUrl = 'https://review.typo3.org/a/';

    /**
     * @codeCoverageIgnore postman generated curl requests
     * @param string $apiPath
     * @param array $postFields
     */
    public function postRequest(string $apiPath, array $postFields)
    {
        $logger = LogManager::getLogger('GerritRequests');

        $curl = curl_init();

        $url = $this->baseUrl . $apiPath;
        $logger->info('cURL request to url ' . $url . ' with params ' . print_r($postFields, true));

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($postFields),
                CURLOPT_HTTPHEADER => [
                    'authorization: Basic dHlwbzNjb21fYmFtYm9vOjBMZnhjbFVackVRSWhDM2JmZ0lSZTJNUVBnc1I1cEljcWIvZ2dZUy9Kdw==',
                    'cache-control: no-cache',
                    'content-type: application/json'
                ],
            ]
        );
        curl_exec($curl);
        //$err = curl_error($curl);
        curl_close($curl);
    }
}