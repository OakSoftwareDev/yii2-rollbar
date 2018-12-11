<?php

namespace fl0v\yii2\rollbar;

use Rollbar\Payload\Level;
use Rollbar\Rollbar;
use yii\log\Logger;
use yii\log\Target;
use fl0v\yii2\rollbar\helpers\GetRollbarTrait;

/**
 * Will send yii log messages to rollbar
 */
class RollbarTarget extends Target
{
    use GetRollbarTrait;

    /**
     * @var string Added to every log payload.
     *             Useful if you want to have a *yii2-debug*-like  grouping.
     * @see export()
     */
    protected $requestId;

    /**
     * Initialize $requestId and checks for rollbar component.
     * @throws \Exception if rollbar component not defined
     */
    public function init()
    {
        if (empty($this->rollbar)) {
            throw new \Exception('Rollbar component must be set!');
        }
        $this->requestId = uniqid(gethostname(), true);
        parent::init();
    }

    /**
     * Will send to Rollbar messages produced by Yii::info(), Yii::warning(), Yii::error(), Yii::debug().
     * Only if rollbar is enabled.
     * @see RollbarLoader::getEnabled()
     */
    public function export()
    {
        if ($this->rollbar && $this->rollbar->enabled) {
            foreach ($this->messages as $row) {
                list($message, $level, $category, $timestamp) = $row;
                $this->rollbar->log(
                    $this->getSeverityLevel($level),
                    $message,
                    $extra = [
                        'category'   => $category,
                        'request_id' => $this->requestId,
                        'timestamp'  => $timestamp,
                    ]
                );
            }
        }
    }

    /**
     * Convert \yii\log\Logger level to Rollbar level.
     * @param  int    $level Severity level as defined in \yii\log\Logger
     * @return string Returns severity level that could be recognized by Rollbar
     */
    protected function getSeverityLevel($level)
    {
        if (in_array($level, [Logger::LEVEL_PROFILE, Logger::LEVEL_PROFILE_BEGIN, Logger::LEVEL_PROFILE_END, Logger::LEVEL_TRACE])) {
            return Level::DEBUG;
        }
        return Logger::getLevelName($level);
    }
}
