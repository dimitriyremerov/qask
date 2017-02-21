<?php
namespace Qask\SecretFlying;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Controller
{
    private $client;

    /**
     * Controller constructor.
     * @param Client $client
     */
    public function __construct(Client $client = null)
    {
        if (!isset($client)) {
            $client = new Client();
        }
        $this->client = $client;
    }

    public function getSecretFlyingRss()
    {
        $headers = [
            'Host' =>  'www.secretflying.com',
            'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:44.0) Gecko/20100101 Firefox/44.0',
            'Accept' => 'application/xml',
            'Accept-Language' => 'en-US,en;q=0.5',
        ];

        $request = new Request('GET', 'http://www.secretflying.com/feed/', $headers);

        $res = $this->client->send($request);

        $data = $res->getBody();

        $data = str_replace('<title></title>', '<title>Sample title</title>', $data);

        return new SymfonyResponse($data, SymfonyResponse::HTTP_OK, ['Content-Type' => 'application/xml']);
    }
}
