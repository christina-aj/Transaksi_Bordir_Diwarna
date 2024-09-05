<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Stock;

/**
 * StockSearch represents the model behind the search form of `app\models\Stock`.
 */
class StockSearch extends Stock
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['stock_id', 'barang_id', 'user_id', 'is_ready', 'is_new'], 'integer'],
            [['tambah_stock', 'created_at', 'updated_at'], 'safe'],
            [['quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir'], 'number'],
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
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Stock::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'stock_id' => $this->stock_id,
            'tambah_stock' => $this->tambah_stock,
            'barang_id' => $this->barang_id,
            'quantity_awal' => $this->quantity_awal,
            'quantity_masuk' => $this->quantity_masuk,
            'quantity_keluar' => $this->quantity_keluar,
            'quantity_akhir' => $this->quantity_akhir,
            'user_id' => $this->user_id,
            'is_ready' => $this->is_ready,
            'is_new' => $this->is_new,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
