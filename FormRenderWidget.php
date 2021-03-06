<?php
/**
 * Created by PhpStorm.
 * User: joels
 * Date: 31/3/17
 * Time: 8:54 PM
 */

namespace enigmatix\dynamicforms;


use yii\base\Widget;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use enigmatix\formbuilder\FormBuilder;

class FormRenderWidget extends Widget
{

    public $model;
    public $view;
    public $form;
    public $formData;
    public $formToggle = false;
    public $title = 'Custom Fields';
    public $options;

    public function run(){

        $toggle = '';
        if($this->formToggle != null){
            $toggle = Html::button($this->formToggle['label'], $this->formToggle);
        }
        $output = Html::tag('h2', $this->title .' ' . $toggle);
        $output .= Html::beginTag('div', ['class' => 'row']);

        foreach ($this->getConfigArray() as $field){
            $output     .= $this->renderField($field);
        }

        $output .= Html::endTag('div');
        return Html::tag('div', $output, ['class' => $this->getClass()]);
    }

    protected function getClass()
    {
        return ArrayHelper::getValue($this,'options.class', 'col-sm-12 well well-lg');
    }

    protected function getConfigArray(){
        if(!is_array($this->formData)){
            $configArray = Json::decode($this->formData);
        } else {
            $configArray = $this->formData;
        }
        return $configArray;
    }

    protected function renderField($fieldConfig){

        $id           = Html::getInputId($this->model, $fieldConfig['name']);
        $activeField  = $this->form->field($this->model,
            $fieldConfig['name']);

        $type         = static::getFunctionType($fieldConfig['type']);
        $functionName = static::getFunctionName($type);

        if(method_exists($this, $functionName)){
            $output = call_user_func([$this, $functionName], $id, $activeField, $fieldConfig);
        } else {
            $output =  Html::tag('div',
                $activeField->$type()->label($fieldConfig['label']),
                ['class' => 'col-md-6']);

        }

        return $output . PHP_EOL;
    }

    protected static function getFunctionType($type)
    {

        switch ($type) {
            case 'text':
                return 'textInput';
                break;
            case 'textarea':
                return 'textArea';
                break;
            case 'checkbox':
                return 'checkbox';
                break;
            case 'checkbox-group':
                return 'checkboxList';
                break;
            case 'file':
                return 'fileInput';
                break;
            case 'radio-group':
                return 'radioList';
                break;
            case 'select':
                return 'dropdownList';
                break;
            case 'date':
                return 'date';
                break;
            case 'header':
                return 'header';
                break;
            }
    }

    protected function renderCheckboxListField($id, $activeField, $fieldConfig){
        return  Html::tag('div',
            $activeField
                ->checkboxList(FormBuilder::getValueList($fieldConfig['values'])),
            ['class' => 'col-md-6']);
    }

    protected function renderRadioListField($id, $activeField, $fieldConfig)
    {
        return Html::tag('div',
            $activeField
                ->radioList(FormBuilder::getValueList($fieldConfig['values'])),
            ['class' => 'col-md-6']);
    }

    protected function renderDropdownListField($id, $activeField, $fieldConfig)
    {
        return Html::tag('div',
            $activeField
                ->dropdownList(FormBuilder::getValueList($fieldConfig['values'])),
            ['class' => 'col-md-6']);
    }

    protected function renderDateField($id, $activeField, $fieldConfig)
    {
        return Html::tag('div',
            $activeField->textInput(),
            ['class' => 'col-md-6']);
    }

    protected function renderHeaderField($id, $activeField, $fieldConfig)
    {
        return Html::tag('h3', $fieldConfig['text']);
    }

    protected static function getFunctionName($function){
        return 'render' . ucfirst($function) . 'Field';
    }

}