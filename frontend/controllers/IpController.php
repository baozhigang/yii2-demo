<?php

namespace frontend\controllers;

use RuntimeException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

class IpController extends Controller
{
    public function actionIndex()
    {
        throw new BadRequestHttpException("测试错误1");
    }
}