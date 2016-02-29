<?php

namespace frontend\controllers;

use app\models\Comment;
use app\models\RatingVoteUser;
use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use frontend\components\MyHelpers;
use frontend\models\PasswordChangeForm;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * ProfileController implements the CRUD actions for User model.
 */
class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
          'access' => [
            'class' => AccessControl::className(),
            'only' => ['update','password-change'],
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
                    'setrating' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @param integer $id
     * @return mixed
     */
    public function actionIndex($id=0)
    {
        $comment = new Comment();
        $ratingModel= new RatingVoteUser();

        if (Yii::$app->request->isPost && !Yii::$app->user->isGuest) {
            if ($comment->load(Yii::$app->request->post())) {
                $comment->user_id=$id;
                $comment->user_comment_id=Yii::$app->user->getId();
                if($comment->save())
                    return $this->redirect(['index','id'=>$id]);
            }
        }

            if ($id <= 0) {
                $id = Yii::$app->user->getId();
            }
            $model = $this->findModel($id);



            $dataProvider = new ActiveDataProvider([
              'query' => Comment::find()->where(['user_id' => $id]),
              'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
              'pagination' => [
                'pageSize' => 10,
              ],
            ]);

            return $this->render('index', [
              'model' => $model,
              'dataProviderComment' => $dataProvider,
              'comment' => $comment
            ]);

    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('index', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id=0)
    {
        if($id<=0)
            $id=Yii::$app->user->getId();
        $model = $this->findModel($id);

        if($model->id!==Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', Yii::t('app','FLASH_PROFILE_ACCESS_ERROR'));
            return $this->goHome();
        }
        $image = $model->photo;

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {

                $model->file = UploadedFile::getInstance($model, 'file');

                if (!$model->file && $image) {
                    $model->photo = $image;
                }

                if ($model->file && $model->validate(['file'])) {
                    MyHelpers::createDirectory($model->image_abs_path);
                    $file_name = $model->file->baseName . '_' . time() . '.' . $model->file->extension;

                    if ($model->file->saveAs(Yii::getAlias($model->image_abs_path) . $file_name)) {

                        if ($image && file_exists(Yii::getAlias($model->image_abs_path . $image))) {
                            unlink(Yii::getAlias($model->image_abs_path . $image));
                        }

                        $model->photo = $file_name;
                        $model->file = null;
                    }
                }


                if ($model->save()) {
                    return $this->redirect(['profile/index']);
                }
            }
        }

        return $this->render('update', ['model' => $model,]);

    }

    public function actionPasswordChange()
    {
        $user = $this->findModel(Yii::$app->user->identity->getId());

        $model = new PasswordChangeForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'FLASH_PASSWORD_CHANGE_SUCCESS'));
            return $this->redirect(['index']);
        } else {
            return $this->render('passwordChange', [
              'model' => $model,
            ]);
        }
    }

    public  function actionSetrating()
    {
        $response = [];
        if (Yii::$app->request->isAjax) {
            $modelRating = new RatingVoteUser();

            $data=Yii::$app->request->post();

            if(isset($this->findModel($data['id'])->voteUser->num) && $this->findModel($data['id'])->voteUser->num>0) {
                Yii::$app->session->setFlash('error', Yii::t('app','FLASH_PROFILE_ACCESS_RATING_ERROR'));
                return $this->redirect(['index','id'=>$data['id']]);
            }

            if ($modelRating->validate($data)) {
                $modelRating->num=$data['value'];
                $modelRating->user_id=$data['id'];
                $modelRating->user_vote_id=Yii::$app->user->getId();
                $response=$modelRating->toArray();
                if ($modelRating->save()) {
                    $modelUser = $this->findModel($modelRating->user_id);
                    $modelUser->rating+=$modelRating->num;
                    $modelUser->rating_votes_col+=1;
                    $modelUser->save();
                    Yii::$app->session->setFlash('success', Yii::t('app','FLASH_PROFILE_RATING_SUCCESS'));
                    return $this->redirect(['index','id'=>$modelRating->user_id]);
                }
                else {
                    $response['error'] = $modelRating->getErrors();
                }
            }
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
              'data'=>$response,
            ];
        }
    }


    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
