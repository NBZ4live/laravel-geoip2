<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MaxMind Databases
    |--------------------------------------------------------------------------
    |
    | Specify the databases that should be used to make lookups. Set database
    | to false/null or empty when you want to use API calls. You must have
    | MaxMind GeoIP2 user authentication credentials and the respective
    | license to use the API.
    |
    | By default GeoLite2 free databases are used, as shown below:
    | (tested using GeoLite2 City & Country DB)
    |
    */
    'databases' => [
        'city'    => 'GeoLite2-City.mmdb',
        'country' => 'GeoLite2-Country.mmdb',
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Storage
    |--------------------------------------------------------------------------
    |
    | Here you may specify the storage location of the databases. Storage 
    | path defaults to /path/to/project/storage/geoip when left empty
    |
    */
    'storage_path' => '',

    /*
    |--------------------------------------------------------------------------
    | MaxMind User Authentication
    |--------------------------------------------------------------------------
    |
    | You can specify your MaxMind User Credentials which will be used
    | with the update command for requests to the GeoIP2 API.
    | When left empty the GeoLite2 free credetials will be used by
    | default, which only allow for download of GeoLite2 free
    | databases but do not allow API calls.
    |
    | @var account_id        integer|empty
    | @var license_key    string
    |
    */
    'account_id'     => '',
    'license_key' => '',


    /*
    |--------------------------------------------------------------------------
    | MaxMind Products
    |--------------------------------------------------------------------------
    |
    | Specified products will be downloaded and updated when using the
    | update command. Add the product IDs, optionally you can specify
    | the file name. 
    |
    */
    'products' => [
        'GeoLite2-City' => 'GeoLite2-City.mmdb',
        'GeoLite2-Country',
    ],

    /*
    |--------------------------------------------------------------------------
    | Localhost IP Ranges
    |--------------------------------------------------------------------------
    |
    | This array contains all the RFC 5735 Special Use IPv4 Addresses. These
    | will all be classed as localhost during lookup and will return the
    | localhost data specified below.
    |
    */
    'localhost_addresses' => [
        '0.0.0.0/8',
        '10.0.0.0/8',
        '127.0.0.0/8',
        '169.254.0.0/16',
        '172.16.0.0/12',
        '192.0.0.0/24',
        '192.0.2.0/24',
        '192.88.99.0/24',
        '192.168.0.0/16',
        '198.18.0.0/15',
        '198.51.100.0/24',
        '203.0.113.0/24',
        '224.0.0.0/4',
        '240.0.0.0/4',
        '255.255.255.255/32',
    ],

    /*
    |--------------------------------------------------------------------------
    | Localhost Raw Data
    |--------------------------------------------------------------------------
    |
    | This is the data GeoIP2 will return when an IP address which belongs
    | to any of the locahost ranges specified above us looked up.
    | Replace this raw data with your own location. 
    |
    */
    'localhost_raw_data' => [
        'city' => [
            'geoname_id' => 2645801,
            'names' => ['en' => 'Kensington'],
        ],
        'continent' => [
            'code' => 'EU',
            'geoname_id' => 6255148,
            'names' => ['en' => 'Europe'],
        ],
        'country' => [
            'geoname_id' => 2635167,
            'iso_code' => 'GB',
            'names' => ['en' => 'United Kingdom'],
        ],
        'location' => [
            'accuracy_radius' => 20,
            'latitude' => 51.5047,
            'longitude' => -0.1998,
            'time_zone' => 'Europe/London',
        ],
        'postal' => [
            'code' => 'W8',
        ],
        'registered_country' => [
            'geoname_id' => 2635167,
            'iso_code' => 'GB',
            'names' => ['en' => 'United Kingdom'],
        ],
        'subdivisions' => [
            [
                'geoname_id' => 6269131,
                'iso_code' => 'ENG',
                'names' => ['en' => 'England'],
            ],
            [
                'geoname_id' => 3333157,
                'iso_code' => 'KEC',
                'names' => ['en' => 'Royal Kensington and Chelsea'],
            ],
        ],
        'traits' => [
            'ip_address' => '127.0.0.1',
        ],
    ],
];
