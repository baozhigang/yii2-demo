<?php

namespace frontend\controllers;

use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [

        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 需要满足3个条件：
     * 1.配置文件添加配置：frontend/config/main.php
     * 2.返回格式是html格式，这是errorhandler里要求的
     * 3.关闭debug模式 或者是用户异常，比如BadRequestHttpException（UserException下的异常）
     *  vendor/yiisoft/yii2/web/ErrorHandler.php  105-109行
    */
    // public function actionError2()
    // {
    //     Yii::$app->response->format = Response::FORMAT_JSON;

    //     $exception = Yii::$app->errorHandler->exception;
    //     if ($exception !== null) {
    //         // return $this->render('error', ['exception' => $exception]);
    //         // return $exception->getMessage();
    //         return [3,3];
    //     }

    //     return [1,2,3];
    // }


}
