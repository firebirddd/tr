<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $isAdmin
 * @property string $photo
 *
 * @property Comment[] $comments
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['isAdmin'], 'integer'],
            [['name', 'email', 'password', 'photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'isAdmin' => 'Is Admin',
            'photo' => 'Photo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }


    public static function findIdentity($id)
    {
        return User::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }


    public function getId()
    {
        return $this->id;
    }


    public function getAuthKey()
    {
        // TODO: Implement getAuthKey() method.
    }


    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }


    public function validatePassword($password)
    {
        if($this->password == $password)
            return true;
        return false;
    }


    public static function findByEmail($email)
    {
        return User::find()->where(['email' => $email])->one();
    }


    public function create()
    {
        return $this->save();
    }


    public function loginFromVk()
    {
        $uid = Yii::$app->request->get('uid');

        $socialInfo = Socials::find()->where(['uid' => $uid, 'type' => 'vk'])->one();
        if($socialInfo)
        {
            // значит такой пользователь уже существует
            $userId = $socialInfo->user_id;
        }
        else
        {
            $name = Yii::$app->request->get('first_name') . ' ' . Yii::$app->request->get('last_name');
            $photo = Yii::$app->request->get('photo');

            $userId =  $this->createFromVk($name, $uid, $photo);
        }


        $user = User::findOne($userId);
        return Yii::$app->user->login($user);
    }



    public function createFromVk($name, $uid, $photo)
    {
        $this->name = $name;
        $this->photo = $photo;

        if(!$this->create())
            return false;

        $userId = $this->id;

        $social = new Socials();
        if(!$social->createUserInfo($userId, 'vk', $uid))
            return false;

        return $userId;
    }
}
