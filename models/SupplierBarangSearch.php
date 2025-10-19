<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SupplierBarang;

/**
 * SupplierBarangSearch represents the model behind the search form of `app\models\SupplierBarang`.
 */
class SupplierBarangSearch extends SupplierBarang
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['supplier_barang_id', 'barang_id', 'total_supplier_barang'], 'integer'],
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
        $query = SupplierBarang::find();

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
            'supplier_barang_id' => $this->supplier_barang_id,
            'barang_id' => $this->barang_id,
            'total_supplier_barang' => $this->total_supplier_barang,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
