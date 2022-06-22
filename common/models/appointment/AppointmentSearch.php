<?php

namespace common\models\appointment;

use backend\models\UserAdmin;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\appointment\Appointment;

/**
 * AppointmentSearch represents the model behind the search form about `common\models\appointment\Appointment`.
 */
class AppointmentSearch extends Appointment
{
    public $time_start;
    public $time_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'medical_record_id', 'product_category_id', 'branch_id', 'created_at', 'updated_at', 'time_end', 'time_start', 'status'], 'integer'],
            [['description', 'name', 'phone', 'address', 'doctor_id'], 'safe'],
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
        $query = Appointment::find()->where(['status_delete' => 0]);
        if (\backend\modules\auth\components\Helper::checkRoute('/service/appointment/view-one') && !\backend\modules\auth\components\Helper::checkRoute('/service/appointment/index')) {
            $query = Appointment::find()->where(['status_delete' => 0,'doctor_id' => Yii::$app->user->id]);
        }
        $query->joinWith(['userAdmin']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->joinWith(['productCategory']);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'medical_record_id' => $this->medical_record_id,
            'product_category_id' => $this->product_category_id,
            'appointment.status' => $this->status,
            'appointment.branch_id' => $this->branch_id,
            'appointment.phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'appointment.name', $this->name])
            ->andFilterWhere(['like', 'user_admin.fullname', $this->doctor_id])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'product_id', $this->product_id]);

        if (isset($this->time_start) && isset($this->time_end)) {
//            $beginOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_start)->format('Y-m-d 00:00:00'))->getTimestamp();
//            $endOfDay = \DateTime::createFromFormat('Y-m-d H:i:s', (new \DateTime())->setTimestamp($this->time_end)->format('Y-m-d 23:59:59'))->getTimestamp();
            $query->andFilterWhere(['>', 'time', $this->time_start - 60])
                ->andFilterWhere(['<=', 'time', $this->time_end]);
        }else{
            $beginOfDay = strtotime("today", time());
            $endOfDay   = strtotime("tomorrow", $beginOfDay) - 1;
            $query->andFilterWhere(['>', 'time', $beginOfDay])
                ->andFilterWhere(['<', 'time', $endOfDay]);
        }

        $query->orderBy('time DESC');
        return $dataProvider;
    }
}
