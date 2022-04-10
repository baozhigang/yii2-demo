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
     * 异步处理，还没处理完，客户端拿不到任何结果，
     * then 是在回调里打印返回结果，
     * 如果使用了异步，场景就不是实时的返回给客户端结果，
     * 而是拿到返回结果，在回调里面操作数据库，或者其他下一步操作
     * 这是异步和同步的区别，实时返回直接用同步就好了
     * 不要使用wait调用，那就主动阻塞了
     *
     * 重点是了解异步的流程，以及什么时候用异步，什么时候用同步
     */
    public function actionIndex2()
    {
        $client = new HttpClient();
        $promise = $client->requestAsync('GET', 'http://httpbin.org/get');

        $promise->then(
            function (ResponseInterface $res) {
                // 修改数据库，或者其他操作
                echo $res->getStatusCode() . "\n";
                echo $res->getBody()->getContents();
            },
            function (RequestException $ex) {
                echo $ex->getMessage() .' '. $ex->getRequest()->getMethod();
            }
        );

        // echo $promise->getState();
        // $promise->wait();
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