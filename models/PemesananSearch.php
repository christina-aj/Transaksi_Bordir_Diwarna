<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Pemesanan;
use Yii;

/**
 * PemesananSearch represents the model behind the search form of `app\models\Pemesanan`.
 */

class PemesananSearch extends Pemesanan
{
    /**
     * {@inheritdoc}
     */


    public $kode_pemesanan;
    public $nama_pemesan;
    public function rules()
    {
        return [
            [['pemesanan_id', 'user_id', 'status'], 'integer'],
            [['tanggal', 'created_at', 'updated_at', 'kode_pemesanan', 'nama_pemesan'], 'safe'],
            [['total_item'], 'number'],
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

        $query = Pemesanan::find()->joinWith(['user']);
        $query->orderBy([
            'tanggal' => SORT_DESC,  // Atur default sorting descending berdasarkan 'tanggal'
            // atau untuk kolom lain
            'pemesanan_id' => SORT_DESC,
        ]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
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
                    'status',
                ]
            ],
            'pagination' => [
                'pageSize' => 15,
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

        $query->andFilterWhere(['like', 'pemesanan_id', $this->kode_pemesanan]);
        $query->andFilterWhere(['like', 'nama_pemesan', $this->nama_pemesan]);
        // grid filtering conditions
        $query->andFilterWhere([
            'pemesanan_id' => $this->pemesanan_id,
            'user_id' => $this->user_id,
            'total_item' => $this->total_item,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
