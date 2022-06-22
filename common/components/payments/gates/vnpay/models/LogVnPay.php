<?php

namespace common\components\payments\gates\vnpay\models;

use Yii;
use common\components\ClaActiveRecord;

/**
 * This is the model class for table "log_payment_vnpay".
 *
 * The followings are the available columns in table 'log_payment_vnpay':
 * @property string $transaction_id
 * @property string $token
 * @property string $receiver_email
 * @property string $order_code
 * @property integer $total_amount
 * @property string $payment_method
 * @property string $bank_code
 * @property integer $payment_type
 * @property integer $tax_amount
 * @property integer $discount_amount
 * @property integer $fee_shiping
 * @property string $return_url
 * @property string $cancel_url
 * @property string $buyer_fullname
 * @property string $buyer_email
 * @property string $buyer_mobile
 * @property string $buyer_address
 * @property integer $created_time
 */
class LogVnPay extends ClaActiveRecord {

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'log_vnpay';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(['order_id', 'status',  'order_code', 'amount', 'sign', 'created_time'], 'required'),
            array(['status',  'correct'], 'number'),
            array('order_id, status, order_code, amount, sign, created_time, site_id, correct, bank_code', 'safe'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'order_id' => 'Order',
            'status' => 'Status',
            'order_code' => 'Order Code',
            'amount' => 'Amount',
            'sign' => 'Sign',
            'created_time' => 'Created Time',
            'site_id' => 'Site',
            'correct' => 'Correct',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('order_id', $this->order_id, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('order_code', $this->order_code, true);
        $criteria->compare('amount', $this->amount, true);
        $criteria->compare('sign', $this->sign, true);
        $criteria->compare('created_time', $this->created_time, true);
        $criteria->compare('site_id', $this->site_id, true);
        $criteria->compare('correct', $this->correct);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return LogPaymentNganluong the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->created_time = time();
        }
        return parent::beforeSave($insert);
    }

    public static function getModel($order_id) {
        $log = self::findOne($order_id);
        if(!$log) {
            $log = new self();
            $log->order_id = $order_id;
            $log->amount = 1;
            $log->order_code = 1;
            $log->created_time = 1;
            $log->site_id = 1;
            $log->bank_code = 1;
            $log->sign =1;
            $log->status = 0;
            $log->correct = 0;
        }
        return $log;
    }

}
