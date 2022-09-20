<?php

namespace oaksoftwaredev\yii2\rollbar;

use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use Yii;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use oaksoftwaredev\yii2\rollbar\helpers\GetRollbarTrait;

/**
 * Handles yii errors both in web and console.
 *
 * Example:
 *
 * ```php
 *  'errorHandler' => [
 *      // In console config oaksoftwaredev\yii2\rollbar\handlers\ConsoleErrorHandler should be used
 *      'class' => 'oaksoftwaredev\yii2\rollbar\handlers\WebErrorHandler',
 *
 *      'payload' => function ($errorHandler) {
 *          return [
 *              // For payload data to be shown in rollbar you nead to use `custom_data_method_context` key
 *              'custom_data_method_context' => [
 *                  'foo' => 'bar',
 *                  'xyz' => getSomeData(),
 *              ],
 *          ];
 *      },
 *  ]
 * ```
 */
trait ErrorHandlerTrait
{
    use GetRollbarTrait;

    /**
     * @var null|array|callable Array data or callback returning a payload data associative array or null.
     *                          For payload data to show up in rollbar it neads to be put inside `custom_data_method_context` key.
     * @see buildPayload()
     * @see https://docs.rollbar.com/docs/php-configuration-reference
     * @see https://github.com/rollbar/rollbar-php-examples/blob/master/custom-data-method/example.php
     */
    public $payload;

    /**
     * First send to rollbar, then pass thru yii handler.
     * @param \Exception $exception
     */
    public function logException($exception)
    {
        $this->rollbar->log(
            $this->getSeverityLevel($exception),
            $exception,
            $this->buildPayload($exception),
            $isUncaught = true
        );
        parent::logException($exception);
    }

    /**
     * Determine the severity level of the error
     * @param  \Exception $exception
     * @return string     Rollbar level
     */
    protected function getSeverityLevel($exception)
    {
        $isFatal = false;
        $isFatal = $isFatal || $exception instanceof \Error;
        $isFatal = $isFatal || (
            $exception instanceof ErrorException
            && ErrorException::isFatalError(['type' => $exception->getSeverity()])
        );
        return $isFatal ? Level::CRITICAL : Level::ERROR;
    }

    /**
     * Will merge exception payload with the error handler payload.
     * @param  \Exception $exception
     * @return null|array Payload data
     */
    protected function buildPayload($exception)
    {
        $payload = null;
        if (! empty($this->payload)) {
            if (is_callable($this->payload)) {
                $payload = call_user_func($this->payload, $this);
            } elseif (is_array($this->payload)) {
                $payload = $this->payload;
            }
        }
        if ($exception instanceof PayloadInterface) {
            $payload = ArrayHelper::merge((array) $payload, (array) $exception->rollbarPayload());
        }
        return $payload;
    }
}
