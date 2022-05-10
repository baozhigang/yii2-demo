<?php

namespace common\filters;

use Exception;
use Yii;
use yii\base\ActionFilter;
use yii\data\DataProviderInterface;
use yii\web\Response;

class ResponseFilter extends ActionFilter
{
    public function beforeAction($action)
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        return true;
    }

    public function afterAction($action, $result)
    {
        if (!$result instanceof DataProviderInterface) {
            return $result;
        }

        $pager = $result->getPagination();

        return [
            'data' => $result->getModels(),
            'pager' => [
                'page_size' => $pager->getPageSize(),
                'page' => $pager->getPage() + 1,
                'page_count' => $pager->getPageCount(),
                'total_count' => $pager->totalCount,
            ]
        ];
    }
}
