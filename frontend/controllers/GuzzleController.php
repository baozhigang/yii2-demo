<?php

namespace frontend\controllers;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use yii\web\Controller;

class GuzzleController extends Controller
{
    /**
     * 1.响应容易坑新手，response是个对象，需要 getBody() 返回的是 流
     *  getContents() 才能返回字符串
     */
    public function actionIndex()
    {
        $client = new HttpClient();
        $response = $client->request('GET', 'http://open.api.tianyancha.com/services/open/ic/baseinfo/2.0?keyword=中航重机股份有限公司', [
            'headers' => [
                'Authorization' => '6bb1b9c3-b3b1-4740-94df-097f606063a6',
            ]
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * 这个异步为什么没有生效？
     *
     */
    public function actionIndex2()
    {
        $client = new HttpClient();
        // $promise = $client->requestAsync('GET', 'http://httpbin.org/get');

        // // return $promise->getBody()->getContents();

        // $promise->then(
        //     function (ResponseInterface $res) {
        //         return $res->getStatusCode() . "\n";
        //     },
        //     function (RequestException $ex) {
        //         return $ex->getMessage() .' '. $ex->getRequest()->getMethod();
        //     }
        // );

        $promise = $client->requestAsync('GET', 'http://httpbin.org/get');
        $promise->then(
            function (ResponseInterface $res) {
                return $res->getStatusCode() . "\n";
            },
            function (RequestException $e) {
                echo $e->getMessage() . " " . $e->getRequest()->getMethod();
            }
        );

    }
}