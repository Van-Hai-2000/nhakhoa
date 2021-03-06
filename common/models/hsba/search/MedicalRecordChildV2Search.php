<?php

namespace common\models\hsba\search;

use common\models\hsba\MedicalRecordChildV2;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MedicalRecordChildSearch represents the model behind the search form about `common\models\user\MedicalRecordChild`.
 */
class MedicalRecordChildV2Search extends MedicalRecordChildV2
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

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
    public function search($params,$id)
    {
        $query = MedicalRecordChildV2::find()->where(['medical_record_id' => $id])->andWhere('medical_record_child_v2.quantity > medical_record_child_v2.quantity_use');

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

        $query->joinWith(['product','user','productCategory']);

        // grid filtering conditions
        $query->andFilterWhere([
        ]);

        return $dataProvider;
    }

    public function searchAll($params,$id)
    {
        $query = MedicalRecordChildV2::find()->where(['medical_record_id' => $id]);

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

        $query->joinWith(['product','user','productCategory']);

        // grid filtering conditions
        $query->andFilterWhere([
        ]);

        return $dataProvider;
    }
}
