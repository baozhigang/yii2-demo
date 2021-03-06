# Yii2 示例项目

## 创建项目

执行：

    composer create-project "modi/yii2-starter:dev-master"

## 安装依赖、初始化

安装 PHP 依赖：`composer install`

安装前端依赖：`yarn install`

执行 Yii2 初始化脚本：`php init`

## 安装 Redis

略。

## 添加 Yii2 Redis 组件

1）添加依赖

执行：`composer require "yiisoft/yii2-redis:~2.0.0"`

2）添加配置

在 `common/config/main.php` 里添加组件配置：

    return [
        // ...
        'components' => [
            'redis' => [
                'class' => 'yii\redis\Connection',
                'hostname' => 'redis', // Redis 服务地址
                'port' => 6379,
                'database' => 0,
                'password' => 'NOT_SAFE',
            ],
        ],
    ];

## 添加 Yii2 Queue 组件

1）添加依赖

执行：`composer require "yiisoft/yii2-queue:^2"`

2）添加配置

在 `common/config/main.php` 里添加配置：

    return [
        // ...
        'bootstrap' => [
            'queue',
        ],
        'components' => [
            'queue' => [
                'class' => \yii\queue\redis\Queue::class,
                'redis' => 'redis',
                'channel' => 'queue',
            ],
        ],
    ];

### Queue 的使用

1）编写负责执行业务逻辑的 Job 类：

```
namespace common\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\JobInterface;

class TestJob extends BaseObject implements JobInterface
{
    public $message;

    public function execute($queue)
    {
        Yii::warning($this->message); // 把 $message 写入日志
    }
}
```

2）在业务场景中提交 Job 实例：

```
use common\jobs\TestJob;
use Yii;
use yii\console\Controller;

$queue = Yii::$app->queue;
$queue->push(new TestJob(['message' => 'Job done!']));
```

3）运行处理 Job 的脚本

处理 Job，完成后退出：`php yii queue/run`。

监听队列，持续处理 Job：`php yii queue/listen`。

使用 Docker Compose 运行“监听队列”脚本：

```
services:
  queue:
    image: modicn/php:7.4.23-fpm-alpine3.14-dev
    command: ["php", "yii", "queue/listen"]
    depends_on:
      - redis
    init: true
    volumes:
      - .:/app
    working_dir: /app
```

## 使用 migration 管理表结构

1）创建 migration 类：

```
<?php

use yii\db\Migration;

class m220221_023713_add_book_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'author' => $this->string(60)->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('book');        
    }
}
```

2）更新表结构：

    php yii migrate/up

3）回滚/撤销：

    php yii migrate/down

## 使用 Fixtures 管理测试样例

1）创建 Fixture 类

2）执行 Fixtures

指定 Fixtures:

    php yii fixture/load Book

所有 Fixtures：

    php yii fixture/load "*"

3）卸载 Fixture：

    php yii fixture/unload Book

