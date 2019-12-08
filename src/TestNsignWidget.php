<?php

namespace millenion\testnsignwidget;

use yii\base\Widget;
use yii\widgets\InputWidget;
use yii\helpers\Html;
use millenion\testnsignwidget\TestNsignWidgetAsset;
use yii\helpers\Json;

/**
 * Основой является виджет NiceEdit
 *
 */
class TestNsignWidget extends InputWidget
{
    public $editorOptions = [];

    /**
     * Начальные данные в textarea
     * @var string
     */
    public $content;

    /**
     * тип по умолчанию
     * @var string
     */
    public $type = 'text';

    /**
     * Генерация ID для input field
     * @var string
     */

    private $inputId;

    /**
     * Использовать локальные ресурсы? если нет,то CDN
     * @var boolean
     */
    public $local = false;

    /**
     * {@inheritDoc}
     * @see \yii\widgets\InputWidget::init()
     */
    public function init()
    {
        if ($this->model === null) {
            $this->initWidget();
        } else {
            $this->initInputWidget();
        }
    }

    private function initWidget()
    {
        Widget::init();
        if ($this->content === null) {
            $this->content = '';
        }
    }

    private function initInputWidget()
    {
        parent::init();
    }

    public function run()
    {
        $this->view->registerJs($this->buildScript(), $this->view::POS_READY);
        if ($this->local) {
            $this->registerAssets();
        } else {
            $view = $this->getView();
            $view->registerJsFile('http://js.nicedit.com/nicEdit-latest.js', ['position' => $view::POS_HEAD]);
        }

        if (!$this->model) {
            return Html::textarea("nicedit", $this->content, ["id" => $this->getInputId()]);
            Html::script($this->buildScript());
        } else {
            $this->options['id'] = $this->getInputId();
            echo html::activeTextarea($this->model, $this->attribute, $this->options);
        }
    }

    private function getInputId()
    {
        !$this->inputId ? $this->inputId = "nicedit" . uniqid() : '';
        return $this->inputId;
    }

    private function registerAssets()
    {
        $view = $this->getView();
        TestNsignWidgetAsset::register($view);
    }

    /**
     *
     * @return string
     */
    private function buildScript()
    {
        $this->local ? $this->editorOptions['iconsPath'] = \Yii::$app->assetManager->getBundle(TestNsignWidgetAsset::class)->baseUrl . '/nicEdit/nicEditorIcons.gif' : '';
        $json = Json::encode($this->editorOptions);
        return <<<marker
    bkLib.onDomLoaded(function() {
    new nicEditor({$json}).panelInstance('{$this->getInputId()}');
    });
marker;
    }
}