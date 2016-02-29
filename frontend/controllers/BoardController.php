<?php

namespace frontend\controllers;

use frontend\components\MyHelpers;
use Yii;
use app\models\Callboard;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use yii\filters\AccessControl;

/**
 * BoardController implements the CRUD actions for Callboard model.
 */
class BoardController extends Controller
{
    public function behaviors()
    {
        return [
          'access' => [
            'class' => AccessControl::className(),
            'only' => ['create','update','delete'],
            'rules' => [
              [
                'allow' => true,
                'roles' => ['@'],
              ],
            ],
          ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Callboard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Callboard::find(),
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]],
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Callboard model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Callboard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Callboard();

        if (Yii::$app->request->isPost) {

            if($model->load(Yii::$app->request->post())) {
                $model->user_id = Yii::$app->user->getId();

                $model->file = UploadedFile::getInstance($model, 'file');

                if ($model->file && $model->validate(['file'])) {
                    MyHelpers::createDirectory($model->image_abs_path);
                    $file_name=$model->file->baseName .'_'.time(). '.' . $model->file->extension;
                    if($model->file->saveAs(Yii::getAlias($model->image_abs_path) . $file_name)) {
                        $model->image = $file_name;
                        $model->file=null;
                    }
                }

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', ['model' => $model,]);

    }

    /**
     * Updates an existing Callboard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if($model->user_id!==Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('app','FLASH_BOARD_ACCESS_ERROR'));
            return $this->goHome();
        }
        $image = $model->image;

        if (Yii::$app->request->isPost) {

            if ($model->load(Yii::$app->request->post())) {

                $model->file = UploadedFile::getInstance($model, 'file');
                if(!$model->file && $image){
                    $model->image=$image;
                }

                if($model->file && $model->validate(['file'])) {
                    MyHelpers::createDirectory($model->image_abs_path);
                    $file_name=$model->file->baseName .'_'.time(). '.' . $model->file->extension;
                    if ($model->file->saveAs(Yii::getAlias($model->image_abs_path).$file_name)) {

                        if($image && file_exists(Yii::getAlias($model->image_abs_path.$image)))
                            unlink(Yii::getAlias($model->image_abs_path.$image));

                        $model->image = $file_name;
                        $model->file = null;
                    }
                }



                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }


        return $this->render('update', ['model' => $model,]);

    }

    /**
     * Deletes an existing Callboard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $image=$model->image;
        if($model->user_id!==Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('app','FLASH_BOARD_ACCESS_ERROR'));
            return $this->goHome();
        }
        if($model->delete()){
            if($image && file_exists(Yii::getAlias($model->image_abs_path.$image)))
                unlink(Yii::getAlias($model->image_abs_path.$image));
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Callboard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Callboard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Callboard::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
