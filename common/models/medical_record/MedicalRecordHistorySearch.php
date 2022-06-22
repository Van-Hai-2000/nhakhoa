<?php

namespace common\models\medical_record;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\medical_record\MedicalRecordHistory;

/**
 * MedicalRecordHistorySearch represents the model behind the search form about `common\models\medical_record\MedicalRecordHistory`.
 */
class MedicalRecordHistorySearch extends MedicalRecordHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'product_id', 'doctor_id', 'admin_id', 'created_at', 'updated_at'], 'integer'],
            [['note'], 'safe'],
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
        $query = MedicalRecordHistory::find();

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
            'medical_record_id' => $this->medical_record_id,
            'product_id' => $this->product_id,
            'doctor_id' => $this->doctor_id,
            'admin_id' => $this->admin_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'note', $this->note]);

        return $dataProvider;
    }
}
