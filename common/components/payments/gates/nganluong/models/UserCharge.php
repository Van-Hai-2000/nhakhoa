<?php

namespace common\components\payments\gates\nganluong\models;

use Yii;
use common\components\ClaActiveRecord;

/**
 * This is the model class for table "user_charge".
 *
 * The followings are the available columns in table 'user_charge':
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $payment_method
 * @property string $bank_code
 * @property integer $status
 * @property integer $payment_status
 * @property string $created_time
 * @property string $modified_time
 * @property string $order_total
 * @property string $multitext
 * @property string $seri
 * @property string $pin
 * @property string $type_card
 */
class UserCharge extends ClaActiveRecord {

    const PAYMENT_METHOD_CARD = 'CARD'; // thanh toán bằng thẻ cào viettel, vinaphone, ...
    const TYPE_CARD_MOBIFONE = 'VMS';
    const TYPE_CARD_VINAPHONE = 'VNP';
    const TYPE_CARD_VIETTEL = 'VIETTEL';
    //
    const PAYMENT_METHOD_NL = 'NL'; // thanh toán bằng ví điện tử ngân lượng
    const PAYMENT_METHOD_ATM_ONLINE = 'ATM_ONLINE'; // thanh toán online bằng thẻ ngân hàng nội địa
    const PAYMENT_METHOD_IB_ONLINE = 'IB_ONLINE'; // thanh toán bằng Internet Banking
    const PAYMENT_METHOD_ATM_OFFLINE = 'ATM_OFFLINE'; // thanh toán atm offline
    const PAYMENT_METHOD_NH_OFFLINE = 'NH_OFFLINE'; // thanh toán tại văn phòng ngân hàng
    const PAYMENT_METHOD_VISA = 'VISA'; // thanh toán bằng thẻ visa hoặc mastercard
    const PAYMENT_METHOD_SMS = 'SMS';
    //

    const PAYMENT_STATUS_WAITING = 1; // chưa thanh toán
    const PAYMENT_STATUS_SUCCESS = 2; // đã thanh toán
    const PAYMENT_STATUS_CANCEL = 0; // hủy đơn hàng khi thanh toán
    const TRANFERED_MONEY = 1; // đã chuyển tiền cho user
    const NOT_TRANFERED_MONEY = 0; // chưa chuyển tiền cho user

    /**
     * @return string the associated database table name
     */

    public static function tableName() {
        return 'user_charge';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('bank_code, order_total, phone', 'required', 'on' => 'atm_online'),
            array('type_card, seri, pin', 'required', 'on' => 'card'),
            array('bank_code, order_total, name, phone', 'required', 'on' => 'visa'),
            array('order_total', 'required', 'on' => 'nganluong'),
            array('status, payment_status', 'numerical', 'integerOnly' => true),
            array('user_id, created_time, modified_time, order_total', 'length', 'max' => 10),
            array('order_total', 'numerical', 'min' => 1000, 'max' => 50000000),
            array('name, email', 'length', 'max' => 255),
            array('phone, payment_method, bank_code', 'length', 'max' => 20),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, name, email, phone, payment_method, bank_code, status, payment_status, created_time, modified_time, order_total, multitext, seri, pin, type_card', 'safe'),
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
            'id' => 'ID',
            'user_id' => 'User',
            'name' => 'Tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'payment_method' => 'Hình thức thanh toán',
            'type_card' => 'Loại thẻ',
            'bank_code' => 'Ngân hàng',
            'status' => 'Status',
            'payment_status' => 'Payment Status',
            'created_time' => 'Created Time',
            'modified_time' => 'Modified Time',
            'order_total' => 'Số tiền',
            'multitext' => 'Multitext',
            'seri' => 'Số seri',
            'pin' => 'Mã số thẻ'
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

        $criteria->compare('id', $this->id, true);
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('payment_method', $this->payment_method, true);
        $criteria->compare('bank_code', $this->bank_code, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('payment_status', $this->payment_status);
        $criteria->compare('created_time', $this->created_time, true);
        $criteria->compare('modified_time', $this->modified_time, true);
        $criteria->compare('order_total', $this->order_total, true);
        $criteria->compare('multitext', $this->multitext, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return UserCharge the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function isPaymentOnline() {
        return in_array($this->payment_method, array(
            self::PAYMENT_METHOD_NL,
            self::PAYMENT_METHOD_ATM_ONLINE,
            self::PAYMENT_METHOD_IB_ONLINE,
            self::PAYMENT_METHOD_ATM_OFFLINE,
            self::PAYMENT_METHOD_NH_OFFLINE,
            self::PAYMENT_METHOD_VISA
        ));
    }

}
