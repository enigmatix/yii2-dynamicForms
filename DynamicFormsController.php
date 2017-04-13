<?php

namespace enigmatix\dynamicforms;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DynamicFormsController implements the CRUD actions for DynamicForm model.
 */
class DynamicFormsController extends Controller
{

    const FORM_ADMINISTRATOR = 'formAdmin';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Creates a new DynamicForm model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model      = new DynamicForm($this->getRequestBody());


        //Set ownership (ie, flag as custom) for forms supplied not from a person authorised to manage defaults.
        if(!Yii::$app->user->isGuest
            && isset(Yii::$app->authManager)
            && !Yii::$app->user->can(self::FORM_ADMINISTRATOR)){
            $model->owned_by = Yii::$app->user->id;
        }

        //Duplicate check
        $duplicate = DynamicForm::findOne(['form_object' => $model->form_object, 'owned_by' => $model->owned_by]);

        if($duplicate !== null){
            return Yii::$app->runAction('dynamic-forms/update', ['id' => $duplicate->uuid,'form' => $duplicate->form_object]);
        }

        if ($model->save()) {

        } else{
            throw new \Exception('Something went wrong');
        }
    }

    /**
     * Updates an existing DynamicForm model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(['DynamicForm' => $this->getRequestBody()]) && $model->save()) {

        }
    }

    /**
     * Deletes an existing DynamicForm model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DynamicForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DynamicForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DynamicForm::findOne(['uuid' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getRequestBody(){
        return [
            'form_data'     => Yii::$app->request->rawBody,
            'form_object'   => Yii::$app->request->get('form')
        ];
    }
}
