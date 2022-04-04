<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\db\Query;

/**
 *  关闭mysql 缓存，批量查询，时间由2秒延长到3秒，
 *  内存使用峰值由 25 772 032 降低到 6 291 456
 */
class BatchController extends Controller
{
    public function actionQueryBuffered()
    {
        $db = Yii::$app->db;
        $db->open();
        $t1 = time();

        $this->stdout('方法1：开启结果缓存， 内存占用情况（字节）:'. "\n");

        $this->stdout(number_format(memory_get_peak_usage(true), 0). "\n");

        $query = (new Query())->from('book');
        foreach ($query->batch() as $books) {
            $this->stdout(number_format(memory_get_peak_usage(true), 0). "\n");
        }

        $this->stdout(time()-$t1);
    }

    public function actionQueryNoBuffered()
    {
        $db = Yii::$app->db;
        $db->open();
        $t1 = time();

        $this->stdout('方法1：开启结果缓存， 内存占用情况（字节）:'. "\n");

        $this->stdout(number_format(memory_get_peak_usage(true), 0). "\n");

        $db->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);

        $query = (new Query())->from('book');
        foreach ($query->batch() as $books) {
            $this->stdout(number_format(memory_get_peak_usage(true), 0). "\n");
        }

        $db->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);

        $this->stdout(time()-$t1);
    }

    public function actionAdd()
    {
        $values = [];
        for ($i=0; $i < 100000; $i++) {
            $values[] = ['book', 'zhangsan'];
        }

        Yii::$app->db->createCommand()->batchInsert('book', ['name', 'author'], $values)->execute();
    }
}