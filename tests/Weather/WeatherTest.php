<?php
namespace Weather;

use Guzzle\Http\Client;
use PHPUnit_Framework_TestCase;

/**
 * Class WeatherTest
 */
class WeatherTest extends PHPUnit_Framework_TestCase
{
    /** @var Weather */
    protected $weather;

    protected function setUp()
    {
        $guzzle = new Client();
        $this->weather = new Weather($guzzle);
    }

    protected function tearDown()
    {
        $this->weather = null;
    }

    /**
     * @test
     */
    public function getWeatherStatusByWoeid()
    {
        $result = $this->weather->getStatusFromWoeid(2442047);

        $this->assertSame('Cloudy', $result);
    }

    /**
     * @test
     */
    public function getWeatherStatusByCity()
    {
        $result = $this->weather->getStatusFromCity('Los Angeles, United States');

        $this->assertSame('Cloudy', $result);
    }
}