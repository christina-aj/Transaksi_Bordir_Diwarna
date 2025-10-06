<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Gudang;

/**
 * GudangSearch represents the model behind the search form of `app\models\Gudang`.
 */
class GudangSearch extends Gudang
{
    public $nama_barang;
    public $kode_barang;
    public $nama_pengguna;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_gudang', 'barang_id', 'user_id', 'kode', 'area_gudang'], 'integer'],
            [['tanggal', 'catatan', 'created_at', 'update_at', 'nama_barang', 'kode_barang', 'nama_pengguna'], 'safe'],
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
     * @param int|null $kode Filter by kode (1 = barang gudang, 2 = penggunaan)
     *
     * @return ActiveDataProvider
     */
    public function search($params, $kode = null)
    {
        $query = Gudang::find()->joinWith(['barang', 'user']);

        // Filter by kode if specified
        if ($kode !== null) {
            $query->andWhere(['gudang.kode' => $kode]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id_gudang' => SORT_DESC,
                ]
            ],
        ]);

        // Add sorting for joined tables
        $dataProvider->sort->attributes['nama_barang'] = [
            'asc' => ['barang.nama_barang' => SORT_ASC],
            'desc' => ['barang.nama_barang' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['kode_barang'] = [
            'asc' => ['barang.kode_barang' => SORT_ASC],
            'desc' => ['barang.kode_barang' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['nama_pengguna'] = [
            'asc' => ['user.nama_pengguna' => SORT_ASC],
            'desc' => ['user.nama_pengguna' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'gudang.id_gudang' => $this->id_gudang,
            'gudang.barang_id' => $this->barang_id,
            'gudang.user_id' => $this->user_id,
            'gudang.kode' => $this->kode,
            'gudang.quantity_awal' => $this->quantity_awal,
            'gudang.quantity_masuk' => $this->quantity_masuk,
            'gudang.quantity_keluar' => $this->quantity_keluar,
            'gudang.quantity_akhir' => $this->quantity_akhir,
            'gudang.tanggal' => $this->tanggal,
            'gudang.area_gudang' => $this->area_gudang,
            'gudang.created_at' => $this->created_at,
            'gudang.update_at' => $this->update_at,
        ]);

        $query->andFilterWhere(['like', 'gudang.catatan', $this->catatan])
            ->andFilterWhere(['like', 'barang.nama_barang', $this->nama_barang])
            ->andFilterWhere(['like', 'barang.kode_barang', $this->kode_barang])
            ->andFilterWhere(['like', 'user.nama_pengguna', $this->nama_pengguna])
            ->andFilterWhere(['like', 'gudang.area_gudang', $this->area_gudang]);

        return $dataProvider;
    }

    /**
     * Search specifically for barang gudang (kode = 1)
     */
    public function searchBarangGudang($params)
    {
        return $this->search($params, Gudang::KODE_BARANG_GUDANG);
    }

    /**
     * Search specifically for penggunaan (kode = 2)
     */
    public function searchPenggunaan($params)
    {
        return $this->search($params, Gudang::KODE_PENGGUNAAN);
    }

    /**
     * Get stock summary per barang
     */
    public function getStockSummary($barang_id = null)
    {
        $query = Gudang::find()
            ->joinWith('barang')
            ->select([
                'gudang.barang_id',
                'barang.nama_barang',
                'barang.kode_barang',
                'MAX(CASE WHEN gudang.kode = 1 THEN gudang.quantity_akhir ELSE 0 END) as stock_gudang',
                'SUM(CASE WHEN gudang.kode = 2 THEN gudang.quantity_keluar ELSE 0 END) as total_penggunaan'
            ])
            ->groupBy(['gudang.barang_id', 'barang.nama_barang', 'barang.kode_barang']);

        if ($barang_id) {
            $query->andWhere(['gudang.barang_id' => $barang_id]);
        }

        return $query->asArray()->all();
    }

    /**
     * Get latest stock per barang for dropdown or selection
     */
    public function getLatestStockPerBarang()
    {
        $subQuery = Gudang::find()
            ->select(['barang_id', 'MAX(id_gudang) as max_id'])
            ->where(['kode' => Gudang::KODE_BARANG_GUDANG])
            ->groupBy('barang_id');

        $query = Gudang::find()
            ->joinWith('barang')
            ->where(['gudang.id_gudang' => $subQuery])
            ->orderBy(['barang.nama_barang' => SORT_ASC]);

        return $query->all();
    }
}