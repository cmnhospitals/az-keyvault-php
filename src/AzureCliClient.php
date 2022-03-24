<?php


namespace AzKeyVault;

use Spatie\Url\Url;

class AzureCliClient extends Client {
    /** @var void */
    protected $az_path;

    /**
     * Client constructor
     */
    public function __construct() {
        $this->az_path = exec('which az');
        if ($this->az_path === false) {
            $this->az_path = '/usr/bin/az';
        }

        $this->client = new \GuzzleHttp\Client();

        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Get access token using managed identity
     * @return string
     */
    protected function getAccessToken() {
        // Get Access Token using Azure CLI
        $resource = 'https://vault.azure.net';
        $cmd = "$this->az_path account get-access-token --resource=$resource";
        $last_line = exec($cmd, $output_arr, $error_code);
        $output = implode("", $output_arr);

        return 'Bearer ' . json_decode($output)->accessToken;
    }
}
