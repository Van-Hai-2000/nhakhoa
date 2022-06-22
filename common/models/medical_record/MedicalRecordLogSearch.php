<?php

namespace common\models\medical_record;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MedicalRecordLogSearch represents the model behind the search form about `common\models\medical_record\MedicalRecordLog`.
 */
class MedicalRecordLogSearch extends MedicalRecordLog
{
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'branch_id', 'type', 'type_id','time_end','time_start'], 'integer'],
            [['user_id', 'action', 'model'], 'safe'],
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
        $query = MedicalRecordLog::find()->where(['not', ['medical_record_log.medical_record_id' => null]]);

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
        $query->joinWith(['branch','userAdmin']);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'medical_record_log.medical_record_id' => $this->medical_record_id,
            'medical_record_log.branch_id' => $this->branch_id,
            'type' => $this->type,
            'type_id' => $this->type_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'user_admin.fullname', $this->user_id])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'model', $this->model]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'medical_record_log.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'medical_record_log.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }

    public function searchThuchi($params)
    {
        $query = MedicalRecordLog::find()->where(['medical_record_log.type' => MedicalRecordLog::TYPE_4]);

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

        $query->joinWith(['branch','userAdmin']);

        // grid filtering conditions
        $query->andFilterWhere([
            'medical_record_log.id' => $this->id,
            'medical_record_log.medical_record_id' => $this->medical_record_id,
            'medical_record_log.branch_id' => $this->branch_id,
            'type' => $this->type,
            'type_id' => $this->type_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'user_admin.fullname', $this->user_id])
            ->andFilterWhere(['like', 'action', $this->action])
            ->andFilterWhere(['like', 'model', $this->model]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'medical_record_log.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'medical_record_log.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
