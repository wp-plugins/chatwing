<?php
/**
 * @author chatwing <dev@chatwing.com>
 */

namespace Chatwing\IntegrationPlugins\WordPress;

use Chatwing\Application as App;


class ShortCode
{
    /**
     * @param array $params
     * @return string
     */
    public static function render($params = array())
    {
        $model = DataModel::getInstance();

        $defaultAttributes = array(
            'width' => $model->getOption('width'),
            'height' => $model->getOption('height')
        );

        $params = array_merge($defaultAttributes, $params);

        if (empty($params['key']) && empty($params['alias'])) {
            return '';
        }

        /**
         * @var \Chatwing\Chatbox $box
         */
        $box = App::getInstance()->get('chatbox');
        if (!empty($params['key'])) {
            $box->setKey($params['key']);
        } else {
            $box->setAlias($params['alias']);
        }

        
        $box->setData('width', $params['width']);
        $box->setData('height', $params['height']);

        if (!empty($params['enable_custom_login']) 
            && $params['enable_custom_login'] == '1' 
            && !empty($params['custom_login_secret'])) {
            $user = wp_get_current_user();
            $customSession = array(
                'id' => $user->ID,
                'name' => $user->user_nicename,
                'avatar' => '',
                'expiration' => round(microtime(true) * 1000) + 60 * 60 * 100
            );
            $box->setParam('custom_session', $customSession);
            $box->setSecret($params['custom_login_secret']);
        }

        return $box->getIframe();
    }
}