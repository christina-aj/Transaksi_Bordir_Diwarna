<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\EoqRop;

/**
 * EoqRopSearch represents the model behind the search form of `app\models\EoqRop`.
 */
class EoqRopSearch extends EoqRop
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['EOQ_ROP_id', 'barang_id', 'total_bom', 'lead_time_snapshot'], 'integer'],
            [['biaya_pesan_snapshot', 'biaya_simpan_snapshot', 'safety_stock_snapshot', 'demand_snapshot', 'total_biaya_persediaan', 'hasil_eoq', 'hasil_rop'], 'number'],
            [['periode', 'created_at'], 'safe'],
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
        $query = EoqRop::find();

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
            'EOQ_ROP_id' => $this->EOQ_ROP_id,
            'barang_id' => $this->barang_id,
            'total_bom' => $this->total_bom,
            'biaya_pesan_snapshot' => $this->biaya_pesan_snapshot,
            'biaya_simpan_snapshot' => $this->biaya_simpan_snapshot,
            'safety_stock_snapshot' => $this->safety_stock_snapshot,
            'lead_time_snapshot' => $this->lead_time_snapshot,
            'demand_snapshot' => $this->demand_snapshot,
            'total_biaya_persediaan' => $this->total_biaya_persediaan,
            'hasil_eoq' => $this->hasil_eoq,
            'hasil_rop' => $this->hasil_rop,
            'periode' => $this->periode,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
