<?php
namespace Weather;

use \Guzzle\Http\Client;
use Guzzle\Http\Message\Response;

/**
 * Class Weather

 *
*@package Weather
 */
class Weather
{
    const WEBSERVICE_URI = 'http://weather.yahooapis.com/forecastrss?u=c&w=';
    const WEBSERVICE_METHOD = 'GET';

    /** @var \Guzzle\Http\Client */
    private $guzzle;

    /**
     * @param Client $guzzle
     */
    public function __construct(Client $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function getStatusFromCity($cityName)
    {
        $query = 'http://query.yahooapis.com/v1/public/yql?q=select woeid from ' .
            'geo.places where text="'.$cityName.'" limit 1';
        $request = $this->guzzle->createRequest(
            self::WEBSERVICE_METHOD,
            $query
        );
        $response = $this->guzzle->send($request);

        $woeid = $this->parseCityResponse($response->xml());
        return $this->getStatusFromWoeid($woeid);
    }

    public function getStatusFromWoeid($woeid)
    {
        $request = $this->guzzle->createRequest(self::WEBSERVICE_METHOD, self::WEBSERVICE_URI.$woeid);
        $response = $this->guzzle->send($request);

        $status = $this->parseWeatherResponse($response->xml());
        return $status;
    }

    private function parseWeatherResponse($response)
    {
        $item = $response->channel->item;
        $condition = $item->xpath('yweather:condition')[0]['text'];
        return (string) $condition;
    }

    private function parseCityResponse($xml)
    {
        return (string)$xml->results->place->woeid;
    }
}