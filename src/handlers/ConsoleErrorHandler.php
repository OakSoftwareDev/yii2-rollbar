<?php

namespace oaksoftwaredev\yii2\rollbar\handlers;

use oaksoftwaredev\yii2\rollbar\ErrorHandlerTrait;

class ConsoleErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}
