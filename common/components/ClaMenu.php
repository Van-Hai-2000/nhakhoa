<?php

namespace common\components;

use Yii;
use common\models\menu\Menu;
use yii\helpers\Url;
use yii\db\Query;

/*
 * Class for create and show menu
 */

class ClaMenu {

    //
    const MENU_ROOT = 0;
    const MENU_BEGIN_STEP = 0;

    //
    public $items = array(); // list category
    public $relations = array(); // list menu relations array('parent'=>'list children');
    public $type = '';   // Type of category such as: news, video,...
    public $route = '';
    public $group_id = 0;

    /**
     * construct
     */
    function __construct($options = array()) {
        if (isset($options['group_id'])) {
            $this->group_id = $options['group_id'];
        }
        if (isset($options['create']) && $options['create'] === true) {
            $this->generateMenu();
        }
    }

    //Khởi tạo data chứa các menu
    function generateMenu() {
        if (!$this->group_id) {
            return;
        }
        // get cache before
        $cache = Yii::$app->cache;
        $key_menu_group = ClaLid::KEY_MENU . $this->group_id;
        // $dataread = $cache->get($key_menu_group);
        //
        // if (empty($dataread) || $dataread === false) {

            // PROCESS DATA
            $data = array('items' => array(), 'relations' => array());
            //
            $conditions = 'group_id=:group_id AND status=:status';
            $params = [
                ':group_id' => $this->group_id,
                ':status' => ClaLid::STATUS_ACTIVED
            ];
            //
            $dataread = (new Query())->select('*')
                    ->from('menu')
                    ->where($conditions, $params)
                    ->orderBy('order ASC')
                    ->all();
            // $cache->set($key_menu_group, $dataread);
        // }
        //
        if ($dataread) {
            foreach ($dataread as $menu) {
                $data['items'][$menu['id']] = $menu;
                //
                if ($menu['linkto'] == Menu::LINKTO_INNER) {
                    // Link trong website
                    $data['items'][$menu['id']]['link'] = Yii::$app->homeUrl;
                    if ($menu['basepath'] && $menu['pathparams']) {
                        $pathparams = json_decode($menu['pathparams'], true);
                        $params = array_merge([$menu['basepath']], $pathparams);
                        $data['items'][$menu['id']]['link'] = Yii::$app->urlManager->createUrl($params);
                    } else if (!$menu['basepath'] && !$menu['pathparams']) {
                        $data['items'][$menu['id']]['link'] = 'javascript:void(0)';
                    }
                } else {
                    // Link ngoài website
                    $data['items'][$menu['id']]['link'] = 'javascript:void(0)';
                    if ($menu['link']) {
                        $data['items'][$menu['id']]['link'] = $menu['link'];
                    }
                }
                //
                $data['relations'][$menu['parent']][] = $menu['id'];
            }
        }

        // END PROCESS
        $this->items = $data['items'];
        $this->relations = $data['relations'];
    }

    // Đệ quy tạo ra một menu
    public function createMenu($parent_id, &$options = array()) {
        $return = array();
        if (isset($this->relations[$parent_id])) {
            $currenturl = Yii::$app->request->getUrl();
            $fullurl = Yii::$app->request->getHostInfo() . $currenturl;
            foreach ($this->relations[$parent_id] as $item_id) {
                $m_link = $this->items[$item_id]['link'];
                $return[$item_id]['name'] = $this->items[$item_id]['name'];
                $return[$item_id]['description'] = $this->items[$item_id]['description'];
                $return[$item_id]['icon_path'] = $this->items[$item_id]['icon_path'];
                $return[$item_id]['icon_name'] = $this->items[$item_id]['icon_name'];
                $return[$item_id]['avatar_path'] = $this->items[$item_id]['avatar_path'];
                $return[$item_id]['avatar_name'] = $this->items[$item_id]['avatar_name'];
                $return[$item_id]['order'] = $this->items[$item_id]['order'];
                $return[$item_id]['link'] = $m_link;
                $return[$item_id]['target'] = $this->items[$item_id]['target'] ? 'target="_blank"' : '';
                //
                $return[$item_id]['active'] = false;
                //
                if ($this->items[$item_id]['linkto'] == Menu::LINKTO_OUTER) {
                    $return[$item_id]['active'] = ($m_link == $fullurl || $m_link == $currenturl) ? true : false;
                } else {
                    $return[$item_id]['active'] = Menu::checkActive($m_link, array('currenturl' => $currenturl));
                }
                //
                if ($return[$item_id]['active']) {
                    $savetrack = array();
                    $this->saveTrack($item_id, $savetrack);
                    foreach ($savetrack as $tid) {
                        $options['track'][$tid] = $tid;
                    }
                }
                //
                $return[$item_id]['items'] = $this->createMenu($item_id, $options);
                // active parent
                if (isset($options['track'][$item_id])) {
                    $return[$item_id]['active'] = true;
                }
            }
        }
        return $return;
    }

    /**
     * 
     * Save track
     * @param type $id
     * @param type $savetrack
     */
    public function saveTrack($id, &$savetrack = array()) {
        if ($id != 0 && isset($this->items[$id]["id"])) {
            $savetrack[] = $this->items[$id]["id"];
            $this->saveTrack($this->items[$id]["parent"], $savetrack);
        }
        return $savetrack;
    }

}
