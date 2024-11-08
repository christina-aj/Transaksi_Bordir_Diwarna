<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Nota;

/**
 * Notasearch represents the model behind the search form of `app\models\Nota`.
 */
class Notasearch extends Nota
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nota_id', 'harga', 'qty', 'total_qty', 'total_harga'], 'integer'],
            [['nama_konsumen', 'tanggal', 'barang'], 'safe'],
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
        $query = Nota::find();

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
            'nota_id' => $this->nota_id,
            'tanggal' => $this->tanggal,
            'harga' => $this->harga,
            'qty' => $this->qty,
            'total_qty' => $this->total_qty,
            'total_harga' => $this->total_harga,
        ]);

        $query->andFilterWhere(['like', 'nama_konsumen', $this->nama_konsumen])
            ->andFilterWhere(['like', 'barang', $this->barang]);

        return $dataProvider;
    }
}
