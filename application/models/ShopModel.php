<?php
/**
 * 门店管理模型
 */

class ShopModel extends CI_Model {
	// 返回数据
	public $returnRes;

	public function __construct(){
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false,
							'data'  => array()
							);
		$this->isAdmin = $this->isAdminUser($this->userInfo->role_id);
	}

	/**
	 * 添加门店
	 * @param array $reqData 请求数据
	 */
	public function addShop($reqData = array()){
		if (!isset($reqData['brandId']) || empty($reqData['brandId'])) return $this->lang->line('ERR_ADDSHOP_BRNAD_NAME');

		if (!isset($reqData['shopId']) || empty($reqData['shopId'])) return $this->lang->line('ERR_ADDSHOP_SHOP_NAME');

		if (!isset($reqData['floor']) || empty($reqData['floor'])) return $this->lang->line('ERR_ADDSHOP_FLOOR');

		$where = array(
				'tb_brand_id' => $reqData['brandId'],
				'tb_mall_id'  => $reqData['shopId'],
			);

		$queryRes = $this->db->get_where(tname('brand_mall'), $where)->result();

		if (count($queryRes) > 0) return $this->lang->line('ERR_ADDSHOP_EXISTS');

		$add = array(
				'id'           => makeUUID(),
				'tb_brand_id'  => $reqData['brandId'],
				'tb_mall_id'   => $reqData['shopId'],
				'create_time' => currentTime(),
				'update_time'  => currentTime(),
				'pic_url'      => 'uploadtemp/mall/default_shop.jpg',
				'thumb_url'    => 'uploadtemp/mall/default_shop_thumb.jpg',
				'address'      => $reqData['floor'],
			);

		$insertStatus = $this->db->insert(tname('brand_mall'), $add);

		return $insertStatus ? true : $this->lang->line('ERR_ADDSHOP_FAIL');
	}

	/**
	 * 删除门店
	 * @param string $shopId 门店id
	 */
	public function delShop($shopId = false){
		$where = array(
				'id' => $shopId,
			);

		return $this->db->where($where)->delete(tname('brand_mall'));
	}

	/**
	 * 搜索门店
	 * @param array $reqData 请求数据
	 * @param string $outType 输出类型
	 */
	public function searchShop($reqData = array(), $outType = 'html'){
		$where = array(
				'level'  => (int)$reqData['type'],
				'status' => 1,
			);

		$queryRes = $this->db->group_start()
							 ->like('name_zh', $reqData['name'])
							 ->or_like('address', $reqData['name'])
							 ->group_end()
							 ->get_where(tname('mall'), $where, 10)->result();

		if ($outType == 'html') {
			$htmlRes = '';
			foreach ($queryRes as $k => $v) {
				
				if ($k == 0) {
					$checked = 'checked';
				}else{
					$checked = '';
				}

				$html = '<input type="radio" name="shopId" id="shop_%s" value="%s" %s>&nbsp;&nbsp;<label for="shop_%s">%s(%s)</label><br>';
				$htmlRes .= sprintf($html, $v->id, $v->id, $checked, $v->id, $v->name_zh, $v->city_name.$v->address);
			}

			$queryRes = $htmlRes;
		}

		return $queryRes;

	}


	/**
	 * 获取门店详情
	 * @param string $shopId 门店id
	 */
	public function getShopDetail($shopId = false){
		$sql = "select 
					b.name_zh AS brandZH, 
					b.name_en AS brandEN, 
					b.id AS brandId,
					b.logo_url AS logo, 
					c.id AS mallId,
					c.name_zh AS mallName, 
					c.city_name AS cityName,
					c.tb_city_id AS cityId,
					c.district_name AS districtName,
					c.tb_district_id AS districtId,
					c.address AS address,
					a.address AS floor
					 
					from tb_brand_mall AS a 
				LEFT JOIN tb_brand AS b ON b.id = a.tb_brand_id 
				LEFT JOIN tb_mall AS c ON c.id = a.tb_mall_id
				WHERE a.id = '".$shopId."'";

		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes;
	}

	/**
	 * 获取商厦相关信息
	 *
	 */
	public function getShopMallInfo(){

	}

	/**
	 * 获取商场列表
	 * @param string $cityId 城市id
	 * @param string $districtId 区域id
	 */
	public function getMallList($cityId = false, $districtId = false){

		$where = array(
				'status' => 1,
				'level'  => 1,
			);

		if (isset($cityId) && !empty($cityId)) $where['tb_city_id'] = $cityId;

		if (isset($districtId) && !empty($districtId)) $where['tb_district_id'] = $districtId;

		$queryRes = $this->db->select('id, name_zh')->get_where(tname('mall'), $where)->result();

		return $queryRes;
	}

	/**
	 * 获取城市列表
	 *
	 */
	public function getCityList(){
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.CITY_LIST'));

		if ($cacheRes) return $cacheRes;

		$queryRes = $this->db->select('id AS cityId, name_zh AS cityName')->get(tname('city'))->result();

		if (count($queryRes)) $this->cache->save(config_item('NORMAL_CACHE.CITY_LIST'), $queryRes);

		return $queryRes;
	}

	/**
	 * 获取商圈列表
	 * @param string $cityId 城市id
	 */
	public function getAreaList($cityId = false){

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.AREA_LIST').$cityId);

		if ($cacheRes) return $cacheRes;

		$where = array(
					'tb_city_id' => is_string($cityId) ? $cityId : '',
				);

		$select = "tb_trade_area_id AS areaId, trade_area_name AS areaName";

		$queryRes = $this->db->select($select)->group_by('tb_trade_area_id')->get_where(tname('mall'), $where)->result();

		if (count($queryRes)) $this->cache->save(config_item('NORMAL_CACHE.AREA_LIST').$cityId, $queryRes);

		return $queryRes;
	}

	/**
	 * 获取门店列表
	 * @param string $where 查询条件
	 * @param string $order 排序条件
	 * @param int $pageNum 页码
	 * @param int $pageCount 条数
	 */
	public function getShopList($where = NULL, $order = NULL, $pageNum = 1, $pageCount = 25){

		$pageNum = ($pageNum - 1) * $pageCount;

		$limit = ' LIMIT '.$pageNum.','.$pageCount;

		$field = " 	
					a.id AS id,
					c.id AS brandId,
					b.id AS mallId,
					CONCAT(c.name_en, ' ', c.name_zh) AS brandName,
					c.logo_url AS logoUrl,
					b.name_zh AS mallName,
					b.address AS address,
					b.district_name AS areaName,
					b.city_name AS cityName,
					a.address AS floor";

		$totalField = ' COUNT(*) AS total ';

		$sql = "SELECT
					%s
				 FROM tb_brand_mall AS a
				LEFT JOIN tb_mall AS b ON b.id = a.tb_mall_id
				LEFT JOIN tb_brand AS c ON c.id = a.tb_brand_id
				%s
				%s
				%s ";

		$where = $this->_checkUserBrand($where);

		if (empty($where)) {
			return $this->_return(null, array('list' => array(), 'total' => 0), false);
		}

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, $order, ''))->first_row();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $order, $limit))->result();

		$this->returnRes = array(
				'error' => false,
				'msg'   => false,
				'data'  => array(
					'list'  => $queryRes,
					'total' => $queryTotal->total,
				),
			);

		return $this->returnRes;
	}

	/**
	 * 获取门店列表格式化为表单
	 *
	 */
	public function getShopListWithForm(){

		$shopList = $this->cache->get(config_item('USER_CACHE.SHOPLIST').$this->userInfo->user_id);
		if ($shopList) return $shopList;

		$sql = "SELECT
					c.id AS mallID,
					CONCAT(c.city_name, c.trade_area_name, c.name_zh, c.address) AS shopName
				 FROM ".tname('brand')." AS a
				LEFT JOIN ".tname('brand_mall')." AS b ON b.tb_brand_id = a.id
				LEFT JOIN ".tname('mall')." AS c ON c.id = b.tb_mall_id
				WHERE a.id = '".$this->userInfo->brand_id."'";

		$queryRes = $this->db->query($sql)->result();

		if (count($queryRes)) {
			$this->cache->save(config_item('USER_CACHE.SHOPLIST').$this->userInfo->user_id, $queryRes, config_item('USER_CACHE.DEFAULT_EXPIRETIME'));
		}

		return $queryRes;
	}

	/**
	 * 添加店长
	 * @param array $managerData 店长信息
	 */
	public function addShopManager($managerData = array()){
		if (count($managerData) <= 0) {
			return $this->_return($this->lang->line('ERR_ADD_MANAGER_FAIL'));
		}

		$insert = array(
				'user_id'      => makeUUID(),
				'role_id'      => config_item('SHOPMANAGER_ROLEID'),
				'name'         => $managerData['managerName'],
				'passwd'       => md5($managerData['passwd']),
				'status'       => 1,
				'created_time' => currentTime(),
			);

		$roleUserRes = $this->db->insert(tname('qcgj_role_user'), $insert);

		if (!$roleUserRes) return $this->_return($this->lang->line('ERR_ADD_MANAGER_FAIL'));

		$insertMall = array(
				'user_id'  => $insert['user_id'],
				'brand_id' => $this->userInfo->brand_id,
				'mall_id'  => $managerData['mallID'],
			);

		$mallRes = $this->db->insert(tname('qcgj_role_brand_mall'), $insertMall);

		if (!$mallRes) {

			$where = array(
					'user_id' => $insert['user_id'],
				);
			$this->db->delete(tname('qcgj_role_user'), $where);

			return $this->_return($this->lang->line('ERR_ADD_MANAGER_FAIL'));
		}

		return $this->_return(NULL, array(), false);
	}


	/**
	 * 检测店长名是否存在
	 * @param string $managerName 店长名
	 */
	public function existsManagerName($managerName = false){

		$where = array(
				'name' => $managerName,
			);

		$queryRes = $this->db->get_where(tname('qcgj_role_user'), $where)->result_array();

		return count($queryRes) > 0 ? true : false;

	}

	/**
	 * 获取店长列表
	 * @param int $pageNum 页数
	 * @param int $pageCount 条数
	 */
	public function getManagerList($pageNum = 1, $pageCount = 15){
		$pageNum = ($pageNum - 1) * $pageCount;

		$limit = " LIMIT ".$pageNum.",".$pageCount;
		$field = "	a.user_id,
					b.name AS userName,
					c.name_zh AS mallName,
					c.address,
					c.district_name AS areaName,
					c.city_name AS cityName ";

		if ($this->isAdmin) {
			$sql = "SELECT %s
						 FROM ".tname('qcgj_role_brand_mall')." AS a
						LEFT JOIN ".tname('qcgj_role_user')." AS b ON b.user_id = a.user_id
						LEFT JOIN ".tname('mall')." AS c ON c.id = a.mall_id
						WHERE a.brand_id != ''
							AND a.mall_id != '' ";
		}else{
			$sql = "SELECT %s
						 FROM ".tname('qcgj_role_brand_mall')." AS a
						LEFT JOIN ".tname('qcgj_role_user')." AS b ON b.user_id = a.user_id
						LEFT JOIN ".tname('mall')." AS c ON c.id = a.mall_id
						WHERE a.brand_id = '".$this->userInfo->brand_id."'
						AND a.user_id != '".$this->userInfo->user_id."'
						AND a.mall_id != '' ";
		}


		$queryRes = $this->db->query(sprintf($sql, $field).$limit)->result();

		$queryTotal = $this->db->query(sprintf($sql, " COUNT(*) AS total "))->first_row();

		$this->returnRes = array(
				'error' => false,
				'msg'   => false,
				'data'  => array(
					'list'  => $queryRes,
					'page' => $this->setPagination(site_url('Shop/managerList'), $queryTotal->total),
				),
			);

		return $this->returnRes;

	}

	/**
	 * 设置分页
	 */
	public function setPagination($url = false, $total = 1, $prePage = 15){
		$pageConfig = array(
				'base_url'   => $url,
				'total_rows' => $total,
				'pre_page'   => $prePage,
			);

		$this->pagination->initialize($pageConfig);

		return $this->pagination->create_links();
	}

	/**
	 * 设置店铺查询条件
	 * @param string $where 查询条件
	 */
	private function _checkUserBrand($where = null){
		// 判断超级管理员
		if ($this->isAdmin) {
			return $where;
		}

		if (isset($this->userInfo->brand_id) && !empty($this->userInfo->brand_id)) {
			$brandEmpty = true;
			$where .= " AND c.id = '".$this->userInfo->brand_id."' ";
		}

		if (isset($this->userInfo->mall_id) && !empty($this->userInfo->mall_id)) {
			$mallEmpty = true;
			$where .= " AND b.id = '".$this->userInfo->mall_id."' ";
		}

		if (!isset($mallEmpty) && !isset($brandEmpty)) {
			return null;
		}

		return $where;
	}

	/**
	 * model 返回内容
	 * @param string $msg 返回信息内容
	 * @param array $data 数据内容
	 * @param bool $error 返回状态
	 */
	private function _return($msg = false, $data = array(), $error = true){

		$this->returnRes = array(
					'error' => $error,
					'msg'   => $msg,
					'data'  => $data,
			);

		return $this->returnRes;
	}
}
