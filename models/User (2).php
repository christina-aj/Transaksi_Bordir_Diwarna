<?php

namespace app\models;

use Yii;

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
class User extends \yii\db\ActiveRecord
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
            [['id_role', 'nama_pengguna', 'email', 'kata_sandi'], 'required'],
            [['id_role'], 'integer'],
            [['dibuat_pada', 'diperbarui_pada'], 'safe'],
            [['nama_pengguna', 'email', 'kata_sandi'], 'string', 'max' => 200],
            [['id_role'], 'unique'],
            [['nama_pengguna'], 'unique'],
            [['id_role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['id_role' => 'id_role']],
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
}
