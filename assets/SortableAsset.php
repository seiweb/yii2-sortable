<?php

namespace seiweb\sortable\assets;

use yii\web\AssetBundle;

class SortableAsset extends AssetBundle
{
    public $sourcePath = '@vendor/seiweb/yii2-sortable/assets/files';

    public $js = [
        'js/sortable-widgets.js',
    ];

    public $css = [
        'css/sortable-widgets.css',
    ];

    public $depends = [
        'seiweb\sortable\assets\RubaxaAsset',
    ];
}
