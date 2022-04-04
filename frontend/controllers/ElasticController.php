<?php

namespace frontend\controllers;

use yii\web\Controller;

class ElasticController extends Controller
{
    public function actionIndex()
    {
        return [1,2,3];
    }
}