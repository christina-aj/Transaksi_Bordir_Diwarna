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

    public function rules()
    {
        return [
            [['riwayat_penjualan_id', 'barang_produksi_id', 'qty_penjualan'], 'integer'],
            [['bulan_periode', 'created_at', 'updated_at', 'nama'], 'safe'],
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
        $query = RiwayatPenjualan::find()
            ->joinWith(['barangProduksi']);

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

        return $dataProvider;
    }
}
