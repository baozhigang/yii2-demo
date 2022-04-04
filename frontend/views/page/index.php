<?php

/* @var $this yii\web\View */

use yii\widgets\LinkPager;

?>
<div>
    <table>
        <thead>
        <tr>
            <th>书名</th>
            <th>作者</th>
        </tr>
        </thead>
        <tbody>
                <?php
                    foreach ($data as $val) {
                ?>
                <tr>
                    <td><?= $val['name'] ?></td>
                    <td><?= $val['author'] ?></td>
                </tr>
                <?php
                    }
                ?>
        </tbody>
    </table>
</div>
<div>总页数： <?= $pagination->totalCount; ?></div>
<div>
        <?php echo LinkPager::widget(['pagination' => $pagination,]); ?>
</div>
