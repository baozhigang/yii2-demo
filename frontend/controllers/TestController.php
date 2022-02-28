<?php

namespace frontend\controllers;

use Exception;
use yii\web\Controller;
use yii\web\Response;
use Yii;
use common\exceptions\CipherException;
use yii\web\BadRequestHttpException;

class TestController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        // Yii::$app->response->format = Response::FORMAT_JSON;

        throw new BadRequestHttpException('测试一个错误2');

        return [1,2];
    }
}