<?php
namespace Nbz4live\LaravelGeoIP2\Provider;

use GeoIp2\Database\Reader;
use GeoIp2\ProviderInterface;
use Nbz4live\LaravelGeoIP2\GeoIP2Exception;

class DatabaseProvider implements ProviderInterface
{
    /** @var \GeoIp2\Database\Reader[] */
    private $readers = [];

    /**
     * Init Reader for configured database files
     *
     * @param string $storagePath
     * @param array $databases
     */
    public function __construct($storagePath, $databases)
    {
        foreach ($databases as $key => $database) {
            $this->readers[$key] = new Reader(sprintf('%s/%s', $storagePath, $database));
        }
    }

    /**
     * Forward method call to belonging database reader
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     * @throws GeoIP2Exception
     */
    public function __call($name, $arguments)
    {
        $key = strtolower($name);
        $ipAddress = array_shift($arguments);

        if (empty($this->readers[$key])) {
            throw new GeoIP2Exception(sprintf('Missing "%s" database', $key));
        }

        return $this->readers[$key]->$name($ipAddress);
    }

    /**
     * @inheritdoc
     */
    public function country($ipAddress)
    {
        return $this->__call('country', [$ipAddress]);
    }

    /**
     * @inheritdoc
     */
    public function city($ipAddress)
    {
        return $this->__call('city', [$ipAddress]);
    }
}
