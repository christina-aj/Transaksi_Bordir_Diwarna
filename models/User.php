<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property int $id_role
 * @property string $nama_pengguna
 * @property string $kata_sandi
 * @property string $email
 * @property string|null $authKey
 * @property string $dibuat_pada
 * @property string $diperbarui_pada
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface

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

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['dibuat_pada', 'diperbarui_pada'],
                    \yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['diperbarui_pada'],
                ],
                'value' => new Expression('NOW()'), // or date('Y-m-d H:i:s')
            ],
        ];
    }
    public function rules()
    {
        return [
            [['id_role', 'nama_pengguna', 'kata_sandi', 'email'], 'required'],
            [['id_role'], 'integer'],
            [['dibuat_pada', 'diperbarui_pada'], 'safe'],
            [['nama_pengguna'], 'string', 'max' => 50],
            [['kata_sandi', 'authKey'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'id_role' => 'Id Role',
            'nama_pengguna' => 'Nama Pengguna',
            'kata_sandi' => 'Kata Sandi',
            'email' => 'Email',
            'authKey' => 'Auth Key',
            'dibuat_pada' => 'Dibuat Pada',
            'diperbarui_pada' => 'Diperbarui Pada',
        ];
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException();
    }

    public function getId()
    {
        return $this->user_id;
    }


    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id_role' => 'id_role']);
    }

    public function getroleid()
    {
        return $this->id_role;
    }

    public function getRoleName()
    {
        return $this->role ? $this->role->nama : 'Guest';
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    public static function findByUsername($nama_pengguna)
    {
        return self::findOne(['nama_pengguna' => $nama_pengguna]);
    }

    public function validatePassword($kata_sandi)
    {
        return $this->kata_sandi === $kata_sandi;
    }
}
