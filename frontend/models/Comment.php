<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\User;

/**
 * This is the model class for table "comment".
 *
 * @property string $id
 * @property string $text
 * @property string $created_at
 * @property string $user_id
 * @property string $user_comment_id
 *
 * @property User $user
 * @property User $userComment
 */
class Comment extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
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
    public function rules()
    {
        return [
            [['text', 'user_id', 'user_comment_id'], 'required'],
            [['text'], 'string'],
            [['created_at'], 'safe'],
            [['user_id', 'user_comment_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'text' => Yii::t('app', 'Text'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_comment_id' => Yii::t('app', 'User Comment ID'),
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
    public function getUserComment()
    {
        return $this->hasOne(User::className(), ['id' => 'user_comment_id']);
    }
}
