<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PesanDetail;

/**
 * PesanDetailSearch represents the model behind the search form of `app\models\PesanDetail`.
 */
class PesanDetailSearch extends PesanDetail
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pesandetail_id', 'pemesanan_id', 'barang_id', 'is_correct', 'langsung_pakai'], 'integer'],
            [['qty', 'qty_terima'], 'number'],
            [['catatan', 'created_at', 'update_at'], 'safe'],
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
        $query = PesanDetail::find();

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
            'pesandetail_id' => $this->pesandetail_id,
            'pemesanan_id' => $this->pemesanan_id,
            'barang_id' => $this->barang_id,
            'qty' => $this->qty,
            'qty_terima' => $this->qty_terima,
            'langsung_pakai' => $this->langsung_pakai,
            'is_correct' => $this->is_correct,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan]);

        return $dataProvider;
    }
}
