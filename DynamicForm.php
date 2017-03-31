<?php

namespace enigmatix\dynamicforms;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

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
            [['owned_by'], 'integer'],
            [['uuid', 'form_object'], 'string', 'max' => 255],
            [['owned_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owned_by' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
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

    protected function getUserClass(){
        return Yii::$app->user->identity->identityClass;
    }
}
