<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PembelianDetail;

/**
 * PembelianDetailSearch represents the model behind the search form of `app\models\PembelianDetail`.
 */
class PembelianDetailSearch extends PembelianDetail
{
    /**
     * {@inheritdoc}
     */

    public $nama_supplier;
    public function rules()
    {
        return [
            [['belidetail_id', 'pembelian_id', 'pesandetail_id', 'is_correct'], 'integer'],
            [['cek_barang', 'total_biaya'], 'number'],
            [['catatan', 'created_at', 'updated_at', 'nama_supplier'], 'safe'],
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
        $query = PembelianDetail::find()->joinWith(['supplier']);

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

        $query->andFilterWhere(['like', 'supplier.nama', $this->nama_supplier]);
        // grid filtering conditions
        $query->andFilterWhere([
            'belidetail_id' => $this->belidetail_id,
            'pembelian_id' => $this->pembelian_id,
            'pesandetail_id' => $this->pesandetail_id,
            'cek_barang' => $this->cek_barang,
            'total_biaya' => $this->total_biaya,
            'is_correct' => $this->is_correct,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan]);

        return $dataProvider;
    }
}
