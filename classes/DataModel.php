<?php namespace Chatwing\IntegrationPlugins\WordPress;

use Chatwing\Object;
use Chatwing\Encryption\DataEncryptionHelper;
use Chatwing\Application as ChatwingContainer;

class DataModel extends Object
{
    protected $token = null;

    protected static $isntance = null;

    function __construct()
    {
        $this->getAccessToken();
    }

    /**
     * @return DataModel|null
     */
    public static function getInstance()
    {
        if (is_null(self::$isntance)) {
            self::$isntance = new self;
        }

        return self::$isntance;
    }

    public function hasAccessToken()
    {
        return (bool) $this->getAccessToken();
    }

    public function getAccessToken()
    {
        if (is_null($this->token)) {
            try {
                $this->token = DataEncryptionHelper::decrypt(get_option('chatwing_access_token'));
            } catch (\Exception $e) {

            }
        }
        return $this->token;
    }

    /**
     * Save access token
     * @param  $token
     */
    public function saveAccessToken($token)
    {
        if ($token) {
            $token = DataEncryptionHelper::encrypt($token);
        }

        $this->token = $token;

        update_option('chatwing_access_token', $token);
    }

    public function deleteAccessToken()
    {
        return delete_option('chatwing_access_token');
    }

    public function saveOption($key, $value)
    {
        update_option('chatwing_default_' . $key, $value);
    }

    public function getOption($key, $default = null)
    {
        return get_option( 'chatwing_default_' . $key, $default );
    }

    public function getBoxList()
    {
        $boxes = array(); 

        try {
            $api = ChatwingContainer::getInstance()->get('api');
            $response = $api->call('user/chatbox/list');

            if ($response->isSuccess()) {
                $boxes = $response->get('data');
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }

        return $boxes;
    }
}