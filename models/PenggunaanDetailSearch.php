<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PenggunaanDetail;

/**
 * PenggunaanDetailSearch represents the model behind the search form of `app\models\PenggunaanDetail`.
 */
class PenggunaanDetailSearch extends PenggunaanDetail
{
    /**
     * {@inheritdoc}
     */

    public $nama_barang;
    public $kode_barang;
    public $kode_penggunaan;

    public function rules()
    {
        return [
            [['gunadetail_id', 'penggunaan_id', 'barang_id', 'jumlah_digunakan'], 'integer'],
            [['catatan', 'created_at', 'updated_at', 'nama_barang', 'kode_penggunaan', 'kode_barang'], 'safe'],
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
        $query = PenggunaanDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'kode_pemesanan' => [
                        'asc' => ['pemesanan.pemesanan_id' => SORT_ASC],
                        'desc' => ['pemesanan.pemesanan_id' => SORT_DESC],
                    ],
                    'nama_barang' => [
                        'asc' => ['barang.nama_barang' => SORT_ASC],
                        'desc' => ['barang.nama_barang' => SORT_DESC],
                    ],
                    'total_item_penggunaan',
                    'catatan',
                ]
            ]
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'barang.nama_barang', $this->nama_barang]);
        $query->andFilterWhere(['like', 'pemesanan.pemesanan_id', $this->kode_pemesanan]);
        // grid filtering conditions
        $query->andFilterWhere([
            'gunadetail_id' => $this->gunadetail_id,
            'penggunaan_id' => $this->penggunaan_id,
            'barang_id' => $this->barang_id,
            'jumlah_digunakan' => $this->jumlah_digunakan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan]);

        return $dataProvider;
    }
}
