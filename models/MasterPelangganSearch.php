<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\MasterPelanggan;

/**
 * MasterPelangganSearch represents the model behind the search form of `app\models\MasterPelanggan`.
 */
class MasterPelangganSearch extends MasterPelanggan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pelanggan_id'], 'integer'],
            [['kode_pelanggan', 'nama_pelanggan'], 'safe'],
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
        $query = MasterPelanggan::find();

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
            'pelanggan_id' => $this->pelanggan_id,
        ]);

        $query->andFilterWhere(['like', 'kode_pelanggan', $this->kode_pelanggan])
            ->andFilterWhere(['like', 'nama_pelanggan', $this->nama_pelanggan]);

        return $dataProvider;
    }
}
