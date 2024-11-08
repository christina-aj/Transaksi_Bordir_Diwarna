<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Barang;
use yii\data\Pagination;
use Attribute;

/**
 * BarangSearch represents the model behind the search form of `app\models\Barang`.
 */
class BarangSearch extends Barang
{
    /**
     * {@inheritdoc}
     */

    public $satuan;

    public function rules()
    {
        return [
            [['barang_id', 'unit_id'], 'integer'],
            [['kode_barang', 'nama_barang', 'tipe', 'warna', 'created_at', 'updated_at', 'satuan'], 'safe'],
            [['angka'], 'number'],
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
        $query = Barang::find()->joinWith(['unit']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15, // Jumlah item per halaman
            ],
            'sort' => [
                'attributes' => [
                    'kode_barang',
                    'nama_barang',
                    'angka',
                    'tipe',
                    'warna',
                    'satuan' => [
                        'asc' => ['unit.satuan' => SORT_ASC],
                        'desc' => ['unit.satuan' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'barang_id' => $this->barang_id,
            'unit_id' => $this->unit_id,
            'angka' => $this->angka,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'unit.satuan', $this->satuan]);

        $query->andFilterWhere(['like', 'kode_barang', $this->kode_barang])
            ->andFilterWhere(['like', 'nama_barang', $this->nama_barang])
            ->andFilterWhere(['like', 'tipe', $this->tipe])
            ->andFilterWhere(['like', 'warna', $this->warna]);

        if (!empty($this->tipe)) {
            $query->andFilterWhere(['tipe' => $this->tipe]);
        }
        return $dataProvider;
    }
}
