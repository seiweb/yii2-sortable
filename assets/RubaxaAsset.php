<?php

namespace seiweb\sortable\assets;

use yii\web\AssetBundle;

class RubaxaAsset extends AssetBundle
{
    public $sourcePath = '@vendor/seiweb/yii2-sortable/assets/files';

    public $js = [
        //'//rubaxa.github.io/Sortable/Sortable.js',
        'js/sortable.js',
        'js/jquery.binding.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
