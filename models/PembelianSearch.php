<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pembelian;

/**
 * PembelianSearch represents the model behind the search form of `app\models\Pembelian`.
 */

class PembelianSearch extends Pembelian
{
    /**
     * {@inheritdoc}
     */
    public $kode_pembelian;
    public $kode_pemesanan;
    public $nama_pemesan;
    public $tanggal;
    public $total_item;
    public $status;

    public function rules()
    {
        return [
            [['pembelian_id', 'pemesanan_id', 'user_id'], 'integer'],
            [['tanggal', 'kode_pembelian', 'kode_pemesanan', 'nama_pemesan', 'total_item', 'status'], 'safe'],
            [['total_biaya'], 'number'],
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
        $query = Pembelian::find()->joinWith(['pemesanan', 'user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15, // Jumlah item per halaman
            ],
            'sort' => [
                'attributes' => [
                    'kode_pembelian' => [
                        'asc' => ['pembelian_id' => SORT_ASC],
                        'desc' => ['pembelian_id' => SORT_DESC],
                    ],
                    'kode_pemesanan' => [
                        'asc' => ['pemesanan.pemesanan_id' => SORT_ASC],
                        'desc' => ['pemesanan.pemesanan_id' => SORT_DESC],
                    ],
                    'nama_pemesan' => [
                        'asc' => ['user.nama_pengguna' => SORT_ASC],
                        'desc' => ['user.nama_pengguna' => SORT_DESC],
                    ],
                    'tanggal',
                    'total_item',
                    'total_biaya',
                    'status' => [
                        'asc' => ['pemesanan.status' => SORT_ASC],
                        'desc' => ['pemesanan.status' => SORT_DESC],
                    ],
                ]
            ],

        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['like', 'pembelian_id', $this->kode_pembelian]);
        $query->andFilterWhere(['like', 'pemesanan_id', $this->kode_pemesanan]);
        $query->andFilterWhere(['like', 'pemesanan.user_id', $this->nama_pemesan]);
        $query->andFilterWhere(['like', 'pemesanan.tanggal', $this->tanggal]);
        $query->andFilterWhere(['like', 'pemesanan.total_item', $this->total_item]);
        $query->andFilterWhere(['like', 'pemesanan.status', $this->status]);
        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'total_biaya' => $this->total_biaya,
        ]);

        return $dataProvider;
    }
}
