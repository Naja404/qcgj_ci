<?php
/**
 * 品牌管理模型
 */

class BrandModel extends CI_Model {
	// 返回数据
	public $returnRes;

	public function __construct(){
		$this->returnRes = array(
							'error' => true, // true=有错误, false=正确
							'msg'   => false, 
							'data'  => array()
							);
	}

	/**
	 * 根据id获取店铺信息
	 * @param string $shopId 店铺id
	 */
	public function getShopInfo($shopId = false){
		$sql = "SELECT 
					CONCAT(b.name_en, '_', b.name_zh) AS brandName,
					b.id AS brandId,
					c.name_en AS shopNameEn,
					c.name_zh AS shopNameZh,
					c.name_py AS shopNamePy,
					c.name_short AS shopNameShort,
					a.branch_name AS branchName,
					a.pic_url AS shopPic,
					c.level AS shopType,
					c.tel AS tel,
					c.address AS address,
					c.tb_city_id AS cityId,
					c.tb_district_id AS districtId,
					c.longitude AS shopLng,
					c.latitude AS shopLat,
					a.open_time AS openTime,
					a.close_time AS closeTime,
					a.description AS description,
					a.address AS shopFloor,
					c.level AS shopTyp
					FROM tb_brand_mall AS a 
					LEFT JOIN tb_brand AS b ON b.id = a.tb_brand_id
					LEFT JOIN tb_mall AS c ON c.id = a.tb_mall_id
					WHERE a.id = '".$shopId."'
					ORDER BY c.update_time DESC";
		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes;
	}

	/**
	 * 获取品牌列表
	 * @param string $where 查询条件
	 * @param int $p 页数
	 */
	public function getBrandList($where = NULL, $p = 1){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " 	id, 
					name_en, 
					name_zh, 
					logo_url, 
					create_time, 
					update_time, 
					LEFT(description, 50) AS summary, 
					oper ";

		$sql = "SELECT %s FROM ".tname('brand')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('Brand/listView'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	public function getShopList($where = NULL, $p = 1){
		
		$limit = "LIMIT ".page($p, 25);

		$countSql = "SELECT COUNT(*) AS total FROM ".tname('brand_mall');

		$queryTotal = $this->db->query($countSql)->first_row();

		$pagination = $this->setPagination(site_url('Brand/shopList'), $queryTotal->total, 25);

		$sql = "SELECT 
					a.id,
					b.name_zh AS brandName,
					b.name_en AS brandNameEn,
					c.name_zh AS shopName,
					a.branch_name AS branchName,
					a.pic_url AS shopPic,
					c.tel AS tel,
					c.district_name AS district,
					c.address AS address,
					c.level,
					c.update_time
					FROM tb_brand_mall AS a 
					LEFT JOIN tb_brand AS b ON b.id = a.tb_brand_id
					LEFT JOIN tb_mall AS c ON c.id = a.tb_mall_id
					WHERE c.address != ''
					ORDER BY c.update_time DESC ".$limit;

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;	
	}

	/**
	 * 获取商场/门店列表
	 * @param string $where 查询条件
	 * @param int $p 页数
	 */
	public function getShopList_bak($where = NULL, $p = 1){
		$limit = "LIMIT ".page($p, 25);
		
		$field = " 	id, 
					name_zh, 
					CONCAT(city_name, district_name) AS city,
					pic_url, 
					LEFT(description, 50) AS summary, 
					address";

		$sql = "SELECT %s FROM ".tname('mall')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('Brand/shopList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	/**
	 * 删除品牌
	 * @param string $brandId 品牌id
	 */
	public function delBrand($brandId = false){
		
		$where = array('id' => $brandId);

		return $this->db->delete(tname('brand'), $where);
	}

	/**
	 * 删除商场/门店
	 * @param string $shopId 商场/门店id
	 */
	public function delShop($shopId = false){
		$where = array('id' => $shopId);

		return $this->db->delete(tname('mall'), $where);
	}

	/**
	 * 获取品牌分类
	 *
	 */
	public function getBrandCategory(){
		$where = array(
				'level' => 1,
			);

		$queryRes = $this->db->select('id, name')->get_where(tname('category'), $where)->result();

		return $queryRes;
	}

	/**
	 * 获取品牌风格
	 *
	 */
	public function getBrandStyle(){

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_STYLE'));

		if (!empty($cacheRes) && count($cacheRes)) return $cacheRes;
		
		$where = array(
				'level' => 1,
			);

		$styleRes = $this->db->select('name, id')->get_where(tname('style'), $where)->result();

		if (count($styleRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_STYLE'), $styleRes);

		return $styleRes;
	}

	/**
	 * 获取品牌年龄
	 *
	 */
	public function getBrandAge(){
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_AGE'));

		if (!empty($cacheRes) && count($cacheRes) > 0) return $cacheRes;

		$ageRes = $this->db->select('name, id')->get(tname('age'))->result();

		if (count($ageRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_AGE'), $ageRes);

		return $ageRes;
	}

	/**
	 * 获取品牌消费金额
	 *
	 */
	public function getBrandPrice(){
		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_PRICE'));

		if (!empty($cacheRes) && count($cacheRes) > 0) return $cacheRes;

		$priceRes = $this->db->select('name, id')->get(tname('price'))->result();

		if (count($priceRes)) $this->cache->save(config_item('NORMAL_CACHE.BRAND_PRICE'), $priceRes);

		return $priceRes;
	}

	/**
	 * 搜索品牌
	 * @param string $brandName 品牌名
	 */
	public function searchBrand($brandName = false){
		if (!$brandName) return false;

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.SEARCH_BRAND_LIST').md5($brandName));
		
		if ($cacheRes) return $cacheRes; 

		$list = $this->db->select('id, name_zh, name_en')
						 ->like('name_zh', $brandName)
						 ->or_like('name_en', $brandName)
						 ->order_by('name_en, name_zh ASC')
						 ->limit(20)
						 ->get(tname('brand'))
						 ->result();

		$returnList = array();

		foreach ($list as $k => $v) {
			$tmp = array(
					'label' => $v->name_en.'_'.$v->name_zh,
					'id' => $v->id,
				);
			array_push($returnList, $tmp);
		}

		if (count($returnList)) $this->cache->save(config_item('NORMAL_CACHE.SEARCH_BRAND_LIST').md5($brandName), $returnList, 3600); 

		return $returnList;
	}

	/**
	 * 搜索地址
	 * @param string $address 品牌名
	 */
	public function searchAddress($address = false){
		if (!$address) return false;

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.SEARCH_ADDRESS_LIST').md5($address));
		
		if ($cacheRes) return $cacheRes; 

		$list = $this->db->select('id, name_zh, address')
						 ->like('address', $address)
						 ->order_by('address ASC')
						 ->limit(20)
						 ->get(tname('mall'))
						 ->result();

		$returnList = array();

		foreach ($list as $k => $v) {
			$tmp = array(
					'label' => $v->address.'('.$v->name_zh.')',
					'value' => $v->address,
					'id' => $v->id,
				);
			array_push($returnList, $tmp);
		}

		if (count($returnList)) $this->cache->save(config_item('NORMAL_CACHE.SEARCH_ADDRESS_LIST').md5($address), $returnList, 3600); 

		return $returnList;
	}

	/**
	 * 搜索商场/店铺
	 * @param string $mall 商场/店铺名称
	 */
	public function searchMall($mall = false){
		if (empty($mall)) return array();

		$queryRes = $this->db->select('id, name_zh, address, city_name')
							 ->like('name_zh', $mall)
							 ->get(tname('mall'))
							 ->result();
		$returnRes = array();
		
		foreach ($queryRes as $k => $v) {
			$tmp = array(
					'label' => $v->name_zh.'('.$v->city_name.$v->address.')',
					'id'    => $v->id,
				);
			array_push($returnRes, $tmp);
		}

		return $returnRes;
	}

	/**
	 * 添加品牌
	 * @param array $reqData 品牌数据内容
	 */
	public function addBrand($reqData = array()){

		$brand = array(
				'id'          => makeUUID(),
				'name_zh'     => $reqData['nameZh'],
				'name_en'     => $reqData['nameEn'],
				// 'logo_url' => $reqData['logo_url'],
				// 'pic_url'  => $reqData['pic_url'],
				'create_time' => currentTime(),
				'update_time' => currentTime(),
				'description' => $reqData['summary'],
				'tb_age_id'   => isset($reqData['age']) ? $reqData['age'] : '',
				'tb_price_id' => isset($reqData['price']) ? $reqData['price'] : '',
				'oper'        => $this->userInfo->user_id,
			);
		
		$insertRes = $this->db->insert(tname('brand'), $brand);

		if(!$insertRes) return $this->lang->line('ERR_ADD_BRAND_FAILURE');

		// 添加品牌分类
		if (isset($reqData['category']) && count($reqData['category'])) {
			foreach ($reqData['category'] as $k => $v) {
				$category = array(
						'id'             => makeUUID(),
						'create_time'    => currentTime(),
						'update_time'    => currentTime(),
						'tb_brand_id'    => $brand['id'],
						'tb_category_id' => $v,
					);

				$this->db->insert(tname('brand_category'), $category);
			}
		} 

		// 添加品牌店铺
		if (isset($reqData['mallId']) && count($reqData['mallId'])) {
			foreach ($reqData['mallId'] as $k => $v) {
				
				$mall = array(
						'id'		  => makeUUID(),
						'tb_brand_id' => $brand['id'],
						'tb_mall_id'  => $v,
						'create_time' => currentTime(),
						'update_time' => currentTime(),
						'address'     => $reqData['floor'][$k],
					);

				$this->db->insert(tname('brand_mall'), $mall);
			}
		}

		// 添加风格
		if (isset($reqData['style']) && count($reqData['style'])) {
			foreach ($reqData['style'] as $k => $v) {
				$style = array(
						'id' => makeUUID(),
						'create_time' => currentTime(),
						'update_time' => currentTime(),
						'tb_brand_id' => $brand['id'],
						'tb_style_id' => $v,
					);	

				$this->db->insert(tname('brand_style'), $style);
			}

		}

		return true;
	}

	/**
	 * 添加门店
	 * @param array $reqData 门店数据内容
	 */
	public function addShop($reqData = array()){

		// 判断门店类型 1.商场 2.街边店
		if ($reqData['shopType'] == 1) {
			return $this->_addBranchShop($reqData);
		}

		$shop = array(
				'id'             => makeUUID(),
				'name_en'        => $reqData['shopNameEN'],
				'name_zh'        => $reqData['shopNameZH'],
				'name_py'        => $reqData['shopNamePY'],
				'name_short'     => $reqData['shopNameShort'],
				'address'        => $reqData['shopAddress'],
				'create_time'	 => currentTime(),
				'update_time'	 => currentTime(),
				'longitude'		 => $reqData['shopLng'],
				'latitude'		 => $reqData['shopLat'],
				'open_time'      => $reqData['shopOpenTime'],
				'close_time'     => $reqData['shopCloseTime'],
				'tb_district_id' => $reqData['shopDistrict'],
				'district_name'	 => $this->getDistrictNameById($reqData['shopDistrict']),
				'tb_city_id'     => $reqData['shopCity'],
				'city_name'      => $this->getCityNameById($reqData['shopCity']),
				'tel'            => $reqData['shopTel'],
				'description'    => $reqData['shopDescription'],
				'level'          => $reqData['shopType'],
			);

		$insertRes = $this->db->insert(tname('mall'), $shop);

		if ($insertRes) {
			
			$reqData['shopAddressId'] = $shop['id'];
			
			$this->_addBranchShop($reqData);
		}

		return $insertRes ? true : $this->lang->line('ERR_ADD_FAILURE');
	}

	/**
	 * 城市列表
	 *
	 */
	public function getCityList(){
		
		$cityRes = $this->db->select('id, name_zh AS name, city_code')->get(tname('city'))->result();

		return $cityRes;
	}

	/**
	 * 获取城市区域列表
	 * @param string $cityId 城市id
	 */
	public function getDistrictList($cityId = false){

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.BRAND_DISTRICT').$cityId);

		if (!empty($cacheRes) && count($cacheRes)) return $cacheRes;
		
		$otherCityId = array('391db7b8fdd211e3b2bf00163e000dce', 'bd21203d001c11e4b2bf00163e000dce');

		if (in_array($cityId, $otherCityId)) {
			$sql = "SELECT c.id, c.name FROM ".tname('district')." AS c WHERE c.city_code IN (SELECT code FROM ".tname('city_all')." AS b WHERE b.province_code = (SELECT a.city_code FROM ".tname('city')." AS a WHERE a.id = '".$cityId."'))";
		}else{
			$sql = "SELECT c.id, c.name FROM ".tname('district')." AS c WHERE c.city_code = (SELECT a.city_code FROM ".tname('city')." AS a WHERE a.id = '".$cityId."')";
		}

		$districtList = $this->db->query($sql)->result();

		if (count($districtList) > 0) $this->cache->save(config_item('NORMAL_CACHE.BRAND_DISTRICT').$cityId, $districtList); 

		return $districtList;
	}

	/**
	 * 获取商场列表
	 * @param string $cityId 城市id
	 */
	public function getMallList($cityId = false, $format = 'result'){
		
		$where = array(
				'tb_city_id' => $cityId,
			);
		
		$queryRes = $this->db->select('id, name_zh, address, longitude, latitude')->get_where(tname('mall'), $where)->result();

		$html = '';
		foreach ($queryRes as $k => $v) {
			$html .= '<option value="'.$v->id.'">'.$v->name_zh.'('.$v->address.')'.'</option>';
		}

		return $format == 'result' ? $queryRes : $html;
	}

	/**
	 * 根据id获取城市区域名
	 * @param string $districtId 城市区域id
	 */
	public function getDistrictNameById($districtId = false){
		$where = array(
				'id' => $districtId
			);
		
		$cityRes = $this->db->select('name')->get_where(tname('district'), $where)->first_row();

		return $cityRes->name;

	}

	/**
	 * 根据id获取城市名
	 * @param string $cityId 城市id
	 */
	public function getCityNameById($cityId = false){

		$cacheRes = $this->cache->get(config_item('NORMAL_CACHE.CITY_NAME'));
		
		if (isset($cacheRes[$cityId])) return $cacheRes[$cityId];
		
		$cityList = $this->db->get(tname('city'))->result();

		$cityArr = array();
		foreach ($cityList as $k => $v) {
			$cityArr[$v->id] = $v->name_zh;
		}

		$this->cache->save(config_item('NORMAL_CACHE.CITY_NAME'), $cityArr);

		if (isset($cityArr[$cityId])) return $cityArr[$cityId];

		return false;
	}

	/**
	 * 根据id获取城市名
	 * @param string $cityId 城市id
	 */
	public function getCityNameById_bak($cityId = false){
		$where = array(
				'id' => $cityId
			);
		
		$cityRes = $this->db->select('name_zh')->get_where(tname('city'), $where)->first_row();

		return $cityRes->name_zh;

	}

	/**
	 * 添加品牌验证
	 * @param array $validateRule 验证规则
	 * @param arary $reqData 待验证数据
	 */
	public function validateAddBrand($validateRule = array(), $reqData = array()){
		$this->form_validation->set_rules($validateRule);

		if (!$this->form_validation->run()) return validation_errors();

		if (!isset($reqData['category']) || count($reqData['category']) <= 0) return $this->lang->line('ERR_CATEGORY'); 

		if (!isset($reqData['mallId']) || count($reqData['mallId']) <= 0) return $this->lang->line('ERR_MALL'); 

		// 验证品牌名是否存在
		if ($this->existsBrandName($reqData['nameZh'], $reqData['nameEn'])) return $this->lang->line('ERR_EXISTS_BRAND_NAME');

		return true;
	}

	/**
	 * 添加店铺验证
	 * @param array $validateRuel 验证规则
	 * @param array $reqData 待验证数据
	 */
	public function validateAddShop($validateRule = array(), $reqData = array()){

		// 验证品牌
		if (!$this->existsBrandById($reqData['shopBrandName'], $reqData['shopBrandId'])) return $this->lang->line('ERR_SHOP_BRANDNAME');

		$this->form_validation->set_rules($validateRule);

		if (!$this->form_validation->run()) return validation_errors();
		
		// if ($this->existsShopName($reqData['shopNameZH'], $reqData['shopNameEN'])) return $this->lang->line('ERR_EXISTS_SHOP_NAME');

		// 判断门店类型
		if ($reqData['shopType'] == 1) { // 1.商场 2.街边店
			if (!$this->existsShopById($reqData['shopAddress'], $reqData['shopAddressId'])) return $this->lang->line('ERR_SHOP_TYPE_MALL');
		}

		return true;
	}

	/**
	 * 是否存在商场/门店名
	 * @param string $nameZh 商场/门店中文名
	 * @param string $nameEn 商场/门店英文名
	 */
	public function existsShopName($nameZh = false, $nameEn = false){
		$where = " name_zh = '".$nameZh."' ";

		if (!empty($nameEn)) $where .= " AND name_en = '".$nameEn."'";

		$sql = "SELECT COUNT(*) AS total FROM ".tname('mall')." WHERE ".$where;

		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes->total > 0 ? true : false;
	}

	/**
	 * 门店信息是否存在
	 * @param string $address 门店地址
	 * @param string $addressId 门店id
	 */
	public function existsShopById($address = false, $addressId = false){
		$where = array(
				'address' => $address,
				'id'      => $addressId,
			);

		$queryRes = $this->db->get_where(tname('mall'), $where)->result();

		return count($queryRes) > 0 ? true : false;
	}

	/**
	 * 品牌信息是否存在
	 * @param string  $brandName 品牌名
	 * @param string $brandId 品牌id
	 */
	public function existsBrandById($brandName = false, $brandId = false){
		$brandName = explode('_', $brandName);

		$where = array(
				'id'      => $brandId,
				'name_zh' => $brandName[1],
				'name_en' => $brandName[0],
			);

		$queryRes = $this->db->get_where(tname('brand'), $where)->result();

		return count($queryRes) > 0 ? true : false;
	}

	/**
	 * 是否存在品牌
	 * @param string $nameZh 品牌中文名
	 * @param string $nameEn 品牌英文名
	 */
	public function existsBrandName($nameZh = false, $nameEn = false){
		
		$where = "name_zh = '".$nameZh."' ";
		
		if (!empty($nameEn)) $where .= " AND name_en = '".$nameEn."'";

		$sql = "SELECT COUNT(*) AS total FROM ".tname('brand')." WHERE ".$where;

		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes->total > 0 ? true : false;
	}

	/**
	 * 检测是否有权限编辑店铺
	 * @param string $shopId 店铺id
	 */
	public function checkEditShop($shopId = false){
		$where = array(
				'id' => $shopId,
			);

		$queryRes = $this->db->get_where(tname('brand_mall'), $where)->result();

		return count($queryRes) == 1 ? true : false;
	}


	/**
	 * 添加品牌店铺
	 * @param array $shop 店铺信息
	 */
	public function _addBranchShop($shop = array()){
			$branchShop = array(
					'id'          => makeUUID(),
					'branch_name' => $shop['shopBranchName'],
					'tb_brand_id' => $shop['shopBrandId'],
					'tb_mall_id'  => $shop['shopAddressId'],
					'create_time' => currentTime(),
					'update_time' => currentTime(),
					'open_time'   => $shop['shopOpenTime'],
					'close_time'  => $shop['shopCloseTime'],
					'pic_url'	  => $shop['shopImgPath'],
					'tel'         => $shop['shopTel'],
					'description' => $shop['shopDescription'],
					'address'     => $shop['shopFloor'],
				);

			$branchRes = $this->db->insert(tname('brand_mall'), $branchShop);

		return $branchRes ? true : $this->lang->line('ERR_ADD_FAILURE');
	}
}
