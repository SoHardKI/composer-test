<?php

namespace millenion\testnsignwidget;
use yii\web\AssetBundle;


class TestNsignWidgetAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/Assets';

    public $js = [
    "nicEdit/nicEdit.js",    
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $depends = [      
    ];   
    
}