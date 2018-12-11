<?php

namespace fl0v\yii2\rollbar\handlers;

use fl0v\yii2\rollbar\ErrorHandlerTrait;

class WebErrorHandler extends \yii\web\ErrorHandler
{
    use ErrorHandlerTrait;
}
