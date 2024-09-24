<?php

namespace app\models;

use Yii;

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
    public $nama_pengguna;
    public $nama_supplier;
    public function rules()
    {
        return [
            [['pembelian_id', 'user_id', 'supplier_id', 'langsung_pakai'], 'integer'],
            [['total_biaya', 'kode_struk', 'tanggal', 'nama_supplier', 'nama_pengguna'], 'safe'],
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
        $query = Pembelian::find()->innerJoinWith(['supplier', 'user']);

        // add conditions that should always apply here
        Yii::debug('Initial query: ' . $query->createCommand()->rawSql);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'tanggal', // Aktifkan sorting untuk kolom tanggal
                    'pembelian_id', // Aktifkan sorting untuk kolom pembelian_id
                    'user_id', // Aktifkan sorting untuk kolom user_id
                    'supplier_id', // Aktifkan sorting untuk kolom supplier_id
                    'total_biaya', // Aktifkan sorting untuk kolom total_biaya
                    'kode_struk', // Aktifkan sorting untuk kolom kode_struk
                    'langsung_pakai',
                    'nama_supplier' => [
                        'asc' => ['supplier.nama' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['supplier.nama' => SORT_DESC],
                    ],
                    'nama_pengguna' => [
                        'asc' => ['user.nama_pengguna' => SORT_ASC],  // Kolom supplier_name diurutkan berdasarkan nama di tabel supplier
                        'desc' => ['user.nama_pengguna' => SORT_DESC],
                    ],

                    // Tambahkan kolom lain jika diperlukan
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            Yii::debug('Validation failed: ' . json_encode($this->errors));
            $query->where('0=1');
            return $dataProvider;
        }

        Yii::debug('Filter input: ' . json_encode($this->attributes));
        // Konversi dan filter rentang tanggal dari dd-mm-yyyy ke yyyy-mm-dd
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

        $query->andFilterWhere(['like', 'supplier.nama', $this->nama_supplier]);
        $query->andFilterWhere(['like', 'user.nama_pengguna', $this->nama_pengguna]);


        // grid filtering conditions tanpa filter tanggal yang duplikat
        $query->andFilterWhere([
            'pembelian_id' => $this->pembelian_id,
            'user_id' => $this->user_id,
            'supplier_id' => $this->supplier_id,
            'langsung_pakai' => $this->langsung_pakai,
        ]);
        Yii::debug('Final query: ' . $query->createCommand()->rawSql);

        // Filter kolom lainnya
        $query->andFilterWhere(['like', 'total_biaya', $this->total_biaya])
            ->andFilterWhere(['like', 'kode_struk', $this->kode_struk]);

        return $dataProvider;
    }
}
