<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Barangproduksi;

/**
 * Barangproduksisearch represents the model behind the search form of `app\models\Barangproduksi`.
 */
class Barangproduksisearch extends Barangproduksi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['barang_produksi_id'], 'integer'],
            [['kode_barang_produksi', 'nama', 'nama_jenis', 'deskripsi','ukuran'], 'safe'],
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
        $query = Barangproduksi::find();

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
            'barang_produksi_id' => $this->barang_produksi_id,
        ]);

        $query->andFilterWhere(['like', 'kode_barang_produksi', $this->kode_barang_produksi])
            ->andFilterWhere(['like', 'nama', $this->nama])
            ->andFilterWhere(['like', 'nama_jenis', $this->nama_jenis])
            ->andFilterWhere(['like', 'deskripsi', $this->deskripsi])
            ->andFilterWhere(['like', 'ukuran', $this->ukuran]);

        return $dataProvider;
    }
}
