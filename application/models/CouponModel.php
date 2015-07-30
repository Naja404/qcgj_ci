<?php
/**
 * 折扣/优惠券管理模型
 */

class CouponModel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 获取店铺列表
	 *
	 */
	public function getShopList(){

		if (!empty($this->userInfo->mall_id) && !empty($this->userInfo->brand_id)) {

			$fields = "id AS mallID,
						address,
						name_zh AS mallName,
						trade_area_name AS areaName,
						city_name AS cityName";
			$where = array('id' => $this->userInfo->mall_id);
			$queryRes = $this->db->select($fields)->get_where(tname('mall'), $where)->result_array();

		}

		if (empty($this->userInfo->mall_id) && !empty($this->userInfo->brand_id)) {
			$sql = "SELECT
						b.id AS mallID,
						b.address,
						b.name_zh AS mallName,
						b.trade_area_name AS areaName,
						b.city_name AS cityName
						 FROM ".tname('brand_mall')." AS a
						LEFT JOIN ".tname('mall')." AS b ON b.id = a.tb_mall_id
						WHERE a.tb_brand_id = '".$this->userInfo->brand_id."' ORDER BY b.city_name ";
			$queryRes = $this->db->query($sql)->result_array();
		}

		$cityList = $cityName = $areaName = array();
		$bjAreaList = $shAreaList = $gzAreaList = '';

		foreach ($queryRes as $k) {
			if (!in_array($k['areaName'], $areaName)) {
				array_push($areaName, $k['areaName']);
			}

			if (!in_array($k['cityName'], $cityName)) {
				array_push($cityName, $k['cityName']);
			}

			$optionHTML = '<option value="'.$k['areaName'].'">'.$k['areaName'].'</option>';

			switch ($k['cityName']) {
				case '北京':
					$bjAreaList .= $optionHTML;
					break;
				case '上海':
					$shAreaList .= $optionHTML;
					break;
				case '广州':
					$gzAreaList .= $optionHTML;
					break;
				default:
					break;
			}

		}

		foreach ($this->lang->line('SELECT_CITY_LIST') as $k => $v) {
			if (in_array($v['name'], $cityName)) {
				array_push($cityList, $v);
			}
		}

		$this->returnRes = array(
				'error' => false,
				'data'  => array(
						'list'       => $queryRes,
						'cityList'	 => $cityList,
						'areaList'   => $areaName,
						'bjAreaList' => $bjAreaList,
						'shAreaList' => $shAreaList,
						'gzAreaList' => $gzAreaList,
					),
			);

		return $this->returnRes;

	}
}
