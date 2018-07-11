<?php
namespace Nbz4live\LaravelGeoIP2;

use Illuminate\Support\Facades\Facade;

/**
 * Class GeoIP2Facade
 *
 * @package Nbz4live\LaravelGeoIP2
 *
 * @method static \GeoIp2\Model\AnonymousIp    anonymousIp(string $ipAddress = null)
 * @method static \GeoIp2\Model\City           city(string $ipAddress = null)
 * @method static \GeoIp2\Model\ConnectionType connectionType(string $ipAddress = null)
 * @method static \GeoIp2\Model\Country        country(string $ipAddress = null)
 * @method static \GeoIp2\Model\Domain         domain(string $ipAddress = null)
 * @method static \GeoIp2\Model\Insights       insights(string $ipAddress = null)
 * @method static \GeoIp2\Model\Isp            isp(string $ipAddress = null)
 */
class GeoIP2Facade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'geoip2';
    }
}
