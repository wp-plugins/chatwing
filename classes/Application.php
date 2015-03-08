<?php namespace Chatwing\IntegrationPlugins\WordPress;

/**
 * @package Chatwing\IntegrationPlugins\Wordpress
 * @author chatwing
 */
use Chatwing\Encryption\DataEncryptionHelper;
use Chatwing\Application as Chatwing;

class Application extends PluginBase
{
	protected function init()
	{
		if (!defined('CHATWING_ENCRYPTION_KEY')) {
			$this->onPluginActivation();
			$this->getModel()->saveAccessToken('');
			return;
		}

		DataEncryptionHelper::setEncryptionKey(CHATWING_ENCRYPTION_KEY);
		Chatwing::getInstance()->bind('access_token', $this->getModel()->getAccessToken());
		add_shortcode('chatwing', array('Chatwing\\IntegrationPlugins\\WordPress\\ShortCode', 'render'));
	}

	protected function registerHooks()
	{
		register_activation_hook(CHATWING_PLG_MAIN_FILE, array($this, 'onPluginActivation'));
		if ($this->getModel()->hasAccessToken()) {
			add_action('widgets_init', function(){
				register_widget('Chatwing\\IntegrationPlugins\\WordPress\\Widget');
			});
		}
	}

	protected function registerFilters()
	{

	}

	public function onPluginActivation()
	{
		// check if we have encryption key
		$filePath = CHATWING_PATH . '/key.php';
		if (!file_exists($filePath)) {
			$encryptionKey = DataEncryptionHelper::generateKey();
			$n = file_put_contents($filePath, "<?php define('CHATWING_ENCRYPTION_KEY', '{$encryptionKey}');?>");
			if ($n) {
				require $filePath;
			} else {
				die("Cannot create encryption key.");
			}
		}
	}

	public function run()
	{
		parent::run();

		if (is_admin()) {
			$admin = new Admin($this->getModel());
			$admin->run();
		}
	}

}