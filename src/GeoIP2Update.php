<?php
namespace Nbz4live\LaravelGeoIP2;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Config\Repository as Config;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;

class GeoIP2Update
{
    /** @var \Illuminate\Config\Repository */
    private $config;

    /** @var \Symfony\Component\Console\Output\OutputInterface */
    private $output;

    private $guzzle;

    protected $baseUrl = 'https://updates.maxmind.com';

    protected $storagePath;
    protected $products;

    private $accountId;
    private $licenseKey;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->output = new NullOutput();
        $this->guzzle = new Client(array(
            'base_uri' => $this->baseUrl,
            'defaults' => [
                'exceptions' => true,
            ]
        ));

        $cfgStoragePath = $this->config->get('geoip2.storage_path');
        if (empty($cfgStoragePath)) {
            $cfgStoragePath = storage_path('geoip');
        }

        if (!file_exists($cfgStoragePath))
        {
            mkdir($cfgStoragePath, 0777, true);
        }

        $this->storagePath = $cfgStoragePath;
        $this->products = $this->config->get('geoip2.products');

        $this->accountId = $this->config->get('geoip2.account_id');
        $this->licenseKey = $this->config->get('geoip2.license_key');

        if (empty($this->accountId) || empty($this->licenseKey)) {
            $this->accountId = 999999;
            $this->licenseKey = '000000000000';
        }
    }

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function update()
    {
        $errors = 0;
        foreach ($this->products as $productId => $filename)
        {
            if (!is_string($productId)) {
                $productId = $filename;
                $filename = '';
            }

            if ($this->updateDatabase($productId, $filename) === false) {
                $errors++;
            }
        }

        return (!$errors);
    }

    public function updateDatabase($productId, $filename)
    {
        try
        {
            if (empty($filename))
            {
                $response = $this->guzzle->get('/app/update_getfilename', [
                    'query' => ['product_id' => $productId],
                ]);
                $filename = $response->getBody()->getContents();
            }

            $filepath = sprintf('%s/%s', $this->storagePath, $filename);
            $tmpFilepath = $filepath.'.gz';

            $md5File = '00000000000000000000000000000000';
            if (file_exists($filepath))
            {
                $md5File = md5_file(sprintf('%s/%s', $this->storagePath, $filename));
            }

            $response = $this->guzzle->get('/app/update_getipaddr');
            $clientIpAddress = $response->getBody()->getContents();

            $md5Client = md5($this->licenseKey.$clientIpAddress);
            $this->guzzle->get('/app/update_secure', [
                'query' => [
                    'db_md5' => $md5File,
                    'challenge_md5' => $md5Client,
                    'account_id' => $this->accountId,
                    'edition_id' => $productId,
                ],
                'save_to' => $tmpFilepath,
                'decode_content' => false
            ]);
        }
        catch (ClientException $clientException)
        {
            $this->output->writeln(sprintf(
                '<error>Error updating database with Product ID "%s". Reason: "%d %s." Body: "%s"</error>',
                $productId,
                $clientException->getResponse()->getStatusCode(),
                $clientException->getResponse()->getReasonPhrase(),
                \str_replace("\n", '', $clientException->getResponse()->getBody()->getContents())
            ));

            return false;
        }

        $noUpdates = 'No new updates available';
        if (strcasecmp($noUpdates, file_get_contents($tmpFilepath, false, null, 0, strlen($noUpdates))) == 0) {
            $this->output->writeln(sprintf('<info>Product ID "%s" database "%s" is already up to date</info>', $productId, $filename));
            return true;
        }

        $gzFileHandle = gzopen($tmpFilepath, 'r');
        $fileHandle = fopen($filepath, 'w');
        if ($gzFileHandle !== false && $fileHandle !== false) {
            while (!gzeof($gzFileHandle)) {
                fwrite($fileHandle, gzread($gzFileHandle, 1024));
            }

            fclose($fileHandle);
            gzclose($gzFileHandle);
            unlink($tmpFilepath);
        }

        $this->output->writeln(sprintf('<info>Updated Product ID "%s" database "%s"</info>', $productId, $filename));
        return true;
    }
}
