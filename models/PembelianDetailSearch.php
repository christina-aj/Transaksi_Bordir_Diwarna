<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PembelianDetail;
use Yii;

/**
 * PembelianDetailSearch represents the model behind the search form of `app\models\PembelianDetail`.
 */
class PembelianDetailSearch extends PembelianDetail
{
    /**
     * {@inheritdoc}
     */
    public $tanggal;
    public $kode_struk;
    public $kode_barang;
    public $nama_barang;
    public function rules()
    {
        return [
            [['belidetail_id', 'pembelian_id', 'barang_id', 'langsung_pakai'], 'integer'],
            [['harga_barang', 'quantity_barang', 'total_biaya'], 'number'],
            [['catatan', 'created_at', 'updated_at', 'tanggal', 'kode_struk', 'kode_barang', 'nama_barang'], 'safe'],
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
        $query = PembelianDetail::find()->joinWith(['barang', 'pembelian']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'belidetail_id',
                    'pembelian_id',
                    'barang_id',
                    'harga_barang',
                    'quantity_barang',
                    'total_biaya',
                    'catatan',
                    'langsung_pakai',

                    'tanggal' => [
                        'asc' => ['pembelian.tanggal' => SORT_ASC],
                        'desc' => ['pembelian.tanggal' => SORT_DESC],
                    ],
                    'kode_struk' => [
                        'asc' => ['pembelian.kode_struk' => SORT_ASC],
                        'desc' => ['pembelian.kode_struk' => SORT_DESC],
                    ],

                    'kode_barang' => [
                        'asc' => ['barang.kode_barang' => SORT_ASC],
                        'desc' => ['barang.kode_barang' => SORT_DESC],
                    ],
                    'nama_barang' => [
                        'asc' => ['barang.nama_barang' => SORT_ASC],
                        'desc' => ['barang.nama_barang' => SORT_DESC],
                    ],
                    // Tambahkan kolom lain jika diperlukan
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->tanggal)) {
            $dates = explode(' - ', $this->tanggal);
            if (count($dates) == 2) {
                $startDate = \DateTime::createFromFormat('d-m-Y', trim($dates[0]));
                $endDate = \DateTime::createFromFormat('d-m-Y', trim($dates[1]));

                if ($startDate && $endDate) {
                    $formattedStartDate = $startDate->format('Y-m-d');
                    $formattedEndDate = $endDate->format('Y-m-d');
                    $query->andFilterWhere(['between', 'DATE(tanggal)', $formattedStartDate, $formattedEndDate]);
                    Yii::debug('Date filter: ' . $formattedStartDate . ' to ' . $formattedEndDate);
                }
            }
        }
        $query->andFilterWhere(['like', 'pembelian.tanggal', $this->tanggal]);
        $query->andFilterWhere(['like', 'pembelian.kode_struk', $this->kode_struk]);
        $query->andFilterWhere(['like', 'barang.kode_barang', $this->kode_barang]);
        $query->andFilterWhere(['like', 'barang.nama_barang', $this->nama_barang]);
        // grid filtering conditions
        $query->andFilterWhere([
            'belidetail_id' => $this->belidetail_id,
            'pembelian_id' => $this->pembelian_id,
            'barang_id' => $this->barang_id,
            'harga_barang' => $this->harga_barang,
            'quantity_barang' => $this->quantity_barang,
            'total_biaya' => $this->total_biaya,
            'langsung_pakai' => $this->langsung_pakai,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan]);

        return $dataProvider;
    }
}
