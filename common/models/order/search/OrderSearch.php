<?php

namespace common\models\order\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\order\Order;

/**
 * OrderSearch represents the model behind the search form about `common\models\order\Order`.
 */
class OrderSearch extends Order {

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_id', 'district_id', 'province_id', 'order_total', 'created_at', 'updated_at', 'confirm_customer_transfer', 'status', 'user_delivery', 'payment_status'], 'integer'],
            [['name', 'email', 'phone', 'address', 'note', 'type_payment', 'payment_method', 'key'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at);
            $start_date .= ' 00:00';
            $end_date .= ' 23:59';
            $start_date = strtotime($start_date);
            $end_date = strtotime($end_date);
            $query->andFilterWhere(['between', 'created_at', $start_date, $end_date]);
        }

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'payment_method' => $this->payment_method,
            'type_payment' => $this->type_payment,
            'user_id' => $this->user_id,
            'district_id' => $this->district_id,
            'province_id' => $this->province_id,
            'order_total' => $this->order_total,
            'payment_status' => $this->payment_status,
//            'updated_at' => $this->updated_at,
            'confirm_customer_transfer' => $this->confirm_customer_transfer,
            'status' => $this->status,
            'user_delivery' => $this->user_delivery,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'key', $this->key])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'note', $this->note]);

        $query->orderBy('id DESC');

        return $dataProvider;
    }

    public function searchCodDelivering($params) {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at);
            $start_date .= ' 00:00';
            $end_date .= ' 23:59';
            $start_date = strtotime($start_date);
            $end_date = strtotime($end_date);
            $query->andFilterWhere(['between', 'created_at', $start_date, $end_date]);
        }

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'district_id' => $this->district_id,
            'province_id' => $this->province_id,
            'order_total' => $this->order_total,
            'payment_status' => $this->payment_status,
            'updated_at' => $this->updated_at,
            'confirm_customer_transfer' => $this->confirm_customer_transfer,
            'status' => $this->status,
            'user_delivery' => $this->user_delivery,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'note', $this->note]);

        $query->andWhere('status=:status1 OR status=:status2', [':status1' => Order::ORDER_COD_DELIVERING, 'status2' => Order::ORDER_DELIVERY_SUCCESS]);

        $query->orderBy('id DESC');

        return $dataProvider;
    }
    
    public function searchYourSelf($params) {
        $query = Order::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        if (!is_null($this->created_at) && strpos($this->created_at, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->created_at);
            $start_date .= ' 00:00';
            $end_date .= ' 23:59';
            $start_date = strtotime($start_date);
            $end_date = strtotime($end_date);
            $query->andFilterWhere(['between', 'created_at', $start_date, $end_date]);
        }

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        // grid filtering conditions
        $query->andWhere('status=:status1 OR status=:status2', [':status1' => Order::ORDER_COD_DELIVERING, 'status2' => Order::ORDER_DELIVERY_SUCCESS]);
        
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'district_id' => $this->district_id,
            'province_id' => $this->province_id,
            'order_total' => $this->order_total,
           'payment_status' => $this->payment_status,
            'updated_at' => $this->updated_at,
            'confirm_customer_transfer' => $this->confirm_customer_transfer,
            'status' => $this->status,
            'user_delivery' => $this->user_delivery,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'phone', $this->phone])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'note', $this->note]);
        $user_id = Yii::$app->user->getId();
        
        $query->andWhere('user_delivery=:user_delivery', [':user_delivery' => $user_id]);

        $query->orderBy('id DESC');

        return $dataProvider;
    }

    public static function getTotal($provider, $fieldName) {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return number_format($total, 0, ',', '.');
    }

}
