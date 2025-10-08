<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PermintaanPenjualan;

/**
 * PermintaanPenjualanSearch represents the model behind the search form of `app\models\PermintaanPenjualan`.
 */
class PermintaanPenjualanSearch extends PermintaanPenjualan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['permintaan_penjualan_id', 'total_item_permintaan', 'status_permintaan'], 'integer'],
            [['tanggal_permintaan', 'created_at', 'updated_at'], 'safe'],
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
        $query = PermintaanPenjualan::find();

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
            'permintaan_penjualan_id' => $this->permintaan_penjualan_id,
            'total_item_permintaan' => $this->total_item_permintaan,
            'tanggal_permintaan' => $this->tanggal_permintaan,
            'status_permintaan' => $this->status_permintaan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
