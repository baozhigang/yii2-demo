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
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
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

    public function actionError2()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            // return $this->render('error', ['exception' => $exception]);
            return $exception->getMessage();
        }

        // return [1,2,3];
    }


}
