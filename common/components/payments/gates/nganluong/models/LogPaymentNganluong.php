<?php

namespace common\components\payments\gates\nganluong\models;

use Yii;
use common\components\ClaActiveRecord;

/**
 * This is the model class for table "log_payment_nganluong".
 *
 * The followings are the available columns in table 'log_payment_nganluong':
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
class LogPaymentNganluong extends ClaActiveRecord {

    /**
     * @return string the associated database table name
     */
    public static function tableName() {
        return 'log_payment_nganluong';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array(['transaction_id'], 'required'),
            array(['total_amount', 'payment_type', 'tax_amount', 'discount_amount', 'fee_shiping'], 'number'),
            array(['transaction_id', 'order_code'], 'string', 'max' => 10),
            array(['token', 'receiver_email', 'return_url', 'cancel_url', 'buyer_fullname', 'buyer_email', 'buyer_mobile', 'buyer_address'], 'string', 'max' => 255),
            array(['payment_method'], 'string', 'max' => 128),
            array('bank_code', 'string', 'max' => 20),
            array(['transaction_id', 'token', 'receiver_email', 'order_code', 'total_amount', 'payment_method', 'bank_code', 'payment_type', 'tax_amount', 'discount_amount', 'fee_shiping', 'return_url', 'cancel_url', 'buyer_fullname', 'buyer_email', 'buyer_mobile', 'buyer_address', 'created_time'], 'safe'),
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
            'transaction_id' => 'Transaction',
            'token' => 'Token',
            'receiver_email' => 'Receiver Email',
            'order_code' => 'Order Code',
            'total_amount' => 'Total Amount',
            'payment_method' => 'Payment Method',
            'bank_code' => 'Bank Code',
            'payment_type' => 'Payment Type',
            'tax_amount' => 'Tax Amount',
            'discount_amount' => 'Discount Amount',
            'fee_shiping' => 'Fee Shiping',
            'return_url' => 'Return Url',
            'cancel_url' => 'Cancel Url',
            'buyer_fullname' => 'Buyer Fullname',
            'buyer_email' => 'Buyer Email',
            'buyer_mobile' => 'Buyer Mobile',
            'buyer_address' => 'Buyer Address',
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

        $criteria->compare('transaction_id', $this->transaction_id, true);
        $criteria->compare('token', $this->token, true);
        $criteria->compare('receiver_email', $this->receiver_email, true);
        $criteria->compare('order_code', $this->order_code, true);
        $criteria->compare('total_amount', $this->total_amount);
        $criteria->compare('payment_method', $this->payment_method, true);
        $criteria->compare('bank_code', $this->bank_code, true);
        $criteria->compare('payment_type', $this->payment_type);
        $criteria->compare('tax_amount', $this->tax_amount);
        $criteria->compare('discount_amount', $this->discount_amount);
        $criteria->compare('fee_shiping', $this->fee_shiping);
        $criteria->compare('return_url', $this->return_url, true);
        $criteria->compare('cancel_url', $this->cancel_url, true);
        $criteria->compare('buyer_fullname', $this->buyer_fullname, true);
        $criteria->compare('buyer_email', $this->buyer_email, true);
        $criteria->compare('buyer_mobile', $this->buyer_mobile, true);
        $criteria->compare('buyer_address', $this->buyer_address, true);

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

}
