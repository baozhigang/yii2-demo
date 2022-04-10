<?php

namespace frontend\controllers;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\Promise;
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
     * 还是没有拿到异步请求返回的值。
     * 只有调用 wait 方法同步之后，才能拿到返回值，
     * 那么怎么使用异步的返回值？
     */
    public function actionIndex2()
    {
        $client = new HttpClient();
        $promise = $client->requestAsync('GET', 'http://httpbin.org/get');

        $promise->then(
            function (ResponseInterface $res) {
                echo $res->getStatusCode() . "\n";
                echo $res->getBody()->getContents();
            },
            function (RequestException $ex) {
                echo $ex->getMessage() .' '. $ex->getRequest()->getMethod();
            }
        );

        // echo $promise->getState();

        $promise->wait();
    }

    public function actionP1()
    {
        $promise = new Promise();
        $promise->then(
            function ($value) {
                echo 'value ' . $value;
            },
            function ($reason) {
                echo 'reason' . $reason;
            }
        );

        $promise->resolve('hello.');
    }
}