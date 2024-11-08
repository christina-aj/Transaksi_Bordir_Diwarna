<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Shift;

/**
 * Shiftsearch represents the model behind the search form of `app\models\Shift`.
 */
class Shiftsearch extends Shift
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['shift_id', 'user_id', 'ganti_benang', 'ganti_kain'], 'integer'],
            [['tanggal', 'shift', 'nama_operator', 'mulai_istirahat', 'selesai_istirahat', 'kendala'], 'safe'],
            [['waktu_kerja'], 'number'],
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
        $query = Shift::find();

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
            'shift_id' => $this->shift_id,
            'user_id' => $this->user_id,
            'tanggal' => $this->tanggal,
            'waktu_kerja' => $this->waktu_kerja,
            'mulai_istirahat' => $this->mulai_istirahat,
            'selesai_istirahat' => $this->selesai_istirahat,
            'ganti_benang' => $this->ganti_benang,
            'ganti_kain' => $this->ganti_kain,
        ]);

        $query->andFilterWhere(['like', 'shift', $this->shift])
            ->andFilterWhere(['like', 'nama_operator', $this->nama_operator])
            ->andFilterWhere(['like', 'kendala', $this->kendala]);

        return $dataProvider;
    }
}
