<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pembelian;

/**
 * PembelianSearch represents the model behind the search form of `app\models\Pembelian`.
 */
class PembelianSearch extends Pembelian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pembelian_id', 'user_id', 'supplier_id', 'langsung_pakai'], 'integer'],
            [['tanggal', 'total_biaya', 'kode_struk'], 'safe'],
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
        $query = Pembelian::find();

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
            'pembelian_id' => $this->pembelian_id,
            'user_id' => $this->user_id,
            'tanggal' => $this->tanggal,
            'supplier_id' => $this->supplier_id,
            'langsung_pakai' => $this->langsung_pakai,
        ]);

        $query->andFilterWhere(['like', 'total_biaya', $this->total_biaya])
            ->andFilterWhere(['like', 'kode_struk', $this->kode_struk]);

        return $dataProvider;
    }
}
