<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LaporanProduksi;

/**
 * LaporanProduksisearch represents the model behind the search form of `app\models\LaporanProduksi`.
 */
class LaporanProduksisearch extends LaporanProduksi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['laporan_id', 'shift_id', 'vs', 'stitch', 'kuantitas', 'bs'], 'integer'],
            [['tanggal_kerja', 'nama_kerjaan','nama_mesin'], 'safe'],
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
        $query = LaporanProduksi::find();
        
        $query->orderBy(['tanggal_kerja' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20, 
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'laporan_id' => $this->laporan_id,
            'nama_mesin' => $this->nama_mesin,
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
