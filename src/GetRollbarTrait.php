<?php

namespace fl0v\yii2\rollbar;

use Yii;

/**
 * Implements access to RollbarLoader component
 */
trait GetRollbarTrait
{
    /**
     * @var string Name of rollbar component
     * @see getRollbar()
     */
    public $rollbarName = 'rollbar';

    /**
     * Load RollbarLoader component from Yii container using $rollbarName.
     * @return RollbarLoader rollbarLoader component
     */
    protected function getRollbar()
    {
        if (! empty($this->rollbarName) && Yii::$app->has($this->rollbarName)) {
            return Yii::$app->get($this->rollbarName);
        }
    }
}
