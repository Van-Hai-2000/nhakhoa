<?php

namespace common\models\hsba\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\hsba\MedicalRecordV2;

/**
 * MedicalRecordSearch represents the model behind the search form about `common\models\user\MedicalRecord`.
 */
class MedicalRecordV2Search extends MedicalRecordV2
{
    public $status_color;
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'status', 'updated_at','introduce','time_end', 'time_start','branch_id'], 'integer'],
            [['username', 'phone', 'name', 'note','branch_related'], 'safe'],
            [['total_money', 'money'], 'number'],
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
        $query = MedicalRecordV2::find()->where(['<>','status',MedicalRecordV2::STATUS_DELETE]);

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
        $query->joinWith(['branch']);
        // grid filtering conditions
        $query->andFilterWhere([
            'medical_record_v2.id' => $this->id,
            'user_id' => $this->user_id,
            'medical_record_v2.branch_id' => $this->branch_id,
            'total_money' => $this->total_money,
            'money' => $this->money,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'medical_record_v2.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'medical_record_v2.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');
        return $dataProvider;
    }

    public function searchCn($params)
    {
        $query = MedicalRecordV2::find()->where(['<>','status',MedicalRecordV2::STATUS_DELETE]);
        $query->andWhere('total_money > money');
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
            'total_money' => $this->total_money,
            'money' => $this->money,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'branch_related', $this->branch_related])
            ->andFilterWhere(['like', 'name', $this->name]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'medical_record_v2.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'medical_record_v2.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');
        return $dataProvider;
    }

    public function searchGt($params)
    {
        $query = MedicalRecordV2::find()->where(['<>','status',MedicalRecordV2::STATUS_DELETE]);
        $query->andWhere(['not', ['introduce' => null]]);
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
            'total_money' => $this->total_money,
            'money' => $this->money,
            'status' => $this->status,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'branch_related', $this->branch_related])
            ->andFilterWhere(['like', 'note', $this->note]);

        if(isset($this->introduce) && isset($this->introduce)){
            $query->andFilterWhere(['introduce' => $this->introduce]);
        }

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'medical_record_v2.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'medical_record_v2.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');
        return $dataProvider;
    }
}
