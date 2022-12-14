<?php

namespace Libraries\ApiIterator;

use GuzzleHttp\Client;

class ApiIterator
{

    public $guzzleClient;

    protected $apiUrl;
    protected $apiKey;

    public $hasNextPage = true;
    protected $nextPage = 1;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function setApiURL(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    public function setApiKey(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getData() :array
    {

        $apiRequest = $this->guzzleClient->request('GET', $_ENV['API_URL'] . '/api/properties', [
            'query' => [
                'page[number]' => $this->nextPage,
                'api_key' => $_ENV['API_KEY']
            ]
        ]);
        $data = $apiRequest->getBody()->getContents();
        $decodeData = json_decode($data);

        if ($decodeData->next_page_url === null) {
            $this->hasNextPage = false;
        }

        echo "Current:".$this->nextPage;

        $this->nextPage = $decodeData->current_page + 1;

        echo "-Next:".$this->nextPage;

        return $decodeData->data;

    }

}