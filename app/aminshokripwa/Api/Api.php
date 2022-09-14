<?php

namespace aminshokripwa\Api;

use GuzzleHttp\Client;

/**
 * Connect to API and get data
 */
class Api
{
    /**
     * Get user data from api
     *
     * @return array
     */
    public function getUsers($url)
    {

        $this->gc = new Client();

        try {
            $request = new \GuzzleHttp\Psr7\Request('GET', $url);
            $res = $this->gc->sendAsync($request)->wait();
        } catch (\Exception $e) {
            // catches all kinds of RuntimeExceptions
            //var_dump($e);
            $response = $e->getResponse();
            //$httpcode = (string) $response->getStatusCode();
            return (string) $response->getBody(); // Body, normally it is JSON;
        }
        //echo print_r(json_decode($res->getBody(), true));
        if (isset($error_msg)) {
            return [
                'error' => 'curl_req',
                'error_description' => $error_msg
            ];
        }

        return json_decode($res->getBody(), true);
    }
}
