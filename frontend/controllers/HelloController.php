<?php

namespace frontend\controllers;

use yii\web\Controller;

class HelloController extends Controller
{
    public function actionIndex($param)
    {
        return $param;
    }

    public function getVaule($param)
    {
        return $param;
    }

}