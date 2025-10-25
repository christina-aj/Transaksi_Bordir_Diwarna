<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PermintaanPelanggan;
use Yii;

class PermintaanPelangganSearch extends PermintaanPelanggan
{
    public $nama_pelanggan;
    
    public function rules()
    {
        return [
            [['permintaan_id', 'pelanggan_id', 'tipe_pelanggan', 'total_item_permintaan', 'status_permintaan'], 'integer'],
            [['tanggal_permintaan', 'created_at', 'updated_at', 'nama_pelanggan'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = PermintaanPelanggan::find()->joinWith(['pelanggan']);

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $tipe_pelanggan = Yii::$app->request->get('tipe_pelanggan');
        if ($tipe_pelanggan && $tipe_pelanggan !== 'all') {
            $query->andWhere(['tipe_pelanggan' => $tipe_pelanggan]);
        }

        $query->andFilterWhere([
            'permintaan_id' => $this->permintaan_id,
            'pelanggan_id' => $this->pelanggan_id,
            'tipe_pelanggan' => $this->tipe_pelanggan,
            'total_item_permintaan' => $this->total_item_permintaan,
            'tanggal_permintaan' => $this->tanggal_permintaan,
        ]);
        
        $query->andFilterWhere(['like', 'master_pelanggan.nama_pelanggan', $this->nama_pelanggan]);
        
        return $dataProvider;
    }
}