<?php

namespace app\models;

use Yii;
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
    public $nama_pengguna;
    public $nama_barang;
    public $kode_barang;
    public function rules()
    {
        return [
            [['penggunaan_id', 'barang_id', 'jumlah_digunakan'], 'integer'],
            [['tanggal_digunakan', 'catatan', 'nama_pengguna', 'nama_barang', 'kode_barang'], 'safe'],
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
        $query = Penggunaan::find()->joinWith(['user', 'barang']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'penggunaan_id', // Aktifkan sorting untuk kolom tanggal
                    'tanggal_digunakan', // Aktifkan sorting untuk kolom pembelian_id
                    'nama_pengguna' => [
                        'asc' => ['supplier.nama' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['supplier.nama' => SORT_DESC],
                    ],
                    'kode_barang' => [
                        'asc' => ['supplier.nama' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['supplier.nama' => SORT_DESC],
                    ],
                    'nama_barang' => [
                        'asc' => ['supplier.nama' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['supplier.nama' => SORT_DESC],
                    ],
                    'jumlah_digunakan', // Aktifkan sorting untuk kolom kode_struk
                    'catatan',
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!empty($this->tanggal_digunakan)) {
            $dates = explode(' - ', $this->tanggal_digunakan);
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
        $query->andFilterWhere(['like', 'barang.nama_barang', $this->nama_barang]);
        $query->andFilterWhere(['like', 'barang.kode_barang', $this->kode_barang]);
        $query->andFilterWhere(['like', 'user.nama_pengguna', $this->nama_pengguna]);
        // grid filtering conditions
        $query->andFilterWhere([
            'penggunaan_id' => $this->penggunaan_id,
            'barang_id' => $this->barang_id,
            'jumlah_digunakan' => $this->jumlah_digunakan,
            'tanggal_digunakan' => $this->tanggal_digunakan,
        ]);

        return $dataProvider;
    }
}
