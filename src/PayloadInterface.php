<?php

namespace fl0v\yii2\rollbar;

interface PayloadInterface
{
    /**
     * @return null|array Payload data to be sent to Rollbar
     */
    public function rollbarPayload();
}
