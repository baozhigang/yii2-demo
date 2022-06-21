<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * 网络请求超时实验验证
 *
 * nginx <> php <> mysql
 *
 * 验证不同环节超时Yii框架是否记录日志
 */
class TimeoutController extends Controller
{
    /**
     * nginx 请求超时，查看 fastcgi模块
     * https://nginx.org/en/docs/http/ngx_http_fastcgi_module.html#fastcgi_connect_timeout
     * 配置文件中设置超时时间：
     *    fastcgi_connect_timeout 1s;
     *    fastcgi_send_timeout 1s;
     *    fastcgi_read_timeout 3s;
     * 结论：
     *    nginx响应大于3S超时后，php会继续运行，
     *    不记录nginx的日志，如果PHP报错会记录日志
     */
    public function actionFastcgi()
    {
        sleep(10);

        Yii::warning('php is still runing');

        return 'fastcgi timeout';
    }

    /**
     * php 请求超时，查看 max_execution_time / set_time_limit
     * https://www.php.net/manual/en/function.set-time-limit.php
     * 配置文件中设置超时时间：
     *    max_execution_time=1
     * 结论：
     *    php运行超时后Yii框架记录了日志
     */
    public function actionPhp()
    {
        while (true) {
        }
    }

    /**
     * PDO 是php访问数据库的一套接口
     * PDO_MYSQL 是PHP访问mysql的数据库扩展，继承了PDO接口
     * MySQL extension： mysqli and PDO MYSQL 都通过 MySQL Native Driver 的服务跟mysql服务器交流
     * MySQL Native Driver 是最底层的。
     * 查看文档：https://www.php.net/manual/en/mysqlnd.config.php
     * 修改超时时间：
     *    mysqlnd.net_read_timeout=1 //默认86400
     * 结论：
     *    php调用mysql，mysql超时后会报错记录日志，之后的PHP程序不再运行
     */
    public function actionMysql()
    {
        Yii::$app->db->createCommand('select sleep(10)')->execute();

        Yii::warning('php is still runing');
    }

    // 总结：nginx超时后，没有日志。PHP继续执行，没有超时时间限制，调用mysql超时时间为默认的86400，如果超时会记录日志
    // 最好设置mysql的超时时间小于24小时，比如 60S，这样可以及时查看日志。
}