<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LaporanAgregat;

/**
 * LaporanAgregatsearch represents the model behind the search form of `app\models\LaporanAgregat`.
 */
class LaporanAgregatsearch extends LaporanAgregat
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['laporan_id', 'shift_id', 'vs', 'stitch', 'kuantitas', 'bs'], 'integer'],
            [['tanggal_kerja', 'nama_kerjaan'], 'safe'],
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
        $query = LaporanAgregat::find();

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
            'laporan_id' => $this->laporan_id,
            'mesin_id' => $this->mesin_id,
            'shift_id' => $this->shift_id,
            'tanggal_kerja' => $this->tanggal_kerja,
            'vs' => $this->vs,
            'stitch' => $this->stitch,
            'kuantitas' => $this->kuantitas,
            'bs' => $this->bs,
        ]);

        $query->andFilterWhere(['like', 'nama_kerjaan', $this->nama_kerjaan]);

        return $dataProvider;
    }
}
