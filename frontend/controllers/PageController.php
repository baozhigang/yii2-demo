<?php

namespace frontend\controllers;

use Yii;
use yii\data\Pagination;
use yii\web\Controller;

class PageController extends Controller
{
    public function actionIndex()
    {
        $count = Yii::$app->db->createCommand('select count(*) from book')->queryScalar();

        $pagination = new Pagination([
            'totalCount' => $count,
            'pageSize' => 10,
        ]);

        $data = Yii::$app->db->createCommand("select * from book limit {$pagination->offset}, {$pagination->limit}")->queryAll();

        return $this->render('index', [
            'data' => $data,
            'pagination' => $pagination,
        ]);
    }
}