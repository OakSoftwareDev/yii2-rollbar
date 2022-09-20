<?php

namespace oaksoftwaredev\yii2\rollbar;

interface PayloadInterface
{
    /**
     * @return null|array Payload data to be sent to Rollbar
     */
    public function rollbarPayload();
}
