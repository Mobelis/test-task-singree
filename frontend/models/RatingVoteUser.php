<?php

namespace app\models;

use Yii;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "rating_vote_user".
 *
 * @property string $id
 * @property string $user_id
 * @property string $user_vote_id
 * @property string $created_at
 * @property integer $num
 *
 * @property User $user
 * @property User $userVote
 */
class RatingVoteUser extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rating_vote_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_vote_id', 'num'], 'required'],
            [['user_id', 'user_vote_id', 'num'], 'integer'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
          'timestamp' => [
            'class' => TimestampBehavior::className(),
            'attributes' => [
              ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
            ],
            'value' => new Expression('NOW()'),
          ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_vote_id' => Yii::t('app', 'User Vote ID'),
            'created_at' => Yii::t('app', 'Created At'),
            'num' => Yii::t('app', 'Num'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserVote()
    {
        return $this->hasOne(User::className(), ['id' => 'user_vote_id']);
    }
}
