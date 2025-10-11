<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DataPerhitungan;

/**
 * DataPerhitunganSearch represents the model behind the search form of `app\models\DataPerhitungan`.
 */
class DataPerhitunganSearch extends DataPerhitungan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_perhitungan_id', 'barang_id', 'lead_time_rerata'], 'integer'],
            [['biaya_pesan', 'biaya_simpan', 'safety_stock'], 'number'],
            [['periode_mulasi', 'periode_selesai', 'created_at'], 'safe'],
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
        $query = DataPerhitungan::find();

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
            'data_perhitungan_id' => $this->data_perhitungan_id,
            'barang_id' => $this->barang_id,
            'biaya_pesan' => $this->biaya_pesan,
            'biaya_simpan' => $this->biaya_simpan,
            'safety_stock' => $this->safety_stock,
            'lead_time_rerata' => $this->lead_time_rerata,
            'periode_mulasi' => $this->periode_mulasi,
            'periode_selesai' => $this->periode_selesai,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
