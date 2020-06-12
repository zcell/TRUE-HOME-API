<?php


namespace models\Integration;


use Aws\Credentials\Credentials;
use yii\base\Component;
use yii\base\InvalidConfigException;

class S3 extends Component
{
    public $key;
    public $secret;
    public $region;
    public $endpoint;
    public $bucket;

    private $_client;
    private $_credentials;

    public function init()
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

    public function getClient(){
        if (!$this->_client) {
            $opts = [
                'version' => 'latest',
                'region' => $this->region,
                'endpoint' => $this->endpoint,
                'credentials' => $this->_credentials,
            ];


            if (!empty($this->endpoint)) {
                $opts['endpoint'] = $this->endpoint;
            }

            $this->_client  = new \Aws\S3\S3Client($opts);
        }
        return $this->_client;
    }
}