<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Stock;
use Yii;

/**
 * StockSearch represents the model behind the search form of `app\models\Stock`.
 */
class StockSearch extends Stock
{
    /**
     * {@inheritdoc}
     */


    public $kode_barang;
    public $nama_barang;
    public $nama_pengguna;

    public function rules()
    {
        return [
            [['stock_id', 'barang_id', 'user_id', 'is_ready', 'is_new'], 'integer'],
            [['tambah_stock', 'created_at', 'updated_at', 'nama_pengguna', 'nama_barang', 'kode_barang'], 'safe'],
            [['quantity_awal', 'quantity_masuk', 'quantity_keluar', 'quantity_akhir'], 'number'],
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
        $query = Stock::find()->joinWith(['barang', 'user']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'stock_id',
                    'tambah_stock',
                    'barang_id',
                    'quantity_awal',
                    'quantity_masuk',
                    'quantity_keluar',
                    'quantity_akhir',
                    'user_id',
                    'nama_pengguna' => [
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
                    'is_new',
                    'is_ready',
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->tambah_stock)) {
            $dates = explode(' - ', $this->tambah_stock);
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
        $query->andFilterWhere(['like', 'barang.kode_barang', $this->kode_barang]);
        $query->andFilterWhere(['like', 'barang.nama_barang', $this->nama_barang]);
        $query->andFilterWhere(['like', 'user.nama_pengguna', $this->nama_pengguna]);
        // grid filtering conditions
        $query->andFilterWhere([
            'stock_id' => $this->stock_id,
            'tambah_stock' => $this->tambah_stock,
            'barang_id' => $this->barang_id,
            'quantity_awal' => $this->quantity_awal,
            'quantity_masuk' => $this->quantity_masuk,
            'quantity_keluar' => $this->quantity_keluar,
            'quantity_akhir' => $this->quantity_akhir,
            'user_id' => $this->user_id,
            'is_ready' => $this->is_ready,
            'is_new' => $this->is_new,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
