<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WaitingList;

/**
 * WaitingListSearch represents the model behind the search form about `common\models\WaitingList`.
 */
class WaitingListSearch extends WaitingList
{
    public $time_end;
    public $time_start;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status','branch_id','time_end','time_start','medical_record_id'], 'integer'],
            [['user_id','doctor_id'], 'safe'],
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
        $query = WaitingList::find()->where(['status_delete' => 0]);
        $user = Yii::$app->user->getIdentity();

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

        if (!$this->branch_id) {
            $this->branch_id = $user->branch_id;
        }

        $query->joinWith(['user', 'branch','userAdmin']);
        // grid filtering conditions
        $query->andFilterWhere([
            'waiting_list.id' => $this->id,
            'waiting_list.status' => $this->status,
            'waiting_list.branch_id' => $this->branch_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'medical_record_id' => $this->medical_record_id,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->user_id])
            ->andFilterWhere(['like', 'user_admin.fullname', $this->doctor_id]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
        }else{
            $beginOfDay = strtotime("today", time());
            $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
        }
        $query->andFilterWhere(['>', 'waiting_list.created_at', $beginOfDay])
            ->andFilterWhere(['<', 'waiting_list.created_at', $endOfDay]);

        $query->orderBy('status ASC, created_at ASC');

        return $dataProvider;
    }
}
