<?php
namespace app\models;

use yii\base\Model;

class MoveAreaForm extends Model
{
    public $barang_id;
    public $area_asal;
    public $area_tujuan;
    public $jumlah;
    public $catatan;

    public function rules()
    {
        return [
            [['barang_id', 'area_asal', 'area_tujuan', 'jumlah'], 'required'],
            [['barang_id', 'area_asal', 'area_tujuan', 'jumlah'], 'integer'],
            [['area_asal', 'area_tujuan'], 'in', 'range' => [1, 2, 3, 4]],
            ['area_tujuan', 'compare', 'compareAttribute' => 'area_asal', 'operator' => '!=', 'message' => 'Area tujuan tidak boleh sama dengan area asal'],
            ['jumlah', 'integer', 'min' => 1],
            ['jumlah', 'validateStockAvailable'],
            [['catatan'], 'string', 'max' => 255],
            [['barang_id'], 'exist', 'targetClass' => '\app\models\Barang', 'targetAttribute' => 'barang_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'barang_id' => 'Barang',
            'area_asal' => 'Area Asal',
            'area_tujuan' => 'Area Tujuan', 
            'jumlah' => 'Jumlah',
            'catatan' => 'Catatan',
        ];
    }

    /**
     * Validasi stock yang tersedia di area asal
     */
    public function validateStockAvailable($attribute, $params)
    {
        if (!empty($this->barang_id) && !empty($this->area_asal)) {
            $availableStock = \app\models\Gudang::getCurrentStockByArea($this->barang_id, $this->area_asal);
            
            if ($this->jumlah > $availableStock) {
                $this->addError($attribute, "Jumlah melebihi stock yang tersedia. Stock tersedia: {$availableStock}");
            }
        }
    }

    /**
     * Get available stock untuk area asal yang dipilih
     */
    public function getAvailableStock()
    {
        if (!empty($this->barang_id) && !empty($this->area_asal)) {
            return \app\models\Gudang::getCurrentStockByArea($this->barang_id, $this->area_asal);
        }
        return 0;
    }

    /**
     * Get nama barang
     */
    public function getNamaBarang()
    {
        if (!empty($this->barang_id)) {
            $barang = \app\models\Barang::findOne($this->barang_id);
            return $barang ? $barang->nama_barang : '';
        }
        return '';
    }
}