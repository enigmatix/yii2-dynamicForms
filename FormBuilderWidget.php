<?php
/**
 * Created by PhpStorm.
 * User: joels
 * Date: 31/3/17
 * Time: 8:54 PM
 */

namespace enigmatix\dynamicforms;


use yii\bootstrap\Modal;
use yii\bootstrap\Tabs;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use Yii;
use yii\helpers\Json;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use enigmatix\formbuilder\FormBuilder;

class FormBuilderWidget extends Modal
{
    var $formModel;

    var $configurations     = [];

    var $formBuilderClass   = '\\enigmatix\\formbuilder\\FormBuilder';

    var $controller         = 'dynamic-forms';

    var $saveButtonSelector;

    var $saveUrl;

    public function init()
    {

        parent::init(); // TODO: Change the autogenerated stub

        foreach ($this->getConfigurationData() as $label => $configuration) {
            $tabConfig[] = [
                'label'  => static::getLabel($label),
                'content' => call_user_func([$this->formBuilderClass, 'widget'],
                    $this->prepareOptions($configuration, $this->getFormId() . '-' . $label))
            ];
        }

        if(count($tabConfig) > 1){
            echo Tabs::widget([
                'items' => $tabConfig,
            ]);
        }else{
            $form = array_shift($tabConfig);
            echo $form['content'];
        }

        $this->registerCloseButton();

    }

    public function getFormId(){
        return $this->id . '-form';
    }

    protected function registerCloseButton(){
        $this->view->registerJs('
        $("'.$this->saveButtonSelector . '").click(function(){$("#'.$this->id . '").modal("toggle")});
        ');
    }

    protected function prepareOptions($formConfigModel, $id){

        return [
            'id'                    => $id,
            'model'                 => $this->formModel,
            'saveButtonSelector'    => $this->saveButtonSelector,
            'controller'            => $this->controller,
            'saveUrl'               => $this->getSaveUrl($formConfigModel, $this->controller),
            'pluginOptions' => [
                'formData'              => static::getConfigurationJSON($formConfigModel),
                'dataType'              => 'json',
                'showActionButtons'     => false,
                'disableFields' => [
                    'header',
                    'paragraph',
                    'number',
                    'autocomplete',
                    'hidden',
                    'file',
                    'button'
                ]
            ]
        ];
    }

    /**
     * @param DynamicForm $formConfigModel
     * @param $controller
     *
     * @return mixed|string
     */

    public function getSaveUrl($formConfigModel, $controller){

        if(is_callable($this->saveUrl)){
            return call_user_func($this->saveUrl, [$formConfigModel, $controller]);
        }

        if($this->saveUrl !== null){
            return $this->saveUrl;
        }

        if($formConfigModel->isNewRecord){
            $url[]      = '/'.$controller . '/create';
        } else{
            $url[]      = '/'.$controller . '/update';
            $url['id']  = $formConfigModel->uuid;
        }

        $url['form']      = StringHelper::basename($this->formModel->className());

        return Url::to($url);
    }

    protected function getConfigurationData()
    {

        if($this->configurations != null){
            return $this->configurations;
        }

        $this->configurations = DynamicForm::findModelConfig(
            StringHelper::basename($this->formModel->className()),
            $this->getCurrentUser());

        return $this->configurations;

    }

    protected function getCurrentUser()
    {
        return ArrayHelper::getValue(Yii::$app,'user.id');
    }

    protected function getConfigurationJSON($formDataModel)
    {

        if ($formDataModel instanceof DynamicForm){

            return $formDataModel->form_data;
        }
        return Json::decode($formDataModel);
    }

    public static function getlabel($label){
        switch($label){
            case '0':
                return 'Custom';
                break;
            case '1':
                return 'Default';
                break;
            default:
                if(is_numeric($label)){
                    return 'Custom ' . $label;
                }else {
                    return $label;
                }
        }
    }

}