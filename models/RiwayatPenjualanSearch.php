<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\RiwayatPenjualan;

/**
 * RiwayatPenjualanSearch represents the model behind the search form of `app\models\RiwayatPenjualan`.
 */
class RiwayatPenjualanSearch extends RiwayatPenjualan
{
    /**
     * {@inheritdoc}
     */

    public $nama;
    public $nama_barang;
    
    public $filter_tahun;
    public $filter_bulan;

    public function rules()
    {
        return [
            [['riwayat_penjualan_id', 'barang_produksi_id', 'qty_penjualan'], 'integer'],
            [['bulan_periode', 'created_at', 'updated_at', 'nama', 'nama_barang', 'filter_tahun', 'filter_bulan'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = RiwayatPenjualan::find();
        // join relasi supaya bisa cari berdasarkan nama barang
        $query->joinWith(['barangProduksi', 'barangCustomPelanggan']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        // enable sorting di kolom "nama_barang"
        $dataProvider->sort->attributes['nama_barang'] = [
            'asc' => ['barang_produksi.nama' => SORT_ASC, 'barang_custom_pelanggan.nama_barang_custom' => SORT_ASC],
            'desc' => ['barang_produksi.nama' => SORT_DESC, 'barang_custom_pelanggan.nama_barang_custom' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // filter lain (kalau ada)
        $query->andFilterWhere([
            'riwayat_penjualan_id' => $this->riwayat_penjualan_id,
            'qty_penjualan' => $this->qty_penjualan,
            // 'bulan_periode' => $this->bulan_periode,
        ]);

        $query->andFilterWhere(['or',
            ['like', 'barangProduksi.nama', $this->nama_barang],
            ['like', 'barang_custom_pelanggan.nama_barang_custom', $this->nama_barang]
        ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'bulan_periode' => SORT_DESC, // ← Sort default by bulan_periode descending
                ],
                'attributes' => [
                    'riwayat_penjualan_id',
                    'qty_penjualan',
                    'bulan_periode', // ← Enable sort untuk bulan_periode
                    'nama' => [
                        'asc' => ['barang.nama' => SORT_ASC],
                        'desc' => ['barang.nama' => SORT_DESC],
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'riwayat_penjualan_id' => $this->riwayat_penjualan_id,
            'barang_produksi_id' => $this->barang_produksi_id,
            'qty_penjualan' => $this->qty_penjualan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'bulan_periode', $this->bulan_periode]);

        
        // ===============================
        // FILTER TAHUN & BULAN (YYYYMM)
        // ===============================

        // Filter Tahun
        if (!empty($this->filter_tahun)) {
            $query->andWhere('LEFT(bulan_periode,4) = :tahun', [':tahun' => $this->filter_tahun]);
        }

        // Filter Bulan
        if (!empty($this->filter_bulan)) {
            $bulan = str_pad($this->filter_bulan, 2, '0', STR_PAD_LEFT);
            $query->andWhere('RIGHT(bulan_periode,2) = :bulan', [':bulan' => $bulan]);
        }

        return $dataProvider;
    }
}
