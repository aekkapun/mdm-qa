<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\MyDate;
/* @var $this yii\web\View */
/* @var $model app\models\Booking */
use chillerlan\QRCode\QRCode;
use yii\helpers\Url;
$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="booking-view">
    <?php
    $data = Url::current([], true);
    $qr = new QRCode();
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                    'format'=>'html',
                    'label' => 'ห้องประชุม',
                    'value' => '<span style="color:green;">'.$model->room->room_title.'</span>'
                ],
            'title',
                [
                    'format'=>'html',
                    'label' => 'รายละเอียด',
                    'value' => '<span style="color:green;">'.$model->content.'</span>'
                ],
             [
                    'format'=>'html',
                    'label' => 'เริ่ม',
                    'value' =>MyDate::getDateThaifull($model->start_meet),
             ],
            [
                    'format'=>'html',
                    'label' => 'ถึง',
                    'value' =>MyDate::getDateThaifull($model->end_meet),
             ],
            'booking',
            'status',
            [
                'format'=>'raw',
                'label' => 'QR_CODE',
                'value' => '<img src="'.$qr->render($data).'" class="rounded mx-auto d-block" alt="...">'
            ],
        ],
    ]) ?>
    <div class="panel panel-default">
        <button type="button" class="btn btn-success pull-right">ภาพห้องประชุม</button>
        <br/>
        <div class="panel-body">
            <?= dosamigos\gallery\Gallery::widget(['items' => $model->getThumbnails($model->room->ref,$model->room->room_title)]);?>
        </div>
    </div>
</div>
