<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use common\models\User;

/**
 * This is the model class for table "callboard".
 *
 * @property string $id
 * @property string $title
 * @property string $text
 * @property string $image
 * @property string $user_id
 * @property integer $active
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Callboard extends ActiveRecord
{
    public $file;
    public $image_abs_path='@webroot/uploads/board/';
    public $image_path='/uploads/board/';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'callboard';
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
              ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
              ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
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
            [['title', 'text','image', 'user_id'], 'required'],
            [['text','image'], 'string'],
            [['user_id', 'active'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 200],
            [['file'], 'image', 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'text' => Yii::t('app', 'Text'),
            'image' => Yii::t('app', 'Image'),
            'user_id' => Yii::t('app', 'User ID'),
            'active' => Yii::t('app', 'Active'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getImage()
    {
        if($this->image)
            return $this->image_path.$this->image;
    }
}
