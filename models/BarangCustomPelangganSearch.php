<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\BarangCustomPelanggan;

/**
 * BarangCustomPelangganSearch represents the model behind the search form of `app\models\BarangCustomPelanggan`.
 */
class BarangCustomPelangganSearch extends BarangCustomPelanggan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_custom_pelanggan_id', 'pelanggan_id', 'created_at', 'updated_at'], 'integer'],
            [['kode_barang_custom', 'nama_barang_custom'], 'safe'],
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
        $query = BarangCustomPelanggan::find();

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
            'barang_custom_pelanggan_id' => $this->barang_custom_pelanggan_id,
            'pelanggan_id' => $this->pelanggan_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'kode_barang_custom', $this->kode_barang_custom])
            ->andFilterWhere(['like', 'nama_barang_custom', $this->nama_barang_custom]);

        return $dataProvider;
    }
}
