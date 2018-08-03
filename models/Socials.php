<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "socials".
 *
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property int $uid
 */
class Socials extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'socials';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'uid'], 'required'],
            [['user_id', 'uid'], 'integer'],
            [['type'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'type' => 'Type',
            'uid' => 'Uid',
        ];
    }


    public function createUserInfo($userId, $type, $uid)
    {
        $this->user_id = $userId;
        $this->type = $type;
        $this->uid = $uid;

        return $this->save();
    }
}
