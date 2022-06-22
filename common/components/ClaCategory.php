<?php

namespace common\components;

use Yii;

/*
 * @author hungtm
 * @date 09-03-2017
 * Class for create and show category
 * @editor Hatv
 * Add link to category
 */

class ClaCategory
{

    const CATEGORY_ROOT = 0;
    const CATEGORY_ = '';
    const CATEGORY_NEWS = 'news';
    const CATEGORY_PRODUCT = 'product';
    //
    const CATEGORY_KEY = 'cat';
    const CATEGORY_SPLIT = ' ';
    //
    const CATEGORY_STEP = 0;

    //
    protected $items = array(); // list category
    protected $relations = array(); // list category array('parent'=>'list children');
    protected $dbname = '';
    public $type = '';   // Type of category such as: news, video,...
    public $route = '';

    /**
     * construct
     */
    function __construct($options = array())
    {
        if (isset($options['type'])) {
            $this->type = $options['type'];
        }
        if (isset($options['create']) && $options['create'] === true) {
            $this->generateCategory();
        }
    }

    /**
     * Khởi tạo data chứa các category
     * @param type $options('selectFull')
     */
    function generateCategory($options = array())
    {
        $dbname = $this->getCategoryTable();
        //
        $data = array('items' => array(), 'relations' => array());
        //
        $dataread = (new \yii\db\Query())->select('*')
            ->from($dbname)
            ->where('status=:status', [':status' => ClaLid::STATUS_ACTIVED])
            ->orderBy('id')
            ->all();
        //
        foreach ($dataread as $menu_item) {
            $data['items'][$menu_item['id']] = $menu_item;
            $data['items'][$menu_item['id']]['link'] =  \yii\helpers\Url::to([$this->getRoute(), 'id' => $menu_item['id'], 'alias' => $menu_item['alias']]);
            $data['relations'][$menu_item['parent']][] = $menu_item['id'];
        }
        $this->items = $data['items'];
        $this->relations = $data['relations'];
    }

    /**
     * get category table in db
     */
    public function getCategoryTable()
    {
        if ($this->dbname == '') {
            switch ($this->type) {
                case self::CATEGORY_NEWS:
                    $this->dbname = 'news_category';
                    break;
                case self::CATEGORY_PRODUCT:
                    $this->dbname = 'product_category';
                    break;
                default:
                    $this->dbname = 'news_category';
            }
        }
        return $this->dbname;
    }

    /**
     * Get route
     */
    public function getRoute()
    {
        if (!$this->route) {
            switch ($this->type) {
                case self::CATEGORY_NEWS: {
                        $this->route = '/news/news/category';
                    }
                    break;
                case self::CATEGORY_PRODUCT: {
                        $this->route = '/product/product/category';
                    }
                    break;
                default:
                    $this->route = '/news/news/category';
            }
        }
        return $this->route;
    }

    /**
     * Get list categories item
     */
    public function getListItems()
    {
        return $this->items;
    }

    /**
     * Get list categories relations
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     *
     * create bread cumbs
     * @param type $id
     * @param type $save
     */
    public function createbreadcrumbs($id = 0, &$save = array())
    {
        if ($id != 0) {
            //add element to begin of array;
            array_unshift($save, $this->items[$id]);
            $this->createbreadcrumbs($this->items[$id]["parent"], $save);
        }
        return $save;
    }

    /**
     *
     * Save track (return track from root to select id)
     * @param type $id
     * @param type $savetrack
     */
    public function saveTrack($id, &$savetrack = array())
    {
        // echo "<pre>";
        // print_r($this->items);
        // die();
        if ($id != 0 && isset($this->items[$id]["id"])) {
            $savetrack[] = $this->items[$id]["id"];
            $this->saveTrack($this->items[$id]["parent"], $savetrack);
        }
        return $savetrack;
    }

    /**
     * Create option array
     */
    public function createOptionArray($parent_id = 0, $step = 0, &$arr = array('' => '|'))
    {
        $space = '';
        $space = str_repeat(' - ', $step * 2);
        $step++;
        if (isset($this->relations[$parent_id])) {
            foreach ($this->relations[$parent_id] as $item_id) {
                if ($parent_id == 0) {
                    $arr[''] = isset($arr['']) ? $arr[''] : '|';
                }
                $arr['' . $this->items[$item_id]["id"]] = (($parent_id == 0) ? "" : $space) . $this->items[$item_id]["cat_name"];
                $this->createOptionArray($item_id, $step, $arr);
            }
        }
        return $arr;
    }

    /**
     * Create option array
     */
    public function createArrayCategory($parent_id = 0, &$options = array())
    {
        $return = array();
        if (isset($this->relations[$parent_id])) {
            foreach ($this->relations[$parent_id] as $item_id) {
                $c_link = \yii\helpers\Url::to([$this->getRoute(), 'id' => $item_id, 'alias' => $this->items[$item_id]['alias']]);
                $return[$item_id] = $this->items[$item_id];
                $return[$item_id]['link'] = $c_link;
                $return[$item_id]['active'] = self::checkActive($c_link);
                //
                if ($return[$item_id]['active']) {
                    $savetrack = array();
                    $this->saveTrack($item_id, $savetrack);
                    foreach ($savetrack as $tid) {
                        $options['track'][$tid] = $tid;
                    }
                }
                //
                $return[$item_id]["children"] = $this->createArrayCategory($item_id, $options);
                // active parent
                if (isset($options['track'][$item_id])) {
                    $return[$item_id]['active'] = true;
                }
            }
        }
        return $return;
    }

    /**
     * get all chidren of id
     */
    public function getChildrens($id, &$data = array())
    {
        if (isset($this->relations[$id])) {
            foreach ($this->relations[$id] as $item_id) {
                array_push($data, $item_id);
                $this->getChildrens($item_id, $data);
            }
        }
        return $data;
    }

    /**
     * Lấy các con của cat
     * @param type $id
     */
    public function getChildren($id)
    {
        if (isset($this->relations[$id]))
            return $this->relations[$id];
        return array();
    }

    /**
     * Check has children
     */
    public function hasChildren($id)
    {
        if (isset($this->relations[$id]))
            return true;
        if (!count($this->relations) && Yii::app()->db->createCommand()->select('*')->from($this->getCategoryTable())->where('parent=' . $id)->limit(1)->queryRow())
            return true;
        return false;
    }

    /**
     * get category name
     * @param type $id
     */
    public function getCateName($id)
    {
        return isset($this->items[$id]["cat_name"]) ? $this->items[$id]["cat_name"] : '';
    }

    static function getCategoryType()
    {
        return array(
            self::CATEGORY_NEWS => Yii::t('news', 'news_category'),
            self::CATEGORY_PRODUCT => Yii::t('product', 'product_category'),
            self::CATEGORY_COURSE => Yii::t('course', 'edu_course_categories'),
            self::CATEGORY_EVENT => Yii::t('event', 'eve_event_categories'),
            self::CATEGORY_SERVICE => Yii::t('service', 'service_category'),
        );
    }

    /**
     * check url is active or not
     * @param type $url
     */
    static function checkActive($url)
    {
        $checkUrl = str_replace('/', '', $url);
        $currentUrl = str_replace('/', '', Yii::$app->request->getUrl());
        return ($checkUrl == $currentUrl || strpos($currentUrl, $checkUrl) !== false) ? true : false;
    }

    /**
     * return category info
     * @param type $category_id
     */
    function getItem($category_id = null)
    {
        if (isset($this->items[$category_id]))
            return $this->items[$category_id];
        return false;
    }

    /**
     * remove item
     * @param type $item_id
     */
    function removeItem($item_id)
    {
        if (isset($this->items[$item_id])) {
            $this->relations[$this->items[$item_id]["parent"]] = ClaArray::deleteWithValue($this->relations[$this->items[$item_id]["parent"]], $item_id);
            unset($this->items[$item_id]);
        }
    }

    /**
     * Kiểm tra xem danh mục có tồn tại hay không
     * @param type $id
     */
    function checkCatExist($id)
    {
        if (isset($this->items[$id]))
            return true;
        return false;
    }

    /**
     * return sub category and its info

     * @param type $id
     */
    function getSubCategory($id)
    {
        $children = $this->getChildren($id);
        $cats = array();
        $route = $this->getRoute();
        foreach ($children as $item_id) {
            $item = $this->getItem($item_id);
            if (!$item)
                continue;
            $cats[$item_id] = $item;
            $cats[$item_id]['link'] = Yii::app()->createUrl($route, array('id' => $item_id, 'alias' => $item['alias']));
        }
        return $cats;
    }

    /**
     * return sub category and its info

     * @param type $id
     */
    function getTrackCategory($id)
    {
        $track = $this->saveTrack($id);
        $track = array_reverse($track);
        $cats = array();
        $route = $this->getRoute();
        foreach ($track as $item_id) {
            $item = $this->getItem($item_id);
            if (!$item) {
                continue;
            }
            $cats[$item_id] = $item;
            $cats[$item_id]['link'] = \yii\helpers\Url::to([$route, 'id' => $item_id, 'alias' => $item['alias']]);
        }
        return $cats;
    }
}
