<?php
namespace Nbz4live\LaravelGeoIP2\Provider;

use GeoIp2\Model\City;
use GeoIp2\Model\Country;
use GeoIp2\ProviderInterface;

class LocalhostProvider implements ProviderInterface
{
    private $localhostAddresses;
    private $raw;

    public function __construct($localhostAddresses, $raw)
    {
        $this->localhostAddresses = $localhostAddresses;
        $this->raw = $raw;
    }

    /**
     * @inheritdoc
     */
    public function country($ipAddress)
    {
        return new Country($this->raw);
    }

    /**
     * @inheritdoc
     */
    public function city($ipAddress)
    {
        return new City($this->raw);
    }

    /**
     * Determine if it's localhost address
     *
     * @param string $ipAddress
     *
     * @return bool
     */
    public function isLocalhost($ipAddress)
    {
        $ipLong = ip2long($ipAddress);
        $ipBin = str_pad(decbin($ipLong), 32, '0', STR_PAD_LEFT);

        foreach ($this->localhostAddresses as $address) {
            list($net, $mask) = explode('/', $address);
            $netLong = ip2long($net);
            $netBin = str_pad(decbin($netLong), 32, '0', STR_PAD_LEFT);
            $netMasked = substr($netBin, 0, $mask);
            $ipMasked = substr($ipBin, 0, $mask);

            if (strcmp($netMasked, $ipMasked) == 0) {
                return true;
            }
        }

        return false;
    }
}
