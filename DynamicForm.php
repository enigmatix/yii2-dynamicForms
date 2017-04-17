<?php

namespace enigmatix\dynamicforms;

use enigmatix\uuid\UUIDBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\StringHelper;



/**
 * This is the model class for table "{{%dynamic_form}}".
 *
 * @property integer $id
 * @property string $uuid
 * @property string $form_object
 * @property string $form_data
 * @property integer $updated_at
 * @property integer $created_at
 * @property integer $owned_by
 * @property integer $created_by
 * @property integer $updated_by
 *
 * @property User $updatedBy
 * @property User $createdBy
 * @property User $ownedBy
 */
class DynamicForm extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dynamic_form}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_data'], 'string'],
            [['form_data'], 'validateFormDataDefault',  'skipOnEmpty' => false, 'skipOnError' => false],
            [['form_data'], 'validateJson'],
            [['owned_by'], 'integer'],
            [['uuid', 'form_object'], 'string', 'max' => 255],
            [['owned_by'], 'exist', 'skipOnError' => true, 'targetClass' => $this->getUserClass(), 'targetAttribute' => ['owned_by' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
            UUIDBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'uuid' => Yii::t('app', 'Uuid'),
            'form_object' => Yii::t('app', 'Form Object'),
            'form_data' => Yii::t('app', 'Form Data'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
            'owned_by' => Yii::t('app', 'Owned By'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
        ];
    }

    public function validateJson(){
        try{
            $this->form_data;
            $decoded = Json::decode($this->form_data);

        } catch (\Exception $e){
            $this->addError('form_data', 'Element does not contain valid JSON');
            return false;
        }

        foreach ($decoded as &$field){
            $field['name'] = str_replace('-','_',$field['name']);
        }

        $this->form_data = Json::encode($decoded);

        return true;
    }

    public function validateFormDataDefault($attribute, $params, $validator){

        if ($this->form_data === null){
            $this->form_data = json_encode([[
                'type' => 'textarea',
                'label' => 'Notes',
                'subtype' => 'text',
                'className' => 'form-control',
                'name' => 'notes',
            ]]);
        }
        return true;
    }

    /**
    * @inheritdoc
    */
    public function relations()
    {
        return [
            'CreatedBy' => 'one',
            'OwnedBy'   => 'one',
            'UpdatedBy' => 'one',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne($this->getUserClass(), ['id' => 'created_by']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwnedBy()
    {
        return $this->hasOne($this->getUserClass(), ['id' => 'owned_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne($this->getUserClass(), ['id' => 'created_by']);
    }

    protected function getUserClass()
    {
        return ArrayHelper::getValue(Yii::$app, 'user.identityClass');
    }

    public static function getModelConfig($modelName, $user = null)
    {

        $results    = [];

        if($user !== null){
            $custom = static::findOne(['form_object' => $modelName, 'owned_by' => $user]);
            if($custom !== null){
                $results[] = $custom;
            }
        }

        $default = static::findOne(['form_object' => $modelName, 'owned_by' => null]);
        if($default !== null){
            $results[]  = $default;
        }

        if(count($results) === 0){
            $newModel = new static;
            $newModel->validate(); //Generate defaults
            $results[] = $newModel;
        }

        return $results;
    }

    public static function getModelFields($model, $user = null){
        $configurations = static::getModelConfig(StringHelper::basename($model->className()), $user);
        $fields = [];
        foreach ($configurations as $configuration){
            foreach (Json::decode($configuration->form_data) as $field){
                $name = $field['name'];
                $fields[$name] = $name;
            }
        }

        return $fields;

    }

    public function userUpdateAuthorised($user){
        
    }
}
