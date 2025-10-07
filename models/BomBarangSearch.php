<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BomBarang;

/**
 * BomBarangSearch represents the model behind the search form of `app\models\BomBarang`.
 */
class BomBarangSearch extends BomBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['BOM_barang_id', 'barang_produksi_id', 'total_bahan_baku'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
        $query = BomBarang::find();

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
            'BOM_barang_id' => $this->BOM_barang_id,
            'barang_produksi_id' => $this->barang_produksi_id,
            'total_bahan_baku' => $this->total_bahan_baku,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
