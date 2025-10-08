<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetailPermintaan;

/**
 * DetailPermintaanSearch represents the model behind the search form of `app\models\DetailPermintaan`.
 */
class DetailPermintaanSearch extends DetailPermintaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['detail_permintaan_id', 'permintaan_penjualan_id', 'barang_produksi_id', 'qty_permintaan'], 'integer'],
            [['catatan', 'created_at', 'updated_at'], 'safe'],
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
        $query = DetailPermintaan::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'detail_permintaan_id' => $this->detail_permintaan_id,
            'permintaan_penjualan_id' => $this->permintaan_penjualan_id,
            'barang_produksi_id' => $this->barang_produksi_id,
            'qty_permintaan' => $this->qty_permintaan,
            'catatan' => $this->catatan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
