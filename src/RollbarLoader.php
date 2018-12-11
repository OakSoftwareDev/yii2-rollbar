<?php

namespace fl0v\yii2\rollbar;

use Rollbar\Rollbar;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Will initialize Rollbar with Yii2 environment custom payload.
 * @see https://github.com/rollbar/rollbar-php
 * @see https://docs.rollbar.com/docs/basic-php-installation-setup
 */
class RollbarLoader extends Component
{
    /**
     * @var array default configuration for rollbar
     * @see https://docs.rollbar.com/v1.0.0/docs/php-configuration-reference
     */
    private $config = [
        'environment'         => YII_ENV,
        'enabled'             => true,
        'root'                => '@app',
        'capture_username'    => true,  // to send person.username if set
        'use_error_reporting' => true,  // will send all errors covered by error_reporting() setting
        'included_errno'      => -1,    // otherwise rollbar skips notices, use ROLLBAR_INCLUDED_ERRNO_BITMASK constant for default rollbar behavior
        'scrub_fields'        => ['passwd', 'password', 'secret', 'confirm_password', 'password_confirmation', 'auth_token', 'csrf_token', '_csrf'],
        'custom'              => [
            'YII2_PATH' => YII2_PATH,
            'YII_DEBUG' => YII_DEBUG,
            'YII_ENV'   => YII_ENV,     // in many situations i use different logic for the environment reported to rollbar but i still want to know the YII_ENV value
        ],
    ];

    public function setConfig($cfg)
    {
        $this->config = ArrayHelper::merge($this->config, $cfg);
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getEnabled()
    {
        return ! empty($this->config['enabled']);
    }

    public function getEnvironment()
    {
        return ArrayHelper::getValue($this->config, 'environment');
    }

    public function getAccessToken()
    {
        return ArrayHelper::getValue($this->config, 'access_token');
    }

    public function init()
    {
        if (empty($this->config['access_token'])) {
            throw new \Exception('Rollbar requires access_token!');
        }
        if (! empty($this->config['root'])) {
            $this->config['root'] = \Yii::getAlias($this->config['root']);
        }
        if (empty($this->config['framework'])) {
            $this->config['framework'] = 'Yii '.\Yii::getVersion();
        }
        $this->config['custom'] = ArrayHelper::merge([
            'app_id'       => \Yii::$app->id,
            'app_name'     => \Yii::$app->name,
            'app_language' => \Yii::$app->language,
            'app_charset'  => \Yii::$app->charset,
            'app_class'    => get_class(\Yii::$app),
        ], $this->config['custom']);
        Rollbar::init(
            $this->config,
            $handleException = false,
            $handleError = false,
            $handleFatal = false
        );
    }

    /**
     * Send log to Rollbar.
     *
     * @param string $level      Severity level as defined in Rollbar
     * @param mixed  $toLog      The thing to be logged (message, exception, error)
     * @param array  $extra      Extra params to be sent along with the payload
     * @param bool   $isUncaught It will be set to true if the error was caught by the global error handler
     */
    public function log($level, $toLog, $extra = [], $isUncaught = false)
    {
        Rollbar::log($level, $toLog, $extra, $isUncaught);
    }
}
