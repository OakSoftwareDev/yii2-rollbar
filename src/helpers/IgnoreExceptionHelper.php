<?php

namespace fl0v\yii2\rollbar\helpers;

/**
 * IgnoreExceptionHelper is used to prevent sending specific exceptions to Rollbar.
 *
 * Example usage:
 *
 * ```php
 * 'components' => [
 *     'rollbar' => [
 *          'class' => 'fl0v\yii2\rollbar\RollbarLoader',
 *          'config' => [
 *              // ...
 *              'check_ignore' => function($isUncaught, $toLog, $payload) {
 *                  return \fl0v\yii2\rollbar\helpers\IgnoreExceptionHelper::checkIgnore ($toLog, [
 *                      ['yii\web\HttpException', 'statusCode' => [400, 404]], // check properties
 *                      ['yii\db\Exception', 'getCode' => [2002]], // check method return values
 *                      ['yii\base\InvalidConfigException'], // ignore the exception
 *                      // ...
 *                  ]);
 *              },
 *          ]
 *     ],
 * ],
 * ```
 */
class IgnoreExceptionHelper
{
    /**
     * @param  mixed $toLog               string, exception or error
     * @param  array $ignoreExceptionList Configuration array in the format:
     *                                    [$class, $method => [$returnValue1, $returnValue2,...], $property => [$value1, $value2,...],]
     * @return bool  True to ignore the log or false otherwise
     */
    public static function checkIgnore($toLog, $ignoreExceptionList)
    {
        if (! ($toLog instanceof \Exception)) {
            return false;
        }

        foreach ($ignoreExceptionList as $ignoreConfig) {
            if ($toLog instanceof $ignoreConfig[0]) {
                $ignoreException = true;
                foreach (array_slice($ignoreConfig, 1) as $propertyOrMethod => $ignoredValues) {
                    if (method_exists($toLog, $propertyOrMethod)) {
                        $testValue = $toLog->$propertyOrMethod();
                    } else {
                        $testValue = $toLog->$propertyOrMethod;
                    }

                    if (! in_array($testValue, $ignoredValues)) {
                        $ignoreException = false;
                        break;
                    }
                }

                if ($ignoreException) {
                    return true;
                }
            }
        }

        return false;
    }
}
