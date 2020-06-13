<?php


namespace app\models\Integration;


use Aws\Credentials\Credentials;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;

/**
 *
 * @property \Aws\S3\S3Client $client
 */
class S3 extends Component
{

    public  $key;

    public  $secret;

    public  $region;

    public  $endpoint;

    public  $bucket;

    private $_client;

    private $_credentials;

    public function init(): void
    {

        if (empty($this->key)) {
            throw new InvalidConfigException(get_class($this) . '::key cannot be empty.');
        }
        if (empty($this->secret)) {
            throw new InvalidConfigException(get_class($this) . '::secret cannot be empty.');
        }
        if (empty($this->region)) {
            throw new InvalidConfigException(get_class($this) . '::region cannot be empty.');
        }
        $this->_credentials = new Credentials($this->key, $this->secret);
        parent::init();
    }

    public function getClient(): \Aws\S3\S3Client
    {

        if (!$this->_client) {
            $opts = [
                'version'     => 'latest',
                'region'      => $this->region,
                'endpoint'    => $this->endpoint,
                'credentials' => $this->_credentials,
            ];


            if (!empty($this->endpoint)) {
                $opts['endpoint'] = $this->endpoint;
            }

            $this->_client = new \Aws\S3\S3Client($opts);
        }

        return $this->_client;
    }

    public function uploadFile(string $filepath, string $name, string $directory = 'temporary')
    {

        $fileMime = FileHelper::getMimeType($filepath);
        $fileSize = filesize($filepath);
        $this->getClient()->putObject(
            [
                'Bucket'      => $this->bucket,
                'Key'         => $directory . '/' . $name,
                'Body'        => file_get_contents($filepath),
                'ContentType' => $fileMime,
                'ACL'         => 'public-read',
            ]
        );

        return [$directory . '/' . $name, $fileMime, $fileSize];
    }

    public function copyFile(string $fileFrom, string $fileTo)
    {

        $this->getClient()->copy(
            $this->bucket,
            $fileFrom,
            $this->bucket,
            $fileTo,
            'public-read'
        );
    }
}