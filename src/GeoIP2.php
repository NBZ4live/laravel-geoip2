<?php
namespace Nbz4live\LaravelGeoIP2;

use GeoIp2\WebService\Client;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Nbz4live\LaravelGeoIP2\Provider\DatabaseProvider;
use Nbz4live\LaravelGeoIP2\Provider\LocalhostProvider;

/**
 * Class GeoIP2
 *
 * @package Nbz4live\LaravelGeoIP2
 *
 * @method \GeoIp2\Model\AnonymousIp    anonymousIp(string $ipAddress = null)
 * @method \GeoIp2\Model\City           city(string $ipAddress = null)
 * @method \GeoIp2\Model\ConnectionType connectionType(string $ipAddress = null)
 * @method \GeoIp2\Model\Country        country(string $ipAddress = null)
 * @method \GeoIp2\Model\Domain         domain(string $ipAddress = null)
 * @method \GeoIp2\Model\Insights       insights(string $ipAddress = null)
 * @method \GeoIp2\Model\Isp            isp(string $ipAddress = null)
 */
class GeoIP2
{
    /** @var \Illuminate\Config\Repository */
    private $config;

    /** @var \Illuminate\Http\Request */
    private $request;

    /** @var \GeoIp2\ProviderInterface */
    private $provider;

    /** @var \Nbz4live\LaravelGeoIP2\Provider\LocalhostProvider */
    private $localhostProvider;

    protected $storagePath;
    protected $databases;

    private $userId;
    private $licenseKey;

    public function __construct(Config $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;

        $cfgStoragePath = $this->config->get('geoip2.storage_path');
        if (empty($cfgStoragePath)) {
            $cfgStoragePath = storage_path('geoip');
        }

        $this->storagePath = $cfgStoragePath;
        $this->databases = $this->config->get('geoip2.databases');

        $this->userId = $this->config->get('geoip2.user_id');
        $this->licenseKey = $this->config->get('geoip2.license_key');

        if (empty($this->userId)) {
            $this->userId = 999999;
        }

        if (empty($this->licenseKey)) {
            $this->licenseKey = '000000000000';
        }

        $this->init();
    }

    /**
     * Initialization of providers
     */
    public function init()
    {
        // Init API Provider when database is not used
        if (empty($this->databases)) {
            $this->provider = new Client($this->userId, $this->licenseKey);
        } else {
            $this->provider = new DatabaseProvider($this->storagePath, $this->databases);
        }

        $this->localhostProvider = new LocalhostProvider(
            $this->config->get('geoip2.localhost_addresses', []),
            $this->config->get('geoip2.localhost_raw_data', [])
        );
    }

    /**
     * Forward method calls to provider
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $ip = $this->request->getClientIp();
        if (!empty($arguments)) {
            $ip = array_shift($arguments);
        }

        if ($this->localhostProvider->isLocalhost($ip)) {
            return $this->localhostProvider->$name($ip);
        }

        return $this->provider->$name($ip);
    }
}
