<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StockRop;

/**
 * StockRopSearch represents the model behind the search form of `app\models\StockRop`.
 */
class StockRopSearch extends StockRop
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_rop_id', 'barang_id', 'stock_barang', 'safety_stock', 'jumlah_eoq', 'jumlah_rop', 'pesan_barang'], 'integer'],
            [['periode'], 'safe'],
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
        $query = StockRop::find();

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
            'stock_rop_id' => $this->stock_rop_id,
            'barang_id' => $this->barang_id,
            'stock_barang' => $this->stock_barang,
            'safety_stock' => $this->safety_stock,
            'jumlah_eoq' => $this->jumlah_eoq,
            'jumlah_rop' => $this->jumlah_rop,
            'pesan_barang' => $this->pesan_barang,
        ]);

        $query->andFilterWhere(['like', 'periode', $this->periode]);

        return $dataProvider;
    }
}
