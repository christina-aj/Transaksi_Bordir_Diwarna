<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Penggunaan;

/**
 * PenggunaanSearch represents the model behind the search form of `app\models\Penggunaan`.
 */
class PenggunaanSearch extends Penggunaan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['penggunaan_id', 'user_id', 'total_item_penggunaan', 'status_penggunaan'], 'integer'],
            [['created_at', 'updated_at', 'tanggal'], 'safe'],
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
        $query = Penggunaan::find();

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
            'penggunaan_id' => $this->penggunaan_id,
            'user_id' => $this->user_id,
            'total_item_penggunaan' => $this->total_item_penggunaan,
            'status_penggunaan' => $this->status_penggunaan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'tanggal' => $this->tanggal,
        ]);

        return $dataProvider;
    }
}
