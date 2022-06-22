<?php

namespace common\models\sale\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sale\DoctorSales;

/**
 * DoctorSalesSearch represents the model behind the search form about `common\models\sale\DoctorSales`.
 */
class DoctorSalesSearch extends DoctorSales
{
    public $time_end;
    public $time_start;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'week', 'month', 'year', 'created_at', 'updated_at', 'time_end','time_start','type_time'], 'integer'],
            [['doctor_id', 'product_id'], 'safe'],
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
        $query = DoctorSales::find()->where(['status_delete' => 0]);

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

        $query->joinWith(['userAdmin', 'product']);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'money' => $this->money,
            'medical_record_id' => $this->medical_record_id,
            'week' => $this->week,
            'month' => $this->month,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'user_admin.fullname', $this->doctor_id]);
        $query->andFilterWhere(['like', 'product.name', $this->product_id]);

        if ($this->type_time) {
            if ($this->type_time == 1) {
                $start = new \DateTime(date('Y-m-d', time()) . '00:00:01');
                $end = new \DateTime(date('Y-m-d', time()) . '23:59:59');
                $query->andFilterWhere(['>', 'doctor_sales.created_at', $start->getTimestamp()]);
                $query->andFilterWhere(['<', 'doctor_sales.created_at', $end->getTimestamp()]);
            } elseif ($this->type_time == 2) {
                $query->andFilterWhere(['=', 'doctor_sales.week', date('W',time())]);
                $query->andFilterWhere(['=', 'doctor_sales.year', date('Y',time())]);
            } elseif ($this->type_time == 3) {
                $query->andFilterWhere(['=', 'doctor_sales.month', date('m',time())]);
                $query->andFilterWhere(['=', 'doctor_sales.year', date('Y',time())]);
            }
        }

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'doctor_sales.created_at', $beginOfDay])
                ->andFilterWhere(['<', 'doctor_sales.created_at', $endOfDay]);
        }

        $query->orderBy('created_at DESC');
        return $dataProvider;
    }
}
