<?php

namespace common\models\user\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\MedicalRecordItemChild;

/**
 * MedicalRecordItemChildSearch represents the model behind the search form about `common\models\user\MedicalRecordItemChild`.
 */
class MedicalRecordItemChildSearch extends MedicalRecordItemChild
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'medical_record_id', 'medical_record_item_id', 'product_id', 'doctor_id', 'status', 'quantity', 'branh_id', 'created_at', 'updated_at'], 'integer'],
            [['product_name', 'chuan_doan', 'description'], 'safe'],
            [['money'], 'number'],
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
        $query = MedicalRecordItemChild::find();

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
            'user_id' => $this->user_id,
            'medical_record_id' => $this->medical_record_id,
            'medical_record_item_id' => $this->medical_record_item_id,
            'product_id' => $this->product_id,
            'doctor_id' => $this->doctor_id,
            'status' => $this->status,
            'money' => $this->money,
            'quantity' => $this->quantity,
            'branh_id' => $this->branh_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'chuan_doan', $this->chuan_doan])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
