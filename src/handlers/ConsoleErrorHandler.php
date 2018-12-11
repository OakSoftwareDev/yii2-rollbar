<?php

namespace fl0v\yii2\rollbar\handlers;

use fl0v\yii2\rollbar\ErrorHandlerTrait;

class ConsoleErrorHandler extends \yii\console\ErrorHandler
{
    use ErrorHandlerTrait;
}
