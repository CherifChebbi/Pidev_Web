<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class OpenWeatherMapService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function getWeatherByCityName(string $cityName): array
    {
        $response = $this->client->request('GET', 'http://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $cityName,
                'appid' => $this->apiKey,
                'units' => 'metric', // or 'imperial' for Fahrenheit
            ],
        ]);

        return $response->toArray();
    }
}