<?php

namespace seiweb\sortable\grid;

use seiweb\sortable\assets\SortableAsset;
use yii\helpers\Html;
use yii\web\View;

class Column extends \yii\grid\Column
{
    public $headerOptions = ['style' => 'width: 30px;'];

    public function init()
    {
        SortableAsset::register($this->grid->view);
        $this->grid->view->registerJs('initSortableWidgets();', View::POS_READY, 'sortable');
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        return Html::tag('div', '<span class="glyphicon glyphicon-menu-hamburger"></span>', [
            'class' => 'sortable-widget-handler',
            'data-id' => $model->id,
        ]);
    }
}
