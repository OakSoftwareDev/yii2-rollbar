<?php

namespace oaksoftwaredev\yii2\rollbar\handlers;

use oaksoftwaredev\yii2\rollbar\ErrorHandlerTrait;

class WebErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}
