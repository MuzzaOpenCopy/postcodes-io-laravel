<?php 

use MuzzaOpenCopy\PostcodesIo\ApiClientFactory;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Subscriber\Mock;

class ApiClientTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string Test base URL.
     */
    const BASE_URL = 'http://api.postcodes.io';

    /**#@+
     * @var string A valid postcode.
     */
    const VALID_POSTCODE1 = 'CF10 1DD';
    const VALID_POSTCODE2 = 'W1B 4BD';
    /**#@-*/

    /**
     * @var string A partial postcode.
     */
    const PARTIAL_POSTCODE = 'CF10';

    /**
     * @var float A latitude value.
     */
    const VALID_LATITUDE1 = 51.481667;

    /**
     * @var float A longitude value.
     */
    const VALID_LONGITUDE1 = -3.182155;

    /**
     * @var float A latitude value.
     */
    const VALID_LATITUDE2 = 51.88328;

    /**
     * @var float A longitude value.
     */
    const VALID_LONGITUDE2 = -3.43684;

    /**
     * @var MuzzaOpenCopy\PostcodesIo\ApiClientFactory The ClientFactory.
     */
    protected $clientFactory;

    /**
     * @var GuzzleHttp\Command\Guzzle\GuzzleClient A client, created by the factory.
     */
    protected $client;

    /**
     * Set up.
     */
    public function setUp()
    {
        $responseArray = [];

        $this->clientFactory = new ApiClientFactory();
        $this->client = $this->clientFactory->create(self::BASE_URL);

        $mockResponse = new \GuzzleHttp\Subscriber\Mock([
            new GuzzleHttp\Message\Response(200, [], GuzzleHttp\Stream\Stream::factory(json_encode($responseArray)))
        ]);
        $guzzle = $this->client->getHttpClient();
        $guzzle->getEmitter()->attach($mockResponse);
    }

    public function testFactoryCreateMethodReturnsInstanceOfClient()
    {
        $this->assertInstanceOf('GuzzleHttp\Command\Guzzle\GuzzleClient', $this->client);
    }

    /**
     * testClientHasCommand
     *
     * @param string $commandName The command name.
     * @param array $commandArguments The command arguments.
     *
     * @dataProvider getCommandNamesWithArguments
     */
    public function testClientHasCommand($commandName, array $commandArguments)
    {
        $response = $this->client->$commandName($commandArguments);

        $this->assertEquals(200, $response['statusCode']);
    }

    /**
     * Get an array of command names with arguments.
     *
     * @return array An array, each element an array containing a command name and its arguments.
     */
    public function getCommandNamesWithArguments()
    {
        return array(
            array('lookup', array('postcode' => self::VALID_POSTCODE1)),
            array('nearest', array('postcode' => self::VALID_POSTCODE1)),
            array('bulkLookup', array('postcodes' => array(self::VALID_POSTCODE1, self::VALID_POSTCODE2))),
            array(
                'reverseGeocode',
                array(
                    'longitude' => self::VALID_LONGITUDE1,
                    'latitude' => self::VALID_LATITUDE1,
                    'limit' => 10,
                    'radius' => 100
                )
            ),
            array(
                'bulkReverseGeocode',
                array(
                    'geolocations' => array(
                        array(
                            'longitude' => self::VALID_LONGITUDE1,
                            'latitude' => self::VALID_LATITUDE1
                        ),
                        array(
                            'longitude' => self::VALID_LONGITUDE2,
                            'latitude' => self::VALID_LATITUDE2,
                            'limit' => 100,
                            'radius' => 500
                        )
                    )
                )
            ),
            array('matching', array('query' => self::PARTIAL_POSTCODE, 'limit' => 5)),
            array('validate', array('postcode' => self::VALID_POSTCODE1)),
            array('autocomplete', array('postcode' => self::PARTIAL_POSTCODE, 'limit' => 10)),
            array('random', array()),
            array('outwardCodeLookup', array('outcode' => self::PARTIAL_POSTCODE))
        );
    }
}
