<?php

namespace backend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use common\models\sync\Dmvt;
use common\models\sync\AttributeMap;
use common\models\product\Product;
use common\models\product\ProductCategory;
use common\components\ClaCategory;
use common\components\ClaProduct;
use common\models\product\ProductAttributeOption;
use common\models\product\ProductAttribute;

class SyncController extends Controller {

    public function actionGetProduct() {
        echo "<pre>";
        print_r(987);
        echo "</pre>";
        die();
//        ini_set('display_errors', 0);
//        error_reporting(0);
//        ->select('VtID, FK_DaID, Fk_hinhdang, Fk_tscID, Fk_qtID, Fk_vcID, Fk_mota, Fk_dcID, Fk_kodcID, Fk_chatlieu, Fk_mausac, Fk_gtID, Fk_dtID, Fk_mdID, Fk_mhID, Fk_size, Fk_phanloai, Fk_hddID, Fk_kcdID, Fk_odaID, Fk_cdID, Fk_doitacID, Fk_ktl, Fk_baohanh, Fk_klID, Fk_dobenID, FK_VtID, Code, VtName, VtName2, Fk_loaiID, FK_Nhvt2ID, FK_DvtID, FK_TkID_vt, FK_TkID_gv, FK_TkID_dt, FK_TkID_tl, Imagepath2, ImagePath, Gia_mua0, Ck_Mua, Hlg_au, Tlg_au, Gia_au0, Tlg_da, Gia_mua, Tong_tlg, Gia_cong0, Tien_da0')
        $time_start = time();
        $data = Dmvt::find()
                ->select('*')
                ->where('1=1')
                ->limit(2000)
                ->offset(8000)
                ->asArray()
                ->all();
        if (isset($data) && $data) {
            foreach ($data as $item) {
                $product = new Product();
                // Category
                $categories = ProductCategory::find()
                        ->where('map_nhvt2_final LIKE :map_nhvt2_final', [':map_nhvt2_final' => '%' . $item['FK_Nhvt2ID'] . '%'])
                        ->asArray()
                        ->all();
                //
                if (isset($categories) && $categories) {
                    $category_track_all = [];
                    if (count($categories) == 1) {
                        $cat_model = $categories[0];
                    } else {
                        foreach ($categories as $key => $cat) {
                            $dynamic_field = json_decode($cat['dynamic_field'], true);
                            $attrs = [];
                            if (isset($dynamic_field) && $dynamic_field) {
                                foreach ($dynamic_field as $field) {
                                    $attrs[] = ProductAttributeOption::find()
                                            ->where('attribute_id=:attribute_id AND index_key IN (' . join(',', $field['index_key']) . ')', [':attribute_id' => $field['id']])
                                            ->asArray()
                                            ->all();
                                }
                            }
                            $dynamic_field['attrs'] = $attrs;
                            // $categories[$key]['dynamic_field_decode'] = $dynamic_field;
                            $map = [];
                            foreach ($attrs as $attr) {
                                foreach ($attr as $att) {
                                    $map[$att['field_app']][] = $att['code_app'];
                                }
                            }
                            $categories[$key]['map_product'] = $map;
                        }
                        $categories_correct = [];
                        foreach ($categories as $cat_process) {
                            $check_correct = true;
                            foreach ($cat_process['map_product'] as $field_name => $field_data) {
                                if (!in_array($item[$field_name], $field_data)) {
                                    $check_correct = false;
                                }
                            }
                            if ($check_correct === true) {
                                $categories_correct[] = $cat_process;
                            }
                        }
                        $cat_model = [];
                        if (count($categories_correct)) {
                            $i = 0;
                            foreach ($categories_correct as $k => $cat_correct) {
                                if ($i < $cat_correct['level']) {
                                    $i = $k;
                                }
                                $category = new ClaCategory();
                                $category->type = ClaCategory::CATEGORY_PRODUCT;
                                $category->generateCategory();
                                //
                                $category_track_temp = array_reverse($category->saveTrack($cat_correct['id']));
                                $category_track_all = array_unique(array_merge($category_track_all, $category_track_temp));
                            }
                            $cat_model = $categories_correct[$i];
                        }
                    }
                    if (count($cat_model)) {
                        $map_price_formula = \common\models\product\MapPriceFormula::find()
                                ->where('code_app=:code_app', [':code_app' => $item['FK_Nhvt2ID']])
                                ->one();
                        if (isset($map_price_formula) && $map_price_formula) {
                            $product->price_formula = $map_price_formula->price_formula;
                        }
                        $product->name = $item['VtName'];
                        $product->code = $item['Code'];
                        $product->ratio_gold = $item['Hlg_au']; // Hàm lượng vàng
                        $product->weight_gold = $item['Tlg_au']; // Trọng lượng vàng
                        $product->price_gold = $item['Gia_au0']; // Giá vàng
                        $product->weight_stone = $item['Tlg_da']; // Trọng lượng đá
                        $product->price_stone = $item['Tien_da0']; // Tiền đá
                        $product->price_buy = $item['Gia_mua']; // Giá mua
                        $product->weight = $item['Tong_tlg']; // Trọng lượng
                        $product->fee = $item['Gia_cong0']; // Tiền công
                        //
                        $product->category_id = $cat_model['id'];
                        //
                        $category = new ClaCategory();
                        $category->type = ClaCategory::CATEGORY_PRODUCT;
                        $category->generateCategory();
                        //
                        $category_track = array_reverse($category->saveTrack($cat_model['id']));
                        $track = implode(ClaCategory::CATEGORY_SPLIT, $category_track);
                        //
                        $product->category_track = $track;
                        $product->category_track_all = implode(ClaCategory::CATEGORY_SPLIT, $category_track_all);
                        //
                        $image1 = $item['ImagePath'];
                        $image2 = $item['Imagepath2'];
                        $image_path = '/media/images/img/';
                        if ($image1) {
                            $image1 = explode('\\', $image1);
                            $image1_name = end($image1);
                            $product->avatar_path = $image_path;
                            $product->avatar_name = $image1_name;
                            $product_exist = Product::find()->where('avatar_name=:avatar_name', [':avatar_name' => $image1_name])->one();
                            if ($product_exist !== NULL) {
                                $product->is_configurable = 1;
                                $product->parent_id = $product_exist->id;
                            }
                        }
                        if ($image2) {
                            $image2 = explode('\\', $image2);
                            $image2_name = end($image2);
                            $product->avatar2_path = $image_path;
                            $product->avatar2_name = $image2_name;
                        }

                        // Thuộc tính
                        $attributes = ClaProduct::attrMap();
                        $attributeValue = array();
                        foreach ($attributes as $attribute_key => $attribute) {
                            if (isset($item[$attribute_key]) && $item[$attribute_key]) {
                                $option = ProductAttributeOption::find()
                                        ->where('attribute_id=:attribute_id AND code_app=:code_app', ['attribute_id' => $attribute['id'], ':code_app' => $item[$attribute_key]])
                                        ->one();
                                if (isset($option) && $option) {
                                    $modelAtt = ProductAttribute::findOne($option['attribute_id']);
                                    if ($modelAtt) {
                                        $keyR = count($attributeValue);
                                        $attributeValue[$keyR] = array();
                                        $attributeValue[$keyR]['id'] = $option['attribute_id'];
                                        $attributeValue[$keyR]['name'] = $modelAtt->name;
                                        $attributeValue[$keyR]['code'] = $modelAtt->code;
                                        $attributeValue[$keyR]['index_key'] = $option['index_key'];
                                        $attributeValue[$keyR]['value'] = $option['index_key'];
                                    }
                                    //
                                    $cus_field = 'cus_field' . $option['attribute_id'];
                                    $product->$cus_field = $option['index_key'];
                                }
                            }
                        }
                        if (!empty($attributeValue)) {
                            $attributeValue = json_encode($attributeValue);
                            $product->dynamic_field = $attributeValue;
                        }
                        if ($product->save()) {
                            $price = ClaProduct::getPriceProduct($product);
                            $product->price = $price;
                            if ($product->avatar_path && $product->avatar_name) {
                                $image = new \common\models\product\ProductImage();
                                $image->product_id = $product->id;
                                $image->path = $product->avatar_path;
                                $image->name = $product->avatar_name;
                                $image->save();
                                $product->avatar_id = $image->id;
                            }
                            if ($product->avatar2_path && $product->avatar2_name) {
                                $image2 = new \common\models\product\ProductImage();
                                $image2->product_id = $product->id;
                                $image2->path = $product->avatar2_path;
                                $image2->name = $product->avatar2_name;
                                $image2->save();
                                $product->avatar2_id = $image2->id;
                            }
                            $product->save();
                        }
                    }
                }
            }
        }
        $time_end = time();
        $total_time = $time_end - $time_start;
        echo "<pre>";
        print_r('Đã xong <br />');
        print_r('Tổng thời gian Import sản phẩm là: ' . $total_time);
        echo "</pre>";
        die();
    }

    public function actionGetAttribute() {
        echo "<pre>";
        print_r('DIE');
        echo "</pre>";
        die();
        $time_start = time();
        $data = AttributeMap::find()
                ->select('*')
                ->where('1=1')
                ->limit(1000)
                ->offset(1)
                ->asArray()
                ->all();
        $i = 0;
        foreach ($data as $item) {
            $i++;
            $modelOp = new \common\models\product\ProductAttributeOption();
            $modelOp->attribute_id = 28;
            $modelOp->value = $item['kodanhchoName'];
            $modelOp->sort_order = $i;
            $modelOp->ext = '';
            $modelOp->code_app = $item['kodanhchoID'];
            if ($modelOp->save()) {
                $modelOp->index_key = $modelOp->id;
                $modelOp->save();
            }
        }
        $time_end = time();
        $total_time = $time_end - $time_start;
        echo "<pre>";
        print_r('Đã xong <br />');
        print_r('Tổng thời gian thực hiện việc Import thuộc tính là: ' . $total_time);
        echo "</pre>";
        die();
    }

    public function actionGetQttg() {
        echo "<pre>";
        print_r('DIE');
        echo "</pre>";
        die();
        $data = \common\models\sync\Dmqttg::find()
                ->select('*')
                ->where('1=1')
                ->limit(1000)
                ->offset(0)
                ->asArray()
                ->all();
        foreach ($data as $item) {
            $model = new \common\models\product\ProductPriceFormula();
            $model->code_app = $item['QttgID'];
            $model->name = $item['QttgName'];
            $model->formula_product = $item['Quy_tac'];
            $model->formula_gold = $item['QT_AU'];
            $model->formula_fee = $item['QT_TC'];
            $model->formula_stone = $item['QT_TD'];
            $model->code_gold_parent = $item['ma_au_me'];
            $currency = \common\models\product\ProductCurrency::find()
                    ->where('code_app=:code_app', [':code_app' => $item['ma_au_me']])
                    ->one();
            if (isset($currency) && $currency) {
                $model->id_currency = $currency->id;
            }
            $model->status = $item['Status'];
            $model->description = $item['DienGiai'];
            $model->coefficient1 = $item['h1'];
            $model->coefficient2 = $item['h2'];
            $model->coefficient3 = $item['h3'];
            $model->coefficient4 = $item['h4'];
            $model->coefficient5 = $item['h5'];
            $model->coefficient6 = $item['h6'];
            $model->coefficient7 = $item['h7'];
            $model->coefficient8 = $item['h8'];
            $model->coefficient9 = $item['h9'];
            $model->coefficientm = $item['hm'];
            $model->coefficientx = $item['hx'];
            $model->save();
        }
        echo "<pre>";
        print_r('DONE');
        echo "</pre>";
        die();
    }

    public function actionGetTiente() {
        echo "<pre>";
        print_r('DIE');
        echo "</pre>";
        die();
        $data = \common\models\sync\Dmtiente::find()
                ->select('*')
                ->where('1=1')
                ->limit(1000)
                ->offset(0)
                ->asArray()
                ->all();

        foreach ($data as $item) {
            $model = new \common\models\product\ProductCurrency();
            $model->code_app = $item['tienteID'];
            $model->name = $item['tienteName'];
            $model->price_sell = $item['Gia_ban'];
            $model->price_buy = $item['Gia_mua'];
            $model->gold_yn = $item['vang_yn'];
            $model->money_yn = $item['tien_yn'];
            $model->save();
        }
        echo "<pre>";
        print_r('DONE');
        echo "</pre>";
        die();
    }

    public function actionGetMapPrice() {
        echo "<pre>";
        print_r('DIE');
        echo "</pre>";
        die();
        $data = \common\models\sync\Dmnhvt::find()
                ->select('*')
                ->where('1=1')
                ->limit(2000)
                ->offset(0)
                ->asArray()
                ->all();
        foreach ($data as $item) {
            $model = new \common\models\product\MapPriceFormula();
            $model->code_app = $item['Nhvt2ID'];
            $model->name = $item['Nhvt2Name'];
            $model->price_formula = $item['Fk_qttgID'];
            $model->save();
        }
        echo "<pre>";
        print_r('DONE');
        echo "</pre>";
        die();
    }

    public function actionGetImageError() {
        $data = Product::find()
                ->select('id, code, name, avatar_path, avatar_name')
                ->where('is_configurable=0')
                ->asArray()
                ->all();
        $images = [];
        $errors = [];
        $errors_name = [];
        $codes = [];
        foreach ($data as $item) {
            $img = \common\components\ClaHost::getImageHost() . $item['avatar_path'] . $item['avatar_name'];
            if (@getimagesize($img)) {
                $images[] = $img;
            } else {
                $errors[] = $img;
                $errors_name[] = $item['avatar_name'];
                $codes[] = isset($item['code']) ? $item['code'] : '';
            }
        }
        echo "<pre>";
        print_r($codes);
        echo "</pre>";
        die();
        echo "<pre>";
        print_r($errors_name);
        echo "</pre>";
        die();
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die();
    }

}
