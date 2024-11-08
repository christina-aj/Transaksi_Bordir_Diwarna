<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Gudang;
use Yii;

/**
 * GudangSearch represents the model behind the search form of `app\models\Gudang`.
 */
class GudangSearch extends Gudang
{
    /**
     * {@inheritdoc}
     */
    public $nama_barang;
    public $nama_pengguna;

    public $kode_barang;

    public function rules()
    {
        return [
            [['id_gudang', 'barang_id', 'user_id'], 'integer'],
            [['tanggal', 'catatan', 'created_at', 'update_at', 'nama_pengguna', 'nama_barang', 'kode_barang'], 'safe'],
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
        $query = Gudang::find()->joinWith(['user', 'barang']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'nama_pengguna' => [
                        'asc' => ['nama_pengguna' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['nama_pengguna' => SORT_DESC],
                    ],
                    'nama_barang' => [
                        'asc' => ['nama_barang' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['nama_barang' => SORT_DESC],
                    ],
                    'kode_barang' => [
                        'asc' => ['nama_barang' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['nama_barang' => SORT_DESC],
                    ],
                    'id_gudang',
                    'tanggal',
                    'barang_id',
                    'user_id',
                    'quantity_awal',
                    'quantity_masuk',
                    'quantity_keluar',
                    'quantity_akhir',
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

        // grid filtering conditions
        $query->andFilterWhere([
            'id_gudang' => $this->id_gudang,
            'barang_id' => $this->barang_id,
            'user_id' => $this->user_id,
            'quantity_awal' => $this->quantity_awal,
            'quantity_masuk' => $this->quantity_masuk,
            'quantity_keluar' => $this->quantity_keluar,
            'quantity_akhir' => $this->quantity_akhir,
            'created_at' => $this->created_at,
            'update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'catatan', $this->catatan]);
        $query->andFilterWhere(['like', 'barang.nama_barang', $this->nama_barang]);
        $query->andFilterWhere(['like', 'user.nama_pengguna', $this->nama_pengguna]);
        $query->andFilterWhere(['like', 'barang.kode_barang', $this->kode_barang]);

        return $dataProvider;
    }
}
