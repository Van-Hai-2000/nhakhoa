<?php

namespace common\models\voucher;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\voucher\Voucher;

/**
 * VoucherSearch represents the model behind the search form about `common\models\voucher\Voucher`.
 */
class VoucherSearch extends Voucher
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status'], 'integer'],
            [['voucher', 'description'], 'safe'],
            [['type_value', 'money_start', 'money_end'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Voucher::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'type_value' => $this->type_value,
            'money_start' => $this->money_start,
            'money_end' => $this->money_end,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'voucher', $this->voucher])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
