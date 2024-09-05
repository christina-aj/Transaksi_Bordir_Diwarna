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
 * @property string $email
 * @property string $kata_sandi
 * @property string $dibuat_pada
 * @property string|null $diperbarui_pada
 *
 * @property Pembelian $pembelian
 * @property Role $role
 * @property Shift $shift
 * @property Stock $stock
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
                'value' => new Expression('NOW()'), 
            ],
        ];
    }
    public function rules()
    {
        return [
            [['id_role', 'nama_pengguna', 'email', 'kata_sandi'], 'required'],
            [['id_role'], 'integer'],
            [['dibuat_pada', 'diperbarui_pada'], 'safe'],
            [['nama_pengguna', 'email', 'kata_sandi'], 'string', 'max' => 200],
            [['nama_pengguna'], 'unique'],
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
            'email' => 'Email',
            'kata_sandi' => 'Kata Sandi',
            'dibuat_pada' => 'Dibuat Pada',
            'diperbarui_pada' => 'Diperbarui Pada',
        ];
    }

    /**
     * Gets query for [[Pembelian]].
     *
     * @return \yii\db\ActiveQuery
     */

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return true;
    }

    public function getId()
    {
        return $this->user_id;
    }

    public function getPembelian()
    {
        return $this->hasOne(Pembelian::class, ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[Role]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::class, ['id_role' => 'id_role']);
    }

    public function getroleid()
    {
        return $this->id_role;
    }

    public function getRoleName()
    {
        return $this->role ? $this->role->nama : 'Guest';
    }


    /**
     * Gets query for [[Shift]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShift()
    {
        return $this->hasOne(Shift::class, ['user_id' => 'user_id']);
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock()
    {
        return $this->hasOne(Stock::class, ['user_id' => 'user_id']);
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
