<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Penggunaan;

/**
 * PenggunaanSearch represents the model behind the search form of `app\models\Penggunaan`.
 */
class PenggunaanSearch extends Penggunaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['penggunaan_id', 'barang_id', 'jumlah_digunakan'], 'integer'],
            [['tanggal_digunakan', 'catatan'], 'safe'],
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
        $query = Penggunaan::find();

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
            'penggunaan_id' => $this->penggunaan_id,
            'barang_id' => $this->barang_id,
            'jumlah_digunakan' => $this->jumlah_digunakan,
            'tanggal_digunakan' => $this->tanggal_digunakan,
        ]);

        return $dataProvider;
    }
}
