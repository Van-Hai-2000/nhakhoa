<?php

namespace common\components;

use common\models\log\LogCustom;
use common\models\medical_record\Factory;
use common\models\medical_record\MedicalRecordItemCommission;
use common\models\medical_record\MedicalRecordLog;
use common\models\user\MedicalRecord;
use common\models\user\MedicalRecordItemMedicine;
use common\models\user\PaymentHistory;
use common\models\user\UserLog;
use yii\db\ActiveRecord;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;

/**
 * Created by PhpStorm.
 * User: trungduc.vnu@gmail.com
 * Date: 19/10/2021
 * Time: 8:54 SA
 */
class ClaActiveRecordLog extends ActiveRecord
{
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert != 1) {
            // Cập nhật
            $newattributes = $this->getAttributes();
            $model_path_arr = explode("\\", get_class($this));
            $record_before = [];
            $record_after = [];

            foreach ($newattributes as $name => $value) {
                if (array_key_exists($name, $changedAttributes)) {
                    $label = $this->getAttributeLabel($name);
                    $val = $changedAttributes[$name];
                    $record_after[] = [$label => $value];
                    $record_before[] = [$label => $val];
                }
            }

            if ($record_after) {
                $log = new LogCustom();
                $log->description = '';
                $log->action = ClaLog::ACTION_UPDATE;
                $log->model = end($model_path_arr);
                $log->idModel = $this->getPrimaryKey();
                $log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $log->record_before = json_encode($record_before);
                $log->record_after = json_encode($record_after);
                $log->save();

                //Log màn lịch hẹn
                if (end($model_path_arr) == 'Appointment') {
                    $att_appoinment = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at', 'product_id']);
                    $att_appoinment = ClaNhakhoa::getValueLogAppointment($att_appoinment,'update');
                    $changedAttributesApp = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogAppointment($changedAttributesApp,'update');
                    foreach ($att_appoinment as $name => $value) {
                        if (array_key_exists($name, $changedAttributesApp)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_app[] = [$label => $value];
                            $record_before_app[] = [$label => $val];
                        }
                    }

                    if ($changedAttributesApp) {
                        $record_before_app[] = ['Mã lịch hẹn' => $this->primaryKey];
                        $record_after_app[] = ['Mã lịch hẹn' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_UPDATE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_app);
                        $medical_log->record_after = json_encode($record_after_app);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_1;
                        $medical_log->save();
                    }
                }

                //Log màn thanh toán
                if (end($model_path_arr) == 'PaymentHistory') {
                    $att_pay = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
                    $att_pay = ClaNhakhoa::getValueLogPayment($att_pay,'update');
                    $changedAttributesPay = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogPayment($changedAttributesPay,'update');
                    foreach ($att_pay as $name => $value) {
                        if (array_key_exists($name, $changedAttributesPay)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_pay[] = [$label => $value];
                            $record_before_pay[] = [$label => $val];
                        }
                    }

                    if ($changedAttributesPay) {
                        $record_before_pay[] = ['Mã thanh toán' => $this->primaryKey];
                        $record_after_pay[] = ['Mã thanh toán' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_UPDATE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_pay);
                        $medical_log->record_after = json_encode($record_after_pay);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_2;
                        $medical_log->save();
                    }
                }

                //Log màn đặt xưởng
                if (end($model_path_arr) == 'Factory') {
                    $att_fac = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
                    $att_fac = ClaNhakhoa::getValueLogFac($att_fac,'update');
                    $changedAttributesFac = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogFac($changedAttributesFac,'update');
                    foreach ($att_fac as $name => $value) {
                        if (array_key_exists($name, $changedAttributesFac)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_fac[] = [$label => $value];
                            $record_before_fac[] = [$label => $val];
                        }
                    }

                    if ($changedAttributesFac) {
                        $record_before_fac[] = ['ID đặt xưởng' => $this->primaryKey];
                        $record_after_fac[] = ['ID đặt xưởng' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_UPDATE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_fac);
                        $medical_log->record_after = json_encode($record_after_fac);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_3;
                        $medical_log->save();
                    }
                }

                //Log màn thu chi
                if (end($model_path_arr) == 'ThuChi') {
                    $att_thu = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
                    $att_thu = ClaNhakhoa::getValueLogThuchi($att_thu,'update');
                    $changedAttributesThu = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogThuchi($changedAttributesThu,'update');
                    foreach ($att_thu as $name => $value) {
                        if (array_key_exists($name, $changedAttributesThu)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_thu[] = [$label => $value];
                            $record_before_thu[] = [$label => $val];
                        }
                    }

                    if ($changedAttributesThu) {
                        $record_before_thu[] = ['Mã thu chi' => $this->primaryKey];
                        $record_after_thu[] = ['Mã thu chi' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_UPDATE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_thu);
                        $medical_log->record_after = json_encode($record_after_thu);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_4;
                        $medical_log->save();
                    }
                }

                //Log màn đơn thuốc
                if (end($model_path_arr) == 'MedicalRecordItemMedicine') {
                    $att_medicine = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at','chuan_doan','product_name','description','status']);
                    $att_medicine = ClaNhakhoa::getValueLogMedicine($att_medicine,'update');
                    $changedAttributesMedicine = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogMedicine($changedAttributesMedicine,'update');
                    foreach ($att_medicine as $name => $value) {
                        if (array_key_exists($name, $changedAttributesMedicine)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_medicine[] = [$label => $value];
                            $record_before_medicine[] = [$label => $val];
                        }
                    }

                    if ($changedAttributesMedicine) {
                        $record_before_medicine[] = ['Mã đơn thuốc' => $this->primaryKey];
                        $record_after_medicine[] = ['Mã đơn thuốc' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_UPDATE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_medicine);
                        $medical_log->record_after = json_encode($record_after_medicine);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_5;
                        $medical_log->save();
                    }
                }

                //Log hoa hồng
                if (end($model_path_arr) == 'MedicalRecordItemCommission') {
                    $att_com = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at','payment_status','status','medical_record_item_child_id']);
                    $att_com = ClaNhakhoa::getValueLogCom($att_com);

                    $changedAttributesCom = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogCom($changedAttributesCom);
                    foreach ($att_com as $name => $value) {
                        if (array_key_exists($name, $changedAttributesCom)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_com[] = [$label => $value];
                            $record_before_com[] = [$label => $val];
                        }
                    }

                    if ($changedAttributesCom) {
                        $record_before_com[] = ['Mã hoa hồng' => $this->primaryKey];
                        $record_after_com[] = ['Mã hoa hồng' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_UPDATE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_com);
                        $medical_log->record_after = json_encode($record_after_com);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_6;
                        $medical_log->save();
                    }
                }

                //Log hồ sơ bệnh án
                if (end($model_path_arr) == 'MedicalRecord') {
                    $att_medical = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
                    $att_medical = ClaNhakhoa::getValueLogMedical($att_medical);

                    $changedAttributesCom = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at']);
                    $changed_before = ClaNhakhoa::getValueLogMedical($changedAttributesCom,'update');
                    $stt_delete = false;
                    foreach ($att_medical as $name => $value) {
                        if (array_key_exists($name, $changedAttributesCom)) {
                            if($name == 'status' && $value == 'Đã xóa'){
                                $stt_delete = true;
                                $label = $this->getAttributeLabel($name);
                                $val = $changed_before[$name];
                                $record_after_com[] = [$label => $value];
                                $record_before_com[] = [$label => $val];

                            }

                        }
                    }

                    if ($stt_delete) {
                        $record_before_com[] = ['Mã HSBA' => $this->primaryKey];
                        $record_after_com[] = ['Mã HSBA' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $medical_log = new MedicalRecordLog();
                        $medical_log->action = ClaLog::ACTION_DELETE;
                        $medical_log->model = end($model_path_arr);
                        $medical_log->medical_record_id = $this->primaryKey;
                        $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                        $medical_log->record_before = json_encode($record_before_com);
                        $medical_log->record_after = json_encode($record_after_com);
                        $medical_log->type_id = $this->primaryKey;
                        $medical_log->type = MedicalRecordLog::TYPE_7;
                        $medical_log->save();
                    }
                }

                //Log user
                if (end($model_path_arr) == 'User') {
                    $record_after_user = [];
                    $record_before_user = [];
                    $att_save = ClaNhakhoa::deleteKeyArr($newattributes, ['avatar_path', 'avatar_name', 'tien_su_benh', 'created_at', 'updated_at', 'user_id_app', 'username_app']);
                    $att_save = ClaNhakhoa::getValueLogUser($att_save,'update');
                    $changedAttributesUser = ClaNhakhoa::deleteKeyArr($changedAttributes, ['updated_at', 'created_at']);
                    $changed_before = ClaNhakhoa::getValueLogUser($changedAttributesUser,'update');

                    foreach ($att_save as $name => $value) {
                        if (array_key_exists($name, $changedAttributesUser)) {
                            $label = $this->getAttributeLabel($name);
                            $val = $changed_before[$name];
                            $record_after_user[] = [$label => $value];
                            $record_before_user[] = [$label => $val];
                        }
                    }
                    if ($changedAttributesUser) {
                        $record_before_user[] = ['Mã bệnh nhân' => $this->primaryKey];
                        $record_after_user[] = ['Mã bệnh nhân' => $this->primaryKey];
                        $user_admin = Yii::$app->user->getIdentity();
                        $user_log = new UserLog();
                        $user_log->action = ClaLog::ACTION_UPDATE;
                        $user_log->user_id = $this->getPrimaryKey();
                        $user_log->admin_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                        $user_log->branch_id = isset($user_admin->branch_id) && $user_admin->branch_id ? $user_admin->branch_id : '';
                        $user_log->record_before = json_encode($record_before_user);
                        $user_log->record_after = json_encode($record_after_user);
                        $user_log->save();
                    }
                }
            }
        } else {
            //Thêm mới
            $model_path_arr = explode("\\", get_class($this));
            $newattributes = $this->getAttributes();
            $record_after = [];

            foreach ($newattributes as $name => $value) {
                $label = $this->getAttributeLabel($name);
                $record_after[] = [$label => $value];
            }
            $log = new LogCustom();
            $log->description = '';
            $log->action = ClaLog::ACTION_CREATE;
            $log->model = end($model_path_arr);
            $log->idModel = $this->getPrimaryKey();
            $log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
            $log->record_before = '';
            $log->record_after = json_encode($record_after);
            $log->save();

            //Log riêng từng màn
            if (end($model_path_arr) == 'Appointment') {
                $att_appoinment = ClaNhakhoa::deleteKeyArr($newattributes, ['created_at', 'updated_at', 'product_id','status_delete']);
                $att_appoinment = ClaNhakhoa::getValueLogAppointment($att_appoinment,'create');
                foreach ($att_appoinment as $name => $value) {
                    $label_app = $this->getAttributeLabel($name);
                    $record_after_app[] = [$label_app => $value];
                }

                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_app);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_1;
                $medical_log->save();
            }

            //log màn thanh toán
            if (end($model_path_arr) == 'PaymentHistory') {
                $att_pay = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
                $att_pay = ClaNhakhoa::getValueLogPayment($att_pay,'create');
                foreach ($att_pay as $name => $value) {
                    $label_pay = $this->getAttributeLabel($name);
                    $record_after_pay[] = [$label_pay => $value];
                }

                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_pay);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_2;
                $medical_log->save();
            }

            //log màn đặt xưởng
            if (end($model_path_arr) == 'Factory') {
                $att_fac = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
                $att_fac = ClaNhakhoa::getValueLogFac($att_fac,'create');
                foreach ($att_fac as $name => $value) {
                    $label_fac = $this->getAttributeLabel($name);
                    $record_after_fac[] = [$label_fac => $value];
                }

                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_fac);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_3;
                $medical_log->save();
            }

            //log màn thu chi
            if (end($model_path_arr) == 'ThuChi') {
                $att_thu = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at','status_delete']);
                $att_thu = ClaNhakhoa::getValueLogThuchi($att_thu,'create');
                foreach ($att_thu as $name => $value) {
                    $label_thu = $this->getAttributeLabel($name);
                    $record_after_thu[] = [$label_thu => $value];
                }

                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_thu);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_4;
                $medical_log->save();
            }

            //log màn thuốc
            if (end($model_path_arr) == 'MedicalRecordItemMedicine') {
                $att_medicine = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at','chuan_doan','product_name','description','status']);
                $att_medicine = ClaNhakhoa::getValueLogMedicine($att_medicine,'create');
                foreach ($att_medicine as $name => $value) {
                    $label_medicine = $this->getAttributeLabel($name);
                    $record_after_medicine[] = [$label_medicine => $value];
                }

                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_medicine);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_5;
                $medical_log->save();
            }

            //log hoa hồng
            if (end($model_path_arr) == 'MedicalRecordItemCommission') {
                $att_com = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at','payment_status','status','medical_record_item_child_id']);
                $att_com = ClaNhakhoa::getValueLogCom($att_com);
                foreach ($att_com as $name => $value) {
                    $label_com = $this->getAttributeLabel($name);
                    $record_after_com[] = [$label_com => $value];
                }
                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_com);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_6;
                $medical_log->save();
            }

            //log hồ sơ bệnh án
            if (end($model_path_arr) == 'MedicalRecord') {
                $att_com = ClaNhakhoa::deleteKeyArr($newattributes, ['username','updated_at','ly_do','total_money','money','sale_money','status','name','note','ly_do','introduce','introduce_id','branch_related']);
                $att_com = ClaNhakhoa::getValueLogMedical($att_com,'create');
                foreach ($att_com as $name => $value) {
                    $label_com = $this->getAttributeLabel($name);
                    $record_after_com[] = [$label_com => $value];
                }
                $user_admin = Yii::$app->user->getIdentity();
                $medical_log = new MedicalRecordLog();
                $medical_log->action = ClaLog::ACTION_CREATE;
                $medical_log->model = end($model_path_arr);
                $medical_log->medical_record_id = $this->primaryKey;
                $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
                $medical_log->record_before = '';
                $medical_log->record_after = json_encode($record_after_com);
                $medical_log->type_id = $this->primaryKey;
                $medical_log->type = MedicalRecordLog::TYPE_7;
                $medical_log->save();
            }

            //Màn user
            if (end($model_path_arr) == 'User') {
                $att_save = ClaNhakhoa::deleteKeyArr($newattributes, ['avatar_path', 'avatar_name', 'tien_su_benh', 'created_at', 'updated_at', 'user_id_app', 'username_app']);
                $att_save = ClaNhakhoa::getValueLogUser($att_save,'create');
                foreach ($att_save as $name_us => $value_us) {
                    $label_user = $this->getAttributeLabel($name_us);
                    $record_after_user[] = [$label_user => $value_us];
                }
                $user_admin = Yii::$app->user->getIdentity();
                $user_log = new UserLog();
                $user_log->action = ClaLog::ACTION_CREATE;
                $user_log->user_id = $this->getPrimaryKey();
                $user_log->admin_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
                $user_log->branch_id = isset($user_admin->branch_id) && $user_admin->branch_id ? $user_admin->branch_id : '';
                $user_log->record_before = '';
                $user_log->record_after = json_encode($record_after_user);
                $user_log->save();
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    public function afterDelete()
    {
        $model_path_arr = explode("\\", get_class($this));
        $newattributes = $this->getAttributes();
        $record_after = [];
        foreach ($newattributes as $name => $value) {
            $label = $this->getAttributeLabel($name);
            $record_after[] = [$label => $value];
        }
        $log = new LogCustom();
        $log->description = '';
        $log->action = ClaLog::ACTION_DELETE;
        $log->model = end($model_path_arr);
        $log->idModel = $this->getPrimaryKey();
        $log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
        $log->record_before = json_encode($record_after);
        $log->record_after = '';
        $log->save();

        //Log riêng từng màn
        if (end($model_path_arr) == 'Appointment') {
            $att_appoinment = ClaNhakhoa::deleteKeyArr($newattributes, ['created_at', 'updated_at', 'product_id','status_delete']);
            $att_appoinment = ClaNhakhoa::getValueLogAppointment($att_appoinment,'delete');
            foreach ($att_appoinment as $name => $value) {
                $label_app = $this->getAttributeLabel($name);
                $record_before_app[] = [$label_app => $value];
            }

            $user_admin = Yii::$app->user->getIdentity();
            $medical_log = new MedicalRecordLog();
            $medical_log->action = ClaLog::ACTION_DELETE;
            $medical_log->model = end($model_path_arr);
            $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
            $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
            $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
            $medical_log->record_before = json_encode($record_before_app);
            $medical_log->record_after = '';
            $medical_log->type_id = $this->primaryKey;
            $medical_log->type = MedicalRecordLog::TYPE_1;
            $medical_log->save();
        }

        //Màn thanh toán
        if (end($model_path_arr) == 'PaymentHistory') {
            $att_pay = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
            $att_pay = ClaNhakhoa::getValueLogPayment($att_pay,'delete');
            foreach ($att_pay as $name => $value) {
                $label_pay = $this->getAttributeLabel($name);
                $record_before_pay[] = [$label_pay => $value];
            }

            $user_admin = Yii::$app->user->getIdentity();
            $medical_log = new MedicalRecordLog();
            $medical_log->action = ClaLog::ACTION_DELETE;
            $medical_log->model = end($model_path_arr);
            $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
            $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
            $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
            $medical_log->record_before = json_encode($record_before_pay);
            $medical_log->record_after = '';
            $medical_log->type_id = $this->primaryKey;
            $medical_log->type = MedicalRecordLog::TYPE_2;
            $medical_log->save();
        }

        //Màn đặt xưởng
        if (end($model_path_arr) == 'Factory') {
            $att_fac = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
            $att_fac = ClaNhakhoa::getValueLogFac($att_fac,'delete');
            foreach ($att_fac as $name => $value) {
                $label_fac = $this->getAttributeLabel($name);
                $record_before_fac[] = [$label_fac => $value];
            }

            $user_admin = Yii::$app->user->getIdentity();
            $medical_log = new MedicalRecordLog();
            $medical_log->action = ClaLog::ACTION_DELETE;
            $medical_log->model = end($model_path_arr);
            $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
            $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
            $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
            $medical_log->record_before = json_encode($record_before_fac);
            $medical_log->record_after = '';
            $medical_log->type_id = $this->primaryKey;
            $medical_log->type = MedicalRecordLog::TYPE_3;
            $medical_log->save();
        }

        //Màn thu chi
        if (end($model_path_arr) == 'ThuChi') {
            $att_thu = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at']);
            $att_thu = ClaNhakhoa::getValueLogThuchi($att_thu,'delete');
            foreach ($att_thu as $name => $value) {
                $label_thu = $this->getAttributeLabel($name);
                $record_before_thu[] = [$label_thu => $value];
            }

            $user_admin = Yii::$app->user->getIdentity();
            $medical_log = new MedicalRecordLog();
            $medical_log->action = ClaLog::ACTION_DELETE;
            $medical_log->model = end($model_path_arr);
            $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
            $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
            $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
            $medical_log->record_before = json_encode($record_before_thu);
            $medical_log->record_after = '';
            $medical_log->type_id = $this->primaryKey;
            $medical_log->type = MedicalRecordLog::TYPE_4;
            $medical_log->save();
        }

        //Màn thuốc
        if (end($model_path_arr) == 'MedicalRecordItemMedicine') {
            $att_medicine = ClaNhakhoa::deleteKeyArr($newattributes, ['updated_at','chuan_doan','product_name','description','status']);
            $att_medicine = ClaNhakhoa::getValueLogMedicine($att_medicine,'delete');
            foreach ($att_medicine as $name => $value) {
                $label_thu = $this->getAttributeLabel($name);
                $record_before_thu[] = [$label_thu => $value];
            }

            $user_admin = Yii::$app->user->getIdentity();
            $medical_log = new MedicalRecordLog();
            $medical_log->action = ClaLog::ACTION_DELETE;
            $medical_log->model = end($model_path_arr);
            $medical_log->medical_record_id = isset($newattributes['medical_record_id']) && $newattributes['medical_record_id'] ? $newattributes['medical_record_id'] : '';
            $medical_log->user_id = isset(Yii::$app->user->id) && Yii::$app->user->id ? Yii::$app->user->id . '' : '';
            $medical_log->branch_id = isset($newattributes['branch_id']) && $newattributes['branch_id'] ? $newattributes['branch_id'] : $user_admin->branch_id;
            $medical_log->record_before = json_encode($record_before_thu);
            $medical_log->record_after = '';
            $medical_log->type_id = $this->primaryKey;
            $medical_log->type = MedicalRecordLog::TYPE_5;
            $medical_log->save();
        }

    }

}