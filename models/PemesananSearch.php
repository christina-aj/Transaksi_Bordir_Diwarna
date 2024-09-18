<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pemesanan;

/**
 * PemesananSearch represents the model behind the search form of `app\models\Pemesanan`.
 */
class PemesananSearch extends Pemesanan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pemesanan_id', 'barang_id', 'user_id'], 'integer'],
            [['tanggal', 'created_at', 'updated_at'], 'safe'],
            [['qty'], 'number'],
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
        $query = Pemesanan::find();

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
            'pemesanan_id' => $this->pemesanan_id,
            'barang_id' => $this->barang_id,
            'user_id' => $this->user_id,
            'tanggal' => $this->tanggal,
            'qty' => $this->qty,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
