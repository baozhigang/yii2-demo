<?php

namespace common\filters;

use Exception;
use Yii;
use yii\base\ActionFilter;
use yii\web\Response;

class ResponseFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return true;
    }
}
