<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;
use app\models\Comment;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $password write-only password
 *
 * @property string $photo
 * @property integer $rating
 * @property string $rating_votes_col
 *
 * @property Callboard[] $callboards
 * @property Comment[] $comments
 * @property Comment[] $comments0
 * @property RatingVoteUser[] $ratingVoteUsers
 * @property RatingVoteUser[] $ratingVoteUsers0
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $file;
    public $image_abs_path='@webroot/uploads/user/';
    public $image_path='/uploads/user/';

    public $newPassword;
    public $newPasswordRepeat;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
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
          ['status', 'default', 'value' => self::STATUS_ACTIVE],
          ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

          [['username', 'email'], 'required'],
          [['id', 'status', 'rating_votes_col'], 'integer'],
          [['created_at', 'updated_at'], 'safe'],
          [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
          [['auth_key'], 'string', 'max' => 32],
          [['photo'], 'string', 'max' => 1024],
          [['username'], 'unique'],
          [['email'], 'unique'],
          [['password_reset_token'], 'unique'],
          [['file'], 'image', 'extensions' => 'png, jpg, jpeg, gif', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
          'id'                      => Yii::t('app', 'ID'),
          'username'                => Yii::t('app', 'USER_USERNAME'),
          'auth_key'                => Yii::t('app', 'Auth Key'),
          'password_hash'           => Yii::t('app', 'Password Hash'),
          'password_reset_token'    => Yii::t('app', 'Password Reset Token'),
          'email'                   => Yii::t('app', 'USER_EMAIL'),
          'status'                  => Yii::t('app', 'Status'),
          'created_at'              => Yii::t('app', 'USER_CREATED_AT'),
          'updated_at'              => Yii::t('app', 'Updated At'),
          'photo'                   => Yii::t('app', 'USER_PHOTO'),
          'rating'                  => Yii::t('app', 'USER_RATING'),
          'rating_votes_col'        => Yii::t('app', 'Rating Votes Col'),
          'file'                    => Yii::t('app', 'USER_PHOTO'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCallboards()
    {
        return $this->hasMany(Callboard::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments0()
    {
        return $this->hasMany(Comment::className(), ['user_comment_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatingVoteUsers()
    {
        return $this->hasMany(RatingVoteUser::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRatingVoteUsers0()
    {
        return $this->hasMany(RatingVoteUser::className(), ['user_vote_id' => 'id']);
    }

    public function getImage()
    {
        if($this->photo)
            return $this->image_path.$this->photo;
        else
            return '/uploads/no-avatar.jpg';
    }
}
