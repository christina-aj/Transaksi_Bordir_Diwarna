<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Nota extends ActiveRecord
{
    public $barangList = [];
    public $hargaList = [];
    public $qtyList = [];

    public static function tableName()
    {
        return 'nota';
    }

    public function rules()
    {
        return [
            [['nama_konsumen', 'tanggal'], 'required'],
            [['tanggal'], 'safe'],
            [['total_harga'], 'integer'],
            [['nama_konsumen'], 'string', 'max' => 200],
            [['barang','qty', 'harga'], 'string'],
            [['barangList', 'hargaList', 'qtyList'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nota_id' => 'Nota ID',
            'nama_konsumen' => 'Nama Konsumen',
            'tanggal' => 'Tanggal',
            'barang' => 'Barang',
            'harga' => 'Harga',
            'qty' => 'Quantity',
            'total_harga' => 'Total Harga',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->barangList = $this->barang ? explode(',', $this->barang) : [];
        $this->hargaList = $this->harga ? explode(',', $this->harga) : [];
        $this->qtyList = $this->qty ? explode(',', $this->qty) : [];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->barang = $this->barangList ? implode(',', $this->barangList) : '';
            $this->harga = $this->hargaList ? implode(',', $this->hargaList) : '';
            $this->qty = $this->qtyList ? implode(',', $this->qtyList) : '';

            // Hitung total harga
            $this->total_harga = array_sum(array_map(function($price, $qty) {
                return intval($price) * intval($qty);
            }, $this->hargaList ?: [], $this->qtyList ?: []));
            $this->total_qty = array_sum($this->qtyList ?: []);
            
            return true;
        }
        return false;
    }

}

