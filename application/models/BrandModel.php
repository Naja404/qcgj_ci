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
	 * 获取商场列表
	 * @param string $where 查询条件
	 * @param int $p 页码
	 * @param int $level 类型
	 * @param string $url 分页url
	 */
	public function getMall($where = null, $p = 1, $level = 0, $url = false){

		$limit = "LIMIT ".page($p, 25);
		
		$field = " * ";

		$sql = "SELECT %s FROM ".tname('mall')." WHERE level = ".$level." %s  ORDER BY update_time ASC %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url($url), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 获取商场详细
	 * @param string $id 商场id
	 * @param int $level 
	 */
	public function getMallDetail($id = false, $level = 0){
		$where = array(
				'id' => $id,
				'level' => $level,
			);

		$queryRes = $this->db->get_where(tname('mall'), $where)->first_row();

		return $queryRes;
	}

	/**
	 * 根据id获取品牌信息
	 * @param string $brandId 品牌id
	 */
	public function getBrandInfo($brandId = false){
		$where = array(
				'id' => $brandId,
			);
		
		$brandRes = $this->db->get_where(tname('brand'), $where)->first_row();

		$whereCate = array(
				'tb_brand_id' => $brandId,
			);

		$category = $this->db->get_where(tname('brand_category'), $whereCate)->result();

		$style = $this->db->get_where(tname('brand_style'), $whereCate)->result();

		$age = $this->db->get_where(tname('brand_age'), $whereCate)->result();

		$price = $this->db->get_where(tname('brand_price'), $whereCate)->result();
		
		$brandRes->category = array();

		foreach ($category as $k => $v) {
			$brandRes->category[] = $v->tb_category_id;
		}

		$brandRes->style = array();

		foreach ($style as $k => $v) {
			$brandRes->style[] = $v->tb_style_id;
		}

		$brandRes->age = array();

		foreach ($age as $k => $v) {
			$brandRes->age[] = $v->tb_age_id;
		}

		$brandRes->price = array();

		foreach ($price as $k => $v) {
			$brandRes->price[] = $v->tb_price_id;
		}

		$brandRes->pic_url = $this->getBrandMainPicWithSingle($brandRes->pic_url);

		return $brandRes;
	}

	/**
	 * 根据id获取店铺信息
	 * @param string $shopId 店铺id
	 */
	public function getShopInfo($shopId = false){
		$sql = "SELECT 
					CONCAT(b.name_en, '_', b.name_zh) AS brandName,
					b.id AS brandId,
					c.id AS addressId,
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
					c.open_time AS openTime,
					c.close_time AS closeTime,
					a.description AS description,
					a.address AS shopFloor,
					c.level AS shopTyp,
					LEFT(c.update_time, 10) AS updateTime
					FROM tb_brand_mall AS a 
					LEFT JOIN tb_brand AS b ON b.id = a.tb_brand_id
					LEFT JOIN tb_mall AS c ON c.id = a.tb_mall_id
					WHERE a.id = '".$shopId."'
					ORDER BY c.update_time DESC";
		$queryRes = $this->db->query($sql)->first_row();

		return $queryRes;
	}

	/**
	 * 获取1005品牌列表
	 * @param string $where 查询条件
	 * @param int $p 页数
	 */
	public function getHadBrandList($where = NULL, $p = 1){
		$limit = "ORDER BY create_time DESC LIMIT ".page($p, 25);
		
		$field = " 	id, 
					name_en, 
					name_zh, 
					logo_url, 
					pic_url,
					create_time, 
					update_time, 
					LEFT(description, 50) AS summary, 
					oper ";

		$sql = "SELECT %s FROM ".tname('brand')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('Brand/hadBrandList'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, $limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);
		
		return $returnRes;
	}

	/**
	 * 获取品牌列表
	 * @param string $where 查询条件
	 * @param int $p 页数
	 */
	public function getBrandList($where = NULL, $p = 1, $isCate = false){

		$limit = " LIMIT ".page($p, 25);

		if($isCate){
			$field = "  a.id, 
						a.name_en, 
						a.name_zh, 
						a.logo_url, 
						a.pic_url, 
						(SELECT GROUP_CONCAT(DISTINCT d.name) AS name FROM tb_brand_category AS c LEFT JOIN tb_category AS d ON d.id = c.tb_category_id WHERE c.tb_brand_id = a.id) AS category, 
						a.create_time, 
						a.update_time, 
						LEFT(a.description, 50) AS summary, 
						IF(CHAR_LENGTH(a.oper) = 32, (SELECT name FROM tb_qcgj_role_user WHERE user_id = a.oper LIMIT 1), a.oper) AS oper ";
			$countField = " COUNT(*) AS total ";
			$sql = "SELECT 
						%s
						FROM tb_brand AS a
						LEFT JOIN tb_brand_category as b on b.tb_brand_id = a.`id`
						 WHERE a.status = 1 and b.tb_category_id is null or (a.description is null or a.description = '') ORDER BY a.create_time DESC %s ";
			
			$queryTotal = $this->db->query(sprintf($sql, $countField, ''))->first_row();

			$pagination = $this->setPagination(site_url('Brand/listView'), $queryTotal->total, 25);

			$queryRes = $this->db->query(sprintf($sql, $field, $limit))->result();

			$returnRes = array(
					'list'       => $queryRes,
					'pagination' => $pagination,
				);

			return $returnRes;

		}
		
		$field = " 	id, 
					name_en, 
					name_zh, 
					logo_url, 
					pic_url,
					(SELECT GROUP_CONCAT(DISTINCT b.name) AS name FROM tb_brand_category AS a LEFT JOIN tb_category AS b ON b.id = a.tb_category_id WHERE a.tb_brand_id = tb_brand.id) AS category,
					create_time, 
					update_time, 
					LEFT(description, 50) AS summary, 
					IF(CHAR_LENGTH(oper) = 32, (SELECT name FROM tb_qcgj_role_user WHERE user_id = oper LIMIT 1), oper) AS oper ";

		$sql = "SELECT %s FROM ".tname('brand')." %s %s ";

		$queryTotal = $this->db->query(sprintf($sql, 'COUNT(*) AS total', $where, ''))->first_row();

		$pagination = $this->setPagination(site_url('Brand/listView'), $queryTotal->total, 25);

		$sql = sprintf($sql, $field, $where, ' ORDER BY create_time DESC '.$limit);

		$queryRes = $this->db->query($sql)->result();

		$returnRes = array(
				'list'       => $queryRes,
				'pagination' => $pagination,
			);

		return $returnRes;
	}

	public function getShopList($where = NULL, $p = 1){
		
		$limit = "LIMIT ".page($p, 25);
		$field = "					a.id,
					b.name_zh AS brandName,
					b.name_en AS brandNameEn,
					c.name_zh AS shopName,
					a.branch_name AS branchName,
					a.pic_url AS shopPic,
					c.tel AS tel,
					c.district_name AS district,
					c.city_name AS cityName,
					c.address AS address,
					c.level,
					c.update_time,
					LEFT(a.update_time, 10) AS updateTime 
					";
		$countField = " COUNT(*) AS total ";
		$sql = "SELECT 
					%s
					FROM tb_brand_mall AS a 
					LEFT JOIN tb_brand AS b ON b.id = a.tb_brand_id
					LEFT JOIN tb_mall AS c ON c.id = a.tb_mall_id
					WHERE c.address != '' %s 
					ORDER BY a.create_time ASC ";

		$queryTotal = $this->db->query(sprintf($sql, $countField, $where))->first_row();

		$pagination = $this->setPagination(site_url('Brand/shopList'), $queryTotal->total, 25);

		$queryRes = $this->db->query(sprintf($sql, $field, $where).$limit)->result();

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

		return $this->db->delete(tname('brand_mall'), $where);
	}

	/**
	 * 获取品牌分类
	 *
	 */
	public function getBrandCategory(){
		$where = array(
				'level' => 1,
				'type' => 1,
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
					'value' => $v->name_zh,
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
				'logo_url'    => $reqData['brandLogoPath'],
				'pic_url'     => $reqData['brandShowPath'],
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

		// // 添加品牌店铺
		// if (isset($reqData['mallId']) && count($reqData['mallId'])) {
		// 	foreach ($reqData['mallId'] as $k => $v) {
				
		// 		$mall = array(
		// 				'id'		  => makeUUID(),
		// 				'tb_brand_id' => $brand['id'],
		// 				'tb_mall_id'  => $v,
		// 				'create_time' => currentTime(),
		// 				'update_time' => currentTime(),
		// 				'address'     => $reqData['floor'][$k],
		// 			);

		// 		$this->db->insert(tname('brand_mall'), $mall);
		// 	}
		// }

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

		// 已有店铺
		if (!empty($reqData['shopAddressId'])) {
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
	 * 编辑门店
	 * @param array $reqData 门店数据内容
	 */
	public function editShop($reqData = array()){
		
		$shop = array(
				'name_en'        => $reqData['shopNameEN'],
				'name_zh'        => $reqData['shopNameZH'],
				'name_py'        => $reqData['shopNamePY'],
				'name_short'     => $reqData['shopNameShort'],
				'address'        => $reqData['shopAddress'],
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

		if ($this->existsShopById($reqData['shopAddress'], $reqData['shopAddressId'])) {
			
			$where = array(
					'id' => $this->getMallIdById($reqData['shopId']),
				);
			
			$updateRes = $this->db->where($where)->update(tname('mall'), $shop);

			$mallId = $where['id'];

		}else{
			
			$shop['id'] = makeUUID();
			
			$insertRes = $this->db->insert(tname('mall'), $shop);

			if (!$insertRes) return $this->lang->line('ERR_UPDATE_FAILURE');

			$mallId = $shop['id'];
		}

		$brandMall = array(
					'branch_name' => $reqData['shopBranchName'],
					'tb_brand_id' => $reqData['shopBrandId'],
					'tb_mall_id'  => $mallId,
					'update_time' => currentTime(),
					'open_time'   => $reqData['shopOpenTime'],
					'close_time'  => $reqData['shopCloseTime'],
					'pic_url'	  => $reqData['shopImgPath'],
					'tel'         => $reqData['shopTel'],
					'description' => $reqData['shopDescription'],
					'address'     => $reqData['shopFloor'],
			);

		$brandMallWhere = array(
				'id' => $reqData['shopId'],
			);

		$updateBrandMall = $this->db->where($brandMallWhere)->update(tname('brand_mall'), $brandMall);

		return $updateBrandMall ? true : $this->lang->line('ERR_UPDATE_FAILURE');
	}

	/**
	 * 编辑品牌
	 * @param array $reqData 品牌数据内容
	 */
	public function editBrand($reqData = array()){
		
		$brand = array(
				'name_zh'     => $reqData['nameZh'],
				'name_en'     => $reqData['nameEn'],
				// 'logo_url'    => $reqData['brandLogoPath'],
				// 'pic_url'     => $reqData['brandShowPath'],
				'update_time' => currentTime(),
				'description' => $reqData['summary'],
				'oper'        => $this->userInfo->user_id,
			);
		
		$updateRes = $this->db->where(array('id' => $reqData['brandRelation']))->update(tname('brand'), $brand);

		if(!$updateRes) return $this->lang->line('ERR_EDIT_BRAND_FAILURE');

		$whereCate = array(
				'tb_brand_id' => $reqData['brandRelation'],
			);

		// 添加品牌分类
		$this->db->where($whereCate)->delete(tname('brand_category'));

		if (isset($reqData['category']) && count($reqData['category'])) {

			foreach ($reqData['category'] as $k => $v) {
				$category = array(
						'id'             => makeUUID(),
						'create_time'    => currentTime(),
						'update_time'    => currentTime(),
						'tb_brand_id'    => $reqData['brandRelation'],
						'tb_category_id' => $v,
					);

				$this->db->insert(tname('brand_category'), $category);
			}
		}

		// 添加风格
		$this->db->where($whereCate)->delete(tname('brand_style'));

		if (isset($reqData['style']) && count($reqData['style'])) {

			foreach ($reqData['style'] as $k => $v) {
				$style = array(
						'id' => makeUUID(),
						'create_time' => currentTime(),
						'update_time' => currentTime(),
						'tb_brand_id' => $reqData['brandRelation'],
						'tb_style_id' => $v,
					);	

				$this->db->insert(tname('brand_style'), $style);
			}

		}

		// 更新年龄层
		$this->db->where($whereCate)->delete(tname('brand_age'));

		if (isset($reqData['age']) && count($reqData['age'])) {

			foreach ($reqData['age'] as $k => $v) {
				$age = array(
						'id' => makeUUID(),
						'create_time' => currentTime(),
						'update_time' => currentTime(),
						'tb_brand_id' => $reqData['brandRelation'],
						'tb_age_id' => $v,
					);	

				$this->db->insert(tname('brand_age'), $age);
			}

		}

		// 更新消费层
		$this->db->where($whereCate)->delete(tname('brand_price'));
		
		if (isset($reqData['price']) && count($reqData['price'])) {

			foreach ($reqData['price'] as $k => $v) {
				$price = array(
						'id' => makeUUID(),
						'create_time' => currentTime(),
						'update_time' => currentTime(),
						'tb_brand_id' => $reqData['brandRelation'],
						'tb_price_id' => $v,
					);	

				$this->db->insert(tname('brand_price'), $price);
			}

		}

		return true;
	}

	/**
	 * 编辑商场
	 * @param array $update 更新内容
	 * @param array $where 更新条件
	 */
	public function editMall($update = array(), $where = array()){

		$updateRes = $this->db->where($where)->update(tname('mall'), $update);

		return $updateRes ? true : false;
	}

	/**
	 * 获取店铺id
	 * @param string $shopId 关联店铺id
	 */
	public function getMallIdById($shopId = false){
		$where = array(
				'id' => $shopId,
			);

		$queryRes = $this->db->get_where(tname('brand_mall'), $where)->first_row();

		return $queryRes->tb_mall_id;
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
	 * 根据城市名获取区域内容
	 * @param string $city 城市名
	 * @param string $format 输出格式 html json
	 */
	public function getDistrictByCity($cityName = false, $format = 'html'){
		$cityId = $this->_getCityIdByName($cityName);

		$list = $this->getDistrictList($cityId);

		$returnRes = '';

		if ($format == 'html') {
			$returnRes .= '<option value="">区域</option>';
			foreach ($list as $k => $v) {
				$returnRes .= '<option value="'.$v->name.'">'.$v->name.'</option>';
			}
		}

		if ($format == 'obj') {
			$returnRes = $list;
		}

		return $returnRes;
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
	 * @param string $option 
	 */
	public function validateAddBrand($validateRule = array(), $reqData = array(), $option = false){
		$this->form_validation->set_rules($validateRule);

		if (!$this->form_validation->run()) return validation_errors();

		// if (!isset($reqData['category']) || count($reqData['category']) <= 0) return $this->lang->line('ERR_CATEGORY'); 

		// if (!isset($reqData['mallId']) || count($reqData['mallId']) <= 0) return $this->lang->line('ERR_MALL'); 

		// 验证品牌名是否存在
		if ($this->existsBrandName($reqData['nameZh'], $reqData['nameEn'], $option)) return $this->lang->line('ERR_EXISTS_BRAND_NAME');

		return true;
	}

	/**
	 * 添加店铺验证
	 * @param array $validateRuel 验证规则
	 * @param array $reqData 待验证数据
	 * @param string $option add 添加, edit 编辑
	 */
	public function validateAddShop($validateRule = array(), $reqData = array(), $option = 'add'){

		// 验证品牌
		if (!$this->existsBrandById($reqData['shopBrandName'], $reqData['shopBrandId'])) return $this->lang->line('ERR_SHOP_BRANDNAME');

		$this->form_validation->set_rules($validateRule);

		if (!$this->form_validation->run()) return validation_errors();
		
		// if ($this->existsShopName($reqData['shopNameZH'], $reqData['shopNameEN'])) return $this->lang->line('ERR_EXISTS_SHOP_NAME');

		// // 验证edit状态
		// if ($option != 'add') return true;

		// // 判断门店类型
		// if ($reqData['shopType'] == 1) { // 1.商场 2.街边店
		// 	if (!$this->existsShopById($reqData['shopAddress'], $reqData['shopAddressId'])) return $this->lang->line('ERR_SHOP_TYPE_MALL');
		// }

		return true;
	}

	/**
	 * 是否存在商场/门店名
	 * @param string $nameZh 商场/门店中文名
	 * @param string $nameEn 商场/门店英文名
	 */
	public function existsShopName($nameZh = false, $nameEn = false){
		$where = " name_zh = '".addslashes($nameZh)."' ";

		if (!empty($nameEn)) $where .= " AND name_en = '".addslashes($nameEn)."'";

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
	 * @param string $brandId 验证条件
	 */
	public function existsBrandName($nameZh = false, $nameEn = false, $brandId = false){
		
		$where = "name_zh = '".addslashes($nameZh)."' ";
		
		if (!empty($nameEn)) $where .= " AND name_en = '".addslashes($nameEn)."'";

		if($brandId !== false) $where .= " AND id != '".$brandId."' ";

		$sql = "SELECT COUNT(*) AS total FROM ".tname('brand')." WHERE status = 1 AND ".$where;

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
	 * 检测是否有权限编辑品牌
	 * @param string $brandId 品牌id
	 */
	public function checkEditBrand($brandId = false){
		$where = array(
				'id' => $brandId,
			);

		$queryRes = $this->db->get_where(tname('brand'), $where)->result();

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

	/**
	 * 获取品牌宣传图 单张图片
	 * @param string $picUrl 品牌宣传图
	 */
	public function getBrandMainPicWithSingle($picUrl = false){
		if (empty($picUrl)) return '';

		$picArr = explode(',', $picUrl);

		foreach($picArr as $j => $m){
			if ($m) { 
				$m = explode('|', $m);
				return $m[0];
			}
		}

		return '';
	}

	/**
	 * 根据城市名获取城市id
	 * @param string $cityName 城市名
	 */
	private function _getCityIdByName($cityName = false){
		if (empty($cityName) || !$cityName) return false;

		$where = array(
				'name_zh' => $cityName,
			);

		$cityRes = $this->db->get_where(tname('city'), $where)->first_row();

		return $cityRes->id ? $cityRes->id : false;
	}
}
