<?php

namespace common\models\sale\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\sale\OperationSales;

/**
 * OperationSalesSearch represents the model behind the search form about `common\models\sale\OperationSales`.
 */
class OperationSalesSearch extends OperationSales
{
    public $time_end;
    public $time_start;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'day', 'week', 'month', 'year', 'updated_at', 'type_time', 'branch_id', 'doctor_id', 'time_end', 'time_start'], 'integer'],
            [['money'], 'number'],
            [['product_id','product_category_id'], 'safe']
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
        $query = OperationSales::find()->where(['status_delete' => 0]);

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

        $query->joinWith(['userAdmin', 'product', 'branch','productCategory']);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'money' => $this->money,
            'operation_sales.branch_id' => $this->branch_id,
            'operation_sales.doctor_id' => $this->doctor_id,
            'medical_record_id' => $this->medical_record_id,
            'day' => $this->day,
            'week' => $this->week,
            'month' => $this->month,
            'year' => $this->year,
        ]);

        $query->andFilterWhere(['like', 'product.name', $this->product_id])
            ->andFilterWhere(['like', 'product_category.name', $this->product_category_id]);

        if (isset($this->time_start) && isset($this->time_end)) {
            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'operation_sales.updated_at', $beginOfDay])
                ->andFilterWhere(['<', 'operation_sales.updated_at', $endOfDay]);
        }
//        if ($this->type_time) {
//            if ($this->type_time == 1) {
//                $start = new \DateTime(date('Y-m-d', time()) . '00:00:01');
//                $end = new \DateTime(date('Y-m-d', time()) . '23:59:59');
//                $query->andFilterWhere(['>', 'operation_sales.created_at', $start->getTimestamp()]);
//                $query->andFilterWhere(['<', 'operation_sales.created_at', $end->getTimestamp()]);
//            } elseif ($this->type_time == 2) {
//                $query->andFilterWhere(['=', 'operation_sales.week', date('W', time())]);
//                $query->andFilterWhere(['=', 'operation_sales.year', date('Y', time())]);
//            } elseif ($this->type_time == 3) {
//                $query->andFilterWhere(['=', 'operation_sales.month', date('m', time())]);
//                $query->andFilterWhere(['=', 'operation_sales.year', date('Y', time())]);
//            }
//        }

        $query->orderBy('created_at DESC');

        return $dataProvider;
    }
}
