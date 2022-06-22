<?php

namespace common\models\user;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\UserLog;

/**
 * UserLogSearch represents the model behind the search form about `common\models\user\UserLog`.
 */
class UserLogSearch extends UserLog
{
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'branch_id','time_end','time_start'], 'integer'],
            [['admin_id', 'action','user_id'], 'safe'],
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
        $query = UserLog::find();

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

        $query->joinWith(['branch','user','userAdmin']);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_log.branch_id' => $this->branch_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'user_admin.fullname', $this->admin_id])
            ->andFilterWhere(['like', 'user.username', $this->user_id])
            ->andFilterWhere(['like', 'action', $this->action]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'user_log.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'user_log.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
