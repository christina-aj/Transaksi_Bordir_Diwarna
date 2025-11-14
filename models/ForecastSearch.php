<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Forecast;

/**
 * ForecastSearch represents the model behind the search form of `app\models\Forecast`.
 */
class ForecastSearch extends Forecast
{
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['forecast_id', 'barang_produksi_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['periode_forecast', 'nilai_alpha', 'mape_test', 'hasil_forecast'], 'number'],
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
        $query = Forecast::find();

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
            'forecast_id' => $this->forecast_id,
            'barang_produksi_id' => $this->barang_produksi_id,
            'periode_forecast' => $this->periode_forecast,
            'nilai_alpha' => $this->nilai_alpha,
            'mape_test' => $this->mape_test,
            'hasil_forecast' => $this->hasil_forecast,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }

}
