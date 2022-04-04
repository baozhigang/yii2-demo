<?php

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\tests\fixtures',
            // 'class' => 'yii\faker\FixtureController',
            // 'templatePath' => '@common/tests/templates/fixtures',
            // 'fixtureDataPath' => '@common/tests/fixtures/data',
          ],
    ],
    'components' => [
    ],
    'modules' => [],
];
