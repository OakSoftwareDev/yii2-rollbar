Rollbar for Yii2
================

This extension is a fork from [baibaratsky/yii2-rollbar](https://github.com/baibaratsky/yii2-rollbar) and [eroteev/yii2-rollbar](https://github.com/eroteev/yii2-rollbar).
For Yii 1.x use [baibaratsky/yii-rollbar](https://github.com/baibaratsky/yii-rollbar).

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/). 

 To install, either run
 ```
 $ php composer.phar require fl0v/yii2-rollbar
 ```
 or add
 ```
 "fl0v/yii2-rollbar": "*"
 ```
 to the `require` section of your `composer.json` file.

Usage
-----
- Add the component configuration in your **global** config file:

```php
'components' => [
    'rollbar' => [
        'class' => 'fl0v\yii2\rollbar\RollbarLoader',
        'config' => [
            'environment' => '{your_environment}',
            'access_token' => '{rollber_access_token}',
            // other Rollbar config settings
        ],
    ],
]
```

- Add the **web** error handler configuration in your **web** config file:

```php
'components' => [
    'errorHandler' => [
        'class' => 'fl0v\yii2\rollbar\handlers\WebErrorHandler',
    ],
],
```

- Add the **console** error handler configuration in your **console** config file:

```php
'components' => [
    'errorHandler' => [
        'class' => 'fl0v\yii2\rollbar\handlers\ConsoleErrorHandler',
    ],
],
```

Payload from your exceptions
----------------------------
If you want your exceptions to send some additional data to Rollbar,
it is possible by implementing `PayloadInterface`.

```php
use fl0v\yii2\rollbar\PayloadInterface;
 
class SomeException extends \Exception implements PayloadInterface
{
    public function rollbarPayload()
    {
        return ['foo' => 'bar'];
    }
}
```

Log Target
----------
You may want to collect your logs produced by `Yii::error()`, `Yii::info()`, etc. in Rollbar.
Put the following code in your config:

```php
'components' => [
    'log' => [
        'targets' => [
            [
                'class' => 'fl0v\yii2\rollbar\RollbarTarget',
                'levels' => ['error', 'warning', 'info'], // Log levels you want to appear in Rollbar             
                'categories' => ['application'],
            ],
        ],
    ],
],
```

Rollbar Javascript
------------------
Rollbar offers Javascript debugger aswell, see https://docs.rollbar.com/docs/javascript.
To use it in Yii2 there is `fl0v\yii2\rollbar\RollbarAsset` that you  can register in your main template.

RollbarAsset is used independently of the server side component, to configure it use assetManager.
For the config part of RollbarAsset checkout Rollbar reference https://docs.rollbar.com/docs/rollbarjs-configuration-reference#section-reference.

```php
'assetManager' => [
    ...
    'bundles' => [
        ....
        'fl0v\yii2\rollbar\RollbarAsset' => [
            // Rollbar configuration
            'config' => [
                'enbaled' => true,
                'accessToken' => '{token}',
                // initialize payload
                'payload' => [
                    'environment' => '{environment}',
                    'client' => [
                        'javascript' => [
                            'code_version' => '...',
                        ],
                    ],
                ],
            ],
            // metrics to add to payload, is called when the asset is registered
            'payload' => function () {
                return [
                    'person' => [
                        'id' => \Yii::$app->has('user') ? (string) \Yii::$app->user->id : null,
                        'username' => \Yii::$app->has('user') && ! \Yii::$app->user->isGuest ? \Yii::$app->user->identity->username : null,
                    ],
                    'custom' => [
                        'myData' => 'asd',
                        'key' => uniqid(),
                    ],
                ];
            ],
        ],
    ],
],
```

