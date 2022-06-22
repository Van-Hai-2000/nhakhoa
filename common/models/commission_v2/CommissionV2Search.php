<?php

namespace common\models\commission_v2;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\commission\Commission;
use yii\db\Query;

/**
 * CommissionSearch represents the model behind the search form about `common\models\commission\Commission`.
 */
class CommissionV2Search extends CommissionV2
{
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'branch_id', 'updated_at','time_start','time_end','type','type_money'], 'integer'],
            [['value', 'money', 'total_money'], 'number'],
            [['admin_id','user_id'],'safe']
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
        $pass_rule = (new Query())->select('item_name, user_id')->from('auth_assignment')->where(['user_id' => Yii::$app->user->id])->all();
        $pass_rule = isset($pass_rule) ? array_column($pass_rule, 'item_name') : [];
        $auth_one = 'Hoa hồng-Xem chính mình';
        $auth_all = 'Hoa hồng-Xem tất cả';

        $query = Commission::find()->where(['commission.status_delete' => 0]);

        if(in_array($auth_one,$pass_rule) && !in_array($auth_all,$pass_rule)){
            $query->where(['commission.admin_id' => Yii::$app->user->id]);
        }
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

        $query->joinWith(['user','userAdmin','branch','itemCommission','itemMedicine']);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'commission.value' => $this->value,
            'money' => $this->money,
            'commission.type' => $this->type,
            'commission.type_money' => $this->type_money,
            'commission.total_money' => $this->total_money,
            'commission.medical_record_id' => $this->medical_record_id,
            'commission.branch_id' => $this->branch_id,
        ]);

        $query->andFilterWhere(['like', 'user_admin.fullname', $this->admin_id]);
        $query->andFilterWhere(['like', 'user.username', $this->user_id]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'commission.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'commission.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
