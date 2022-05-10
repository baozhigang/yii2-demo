<?php

namespace frontend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Controller;
use yii\web\Response;

class ProviderController extends Controller
{
    public function actionIndex()
    {
        $query = (new Query())
            ->from('book');

        $pd = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $pd;
    }
}