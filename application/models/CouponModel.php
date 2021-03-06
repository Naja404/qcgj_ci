<?php
/**
 * 优惠券管理模型
 */

class CouponModel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 获取优惠券列表
	 * @param string $where 查询条件
	 * @param string $order 排序条件
	 * @param int $pageNum 页码
	 * @param int $pageCount 条数
	 */
	public function getCouponList($where = NULL, $order = NULL, $pageNum = 1, $pageCount = 15){
		$pageNum = ($pageNum - 1) * $pageCount;

		$limit = ' LIMIT '.$pageNum.','.$pageCount;

		$field = " a.id,
					a.tb_brand_id,
					a.name AS title,
					CONCAT(a.begin_date, '<br/>~<br/>', a.end_date) AS expire,
					a.status AS status,
					a.on_sale AS saleStatus,
					(SELECT COUNT(*) FROM ".tname('coupon_individual')." WHERE tb_coupon_id = a.id AND status > 0) AS received,
					(SELECT COUNT(*) FROM ".tname('coupon_individual')." WHERE tb_coupon_id = a.id AND status = 2) AS used,
					count(c.tb_coupon_id) AS mallCount ";

		$totalField = ' COUNT(*) AS total ';

		$sql = "SELECT
					%s
				 FROM ".tname('coupon')." AS a
				LEFT JOIN ".tname('coupon_mall')." AS c ON c.tb_coupon_id = a.id
				 %s GROUP BY a.id %s ";

		$where = $this->_checkUserWhere($where);

		if (!$where) {
			return $this->returnRes(null, array('list' => array(), 'page' => false), false);
		}

		$queryTotal = $this->db->query(sprintf($sql, $totalField, $where, $order))->result();

		$queryRes = $this->db->query(sprintf($sql, $field, $where, $order).$limit)->result();

		$returnData = array(
						'list'  => $queryRes,
						'page' => $this->setPagination(site_url('Coupon/'.$this->router->method), count($queryTotal), $pageCount),
					);


		return $this->returnRes(null, $returnData, false);
	}

	/**
	 * 获取优惠券使用状态
	 * @param string $couponId 优惠券id
	 */
	public function getStateList($couponId = false){

		$couponList = $this->_getCouponListWithState();

		if (empty($couponList)) {
			return false;
		}

		if (!in_array($couponId, $couponList['couponId'])) {
			if (count($couponList['couponId']) <= 0) return false;
			$couponId = $couponList['couponId'][0];
		}

		$usedSql = "SELECT COUNT(*) AS total, SUBSTRING(used_time, 6, 5) AS time FROM ".tname('coupon_individual')." WHERE tb_coupon_id = '".$couponId."' AND status = 2 GROUP BY LEFT(used_time, 10) ASC";
		$receivedSql = "SELECT COUNT(*) AS total, SUBSTRING(update_time, 6, 5) AS time FROM ".tname('coupon_individual')." WHERE tb_coupon_id = '".$couponId."'  AND status > 0 GROUP BY LEFT(update_time, 10) ASC";

		$usedRes = $this->db->query($usedSql)->result_array();
		$receivedRes = $this->db->query($receivedSql)->result_array();

		$returnRes = array(
				'usedCoupon'     => $this->_formatCouponStateList($usedRes),
				'receivedCoupon' => $this->_formatCouponStateList($receivedRes),
				'usedCount'		 => count($usedRes),
				'receivedCount'  => count($receivedRes),
				'couponList'     => $couponList['list'],
			);

		return $returnRes;
	}

	/**
	 * 新建优惠券
	 * @param array $couponData 优惠券数组数据
	 */
	public function addCoupon_bak($couponData = array()){

		$couponInsertData = array(
				'coupon_id'   => makeUUID(),
				'brand_id'    => $this->userInfo->brand_id,
				'name'        => $couponData['couponTitle'],
				'type'        => $couponData['couponType'],
				'price'       => $couponData['couponMoney'] == 2 ? floatval($couponData['couponMoneyNum']) : 0,
				'sale_time'   => $couponData['reviewPassDate'],
				'create_time' => currentTime(),
				'update_time' => currentTime(),
				'operator'    => $this->userInfo->user_id,
				'status'      => 0,
			);
		// 创建优惠券
		$couponRes = $this->db->insert(tname('qcgj_coupon'), $couponInsertData);

		if (!$couponRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_COUPON_ADD_FAILURE');
			return $this->returnRes;
		}

		$expireDate = $this->_formatExpireDate($couponData['couponExpireDate']);
		$receiveDate = $this->_formatExpireDate($couponData['couponReceiveDate']);

		$couponDetailData = array(
				'coupon_id'          => $couponInsertData['coupon_id'],
				'sum_count'          => intval($couponData['couponSum']),
				'receive_sum'        => intval($couponData['couponEveryoneSum']),
				'expire_date_start'  => $expireDate['start'],
				'expire_date_end'    => $expireDate['end'],
				'receive_date_start' => $receiveDate['start'],
				'receive_date_end'   => $receiveDate['end'],
				'use_time_start'     => trim($couponData['couponUseTimeStart']),
				'use_time_end'       => trim($couponData['couponUseTimeEnd']),
				'use_guide'          => $this->_formatGuideToJSON($couponData['couponUseGuide']),
				'notice'             => $this->_formatGuideToJSON($couponData['couponNotice']),
				'verification_guide' => $this->_formatGuideToJSON($couponData['couponVerification']),
			);
		// 创建优惠券详情
		$couponDetailRes = $this->db->insert(tname('qcgj_coupon_detail'), $couponDetailData);
		if (!$couponDetailRes) {
			$this->db->delete(tname('qcgj_coupon'), array('coupon_id' => $couponInsertData['coupon_id']));
			$this->returnRes['msg'] = $this->lang->line('ERR_COUPON_ADD_FAILURE');
			return $this->returnRes;
		}

		// 优惠券使用门店
		if (is_array($couponData['mallID']) && count($couponData['mallID'])) {

			$couponInsertMall = array();

			foreach ($couponData['mallID'] as $k => $v) {
				$couponInsertMall[] = array(
						'coupon_id' => $couponInsertData['coupon_id'],
						'shop_id'   => $v,
					);
			}

			$this->db->insert_batch(tname('qcgj_coupon_shop'), $couponInsertMall);
		}

		// 创建优惠券图片
		if ($couponData['couponPic']) {
			$couponPic = array(
					'coupon_id' => $couponInsertData['coupon_id'],
					'path'      => $couponData['couponPic'],
				);
			$this->db->insert(tname('qcgj_coupon_pic'), $couponPic);
		}

		// TODO 创建优惠券代码
		$this->returnRes['error'] = false;
		return $this->returnRes;
	}

	/**
	 * 新建优惠券
	 * @param array $couponData 优惠券数组数据
	 * @param array $brandId 品牌id
	 */
	public function addCoupon($couponData = array(), $brandId = false){

		if ($this->userInfo->brand_id) $brandId = $this->userInfo->brand_id;

		$brandInfo = $this->getBrandInfoById($brandId);

		$expireDate = $this->_formatExpireDate($couponData['couponExpireDate']);
		$receiveDate = $this->_formatExpireDate($couponData['couponReceiveDate']);

		$couponInsertData = array(
				'id'                     => makeUUID(),
				'name'                   => $couponData['couponTitle'],
				// 'brand_pic_url'			 => $brandInfo->logo_url,
				'brand_pic_url'          => $couponData['couponImg'],
				// 'main_pic_url'         	 => $couponData['couponPic'],
				'status'                 => 0,
				'gene_type'              => (int)$couponData['couponAutoCode'],	//生成类型 0.无须生成 1.自动生成 2.手动
				'coupon_type'            => $couponData['couponType'],				// 1.代金券 102.折扣劵 103.提货券
				'create_time'            => currentTime(),
				'update_time'            => currentTime(),
				'tb_category_id'         => $brandInfo->tb_category_id,
				'category_name'			 => $brandInfo->category_name,
				'tb_brand_id'            => $brandInfo->id,
				'brand_name_en'          => $brandInfo->name_en,
				'brand_name_zh'          => $brandInfo->name_zh,
				'coupon_desc'            => $couponData['couponUseGuide'],			//使用说明
				'recommend_desc'         => $couponData['couponVerification'],		//验劵说明
				'total_count'            => $couponData['couponSum'],				//总数
				'limit_count_used'       => $couponData['couponSum'],				//使用总数
				'limit_count_per_person' => $couponData['couponEveryoneSum'],		//每人限领数
				'cost_price'             => $couponData['couponMoney'] == 2 ? floatval($couponData['couponMoneyNum']) : 0.00,//收费优惠券
				'begin_date'             => $expireDate['start'],//有效期开始
				'end_date'               => $expireDate['end'],//有效期结束
				'receive_begin_date'     => $this->_formatTimeToDate($receiveDate['start'], $couponData['couponUseTimeStart']),					//领取开始
				'receive_end_date'       => $this->_formatTimeToDate($receiveDate['end'], $couponData['couponUseTimeEnd']),					//领取结束
				'is_delete'              => 0,
				'on_sale'				 => 0,
				'on_sale_time'           => $couponData['reviewPass'] == 2 ? $couponData['reviewPassDate'] : NULL, //上架时间
				'oper'                   => $this->userInfo->user_id,
				'access_limit'			 => 1,
				'interface_partner'		 => $couponData['interface_partner'] == '1' ? '1' : '0',
			);
	
		// 创建优惠券
		$couponRes = $this->db->insert(tname('coupon'), $couponInsertData);

		if (!$couponRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_COUPON_ADD_FAILURE');
			return $this->returnRes;
		}

		// 优惠券适用门店
		if (is_array($couponData['mallID']) && count($couponData['mallID'])) {

			$couponInsertMall = $tmpMallArr = array();

			foreach ($couponData['mallID'] as $k => $v) {

				if (in_array($v, $tmpMallArr)) {
					continue;
				}

				$couponInsertMall[] = array(
						'id'           => makeUUID(),
						'tb_coupon_id' => $couponInsertData['id'],
						'tb_mall_id'   => $v,
						'address'	   => $this->getMallFloorById($v, $brandId),
						'create_time'  => currentTime(),
						'update_time'  => currentTime(),
					);
			}

			$this->db->insert_batch(tname('coupon_mall'), $couponInsertMall);
		}

		$this->returnRes['error'] = false;

		return $this->returnRes;
	}

	/**
	 * 编辑优惠券
	 * @param array $couponData 优惠券数组数据
	 */
	public function editCoupon($couponData = array()){

		$expireDate = $this->_formatExpireDate($couponData['couponExpireDate']);
		$receiveDate = $this->_formatExpireDate($couponData['couponReceiveDate']);

		$couponEditData = array(
				'name'                   => $couponData['couponTitle'],
				'brand_pic_url'          => $couponData['couponImg'],
				// 'main_pic_url'           => $couponData['couponPic'],
				'gene_type'              => (int)$couponData['couponAutoCode'],	//生成类型 0.无须生成 1.自动生成 2.手动
				'coupon_type'            => $couponData['couponType'],				// 1.代金券 102.折扣劵 103.提货券
				'update_time'            => currentTime(),
				'coupon_desc'            => $couponData['couponUseGuide'],			//使用说明
				'recommend_desc'         => $couponData['couponVerification'],		//验劵说明
				'total_count'            => $couponData['couponSum'],				//总数
				'limit_count_used'       => $couponData['couponSum'],				//使用总数
				'limit_count_per_person' => $couponData['couponEveryoneSum'],		//每人限领数
				'cost_price'             => $couponData['couponMoney'] == 2 ? floatval($couponData['couponMoneyNum']) : 0.00,//收费优惠券
				'begin_date'             => $expireDate['start'],//有效期开始
				'end_date'               => $expireDate['end'],//有效期结束
				'receive_begin_date'     => $this->_formatTimeToDate($receiveDate['start'], $couponData['couponUseTimeStart']),					//领取开始
				'receive_end_date'       => $this->_formatTimeToDate($receiveDate['end'], $couponData['couponUseTimeEnd']),					//领取结束
				'on_sale_time'           => $couponData['reviewPass'] == 2 ? $couponData['reviewPassDate'] : NULL, //上架时间
				'oper'                   => $this->userInfo->user_id,
				'interface_partner'		 => isset($couponData['interface_partner']) && $couponData['interface_partner'] == '1' ? '1' : '0',
			);
		$where = array(
				'id' => $couponData['couponId'],
			);
		// 编辑优惠券
		$couponRes = $this->db->where($where)->update(tname('coupon'), $couponEditData);

		if (!$couponRes) {
			$this->returnRes['msg'] = $this->lang->line('ERR_COUPON_EDIT_FAILURE');
			return $this->returnRes;
		}

		// 优惠券适用门店
		if (is_array($couponData['mallID']) && count($couponData['mallID'])) {

			$this->db->delete(tname('coupon_mall'), array('tb_coupon_id' => $couponData['couponId']));

			$couponInsertMall = $tmpMallArr = array();

			foreach ($couponData['mallID'] as $k => $v) {

				if (in_array($v, $tmpMallArr)) {
					continue;
				}

				$couponInsertMall[] = array(
						'id'           => makeUUID(),
						'tb_coupon_id' => $couponData['couponId'],
						'tb_mall_id'   => $v,
						'address'	   => $this->getMallFloorById($v, $couponData['brandId']),
						'create_time'  => currentTime(),
						'update_time'  => currentTime(),
					);
			}

			$this->db->insert_batch(tname('coupon_mall'), $couponInsertMall);
		}

		$this->returnRes['error'] = false;
	}

	/**
	 * 上架优惠券
	 * @param string $couponId 优惠券id
	 */
	public function saleCoupon($couponId = false, $saleStatus = 0){
		if ($this->userInfo->role_id != 1) return $this->lang->line('ERR_AUTH_OPERTION');
		if (!in_array($saleStatus, array(0,1,2))) return $this->lang->line('ERR_AUTH_OPERTION');
		
		$where = array(
				'id' => $couponId,
			);
		$update = array(
				'on_sale' => (int)$saleStatus,
			);

		$updateRes = $this->db->where($where)->update(tname('coupon'), $update);

		if (!$updateRes) return $this->lang->line('ERR_ONSALE_FAIL');

		$returnRes = array(
				'html' => $this->lang->line('TEXT_STATUS_'.$saleStatus),
				'class' => $this->lang->line('TEXT_OPERATION_ICON_'.$saleStatus),
			);

		return $returnRes;
	}

	/**
	 * 删除优惠券
	 * @param string $couponId 优惠券id
	 */
	public function delCouponById($couponId = false){
		if(empty($couponId) || !$couponId){
			return false;
		}
		
		$where = array(
				// 'tb_brand_id' => $this->userInfo->brand_id,
				'id'          => $couponId,
			);
		$update = array(
				'is_delete' => 1,
			);

		$queryRes = $this->db->where($where)->update(tname('coupon'), $update);

		return $queryRes ? true : false;
	}

	/**
	 * 获取优惠券信息
	 * @param string $couponId 优惠券id
	 * @param bool $isAdmin 是否管理员
	 */
	public function getCouponById($couponId = false, $isAdmin = false){
		if (!$this->checkAuthCoupon($couponId, $isAdmin)) {
			return false;
		}

		$where = array(
				'id'          => $couponId,
				'tb_brand_id' => $this->userInfo->brand_id,
			);

		if ($isAdmin === true) unset($where['tb_brand_id']);

		$couponRes = $this->db->get_where(tname('coupon'), $where)->first_row();

		$couponRes->couponExpireDate = $this->_formatDateToStr($couponRes->begin_date, $couponRes->end_date);
		$couponRes->couponReceiveDate = $this->_formatDateToStr($couponRes->receive_begin_date, $couponRes->receive_end_date);

		$couponRes->mallID = $this->db->select('tb_mall_id AS id')->get_where(tname('coupon_mall'), array('tb_coupon_id' => $couponId))->result_array();

		if ($couponRes->tb_brand_id) $couponRes->brandName = $this->db->get_where(tname('brand'), array('id' => $couponRes->tb_brand_id))->first_row();

		$couponType = config_item('COUPON_TYPE');

		$couponRes->typeHTML = $couponType[$couponRes->coupon_type];

		$couponRes->mallHTML = '';

		foreach ($couponRes->mallID as $k => $v) {
			$mallRes = $this->db->get_where(tname('mall'), array('id' => $v['id']))->first_row();
			$couponRes->mallHTML .= $mallRes->name_zh.'('.$mallRes->city_name.' '.$mallRes->address.')<br>';
		}

		$couponRes->geneHTML = $couponRes->gene_type == 1 ? '自动生成' : '手动生成';

		$couponRes->partnerHTML = $couponRes->interface_partner == 1 ? '银联' : '无';

		return $couponRes;		
	}

	/**
	 * 获取店铺列表
	 *
	 */
	public function getShopList($brandId = false){

		if ($brandId) {
			$queryRes = $this->getAdminBrandShopList($brandId);
		}else{
			$queryRes = $this->getUserBrandShopList();
		}

		$cityList = $cityName = $areaName = array();
		$bjAreaList = $shAreaList = $gzAreaList = '';

		if ($queryRes) {
			foreach ($queryRes as $k) {
				if (!in_array($k['districtName'], $areaName)) {
					array_push($areaName, $k['districtName']);
				}else{
					continue;
				}

				if (!in_array($k['cityName'], $cityName)) {
					array_push($cityName, $k['cityName']);
				}

				$optionHTML = '<option value="'.$k['districtName'].'">'.$k['districtName'].'</option>';

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

	/**
	 * 获取管理员品牌店铺列表
	 * @param string $brandId 品牌id
	 */
	public function getAdminBrandShopList($brandId = false){
		$sql = "SELECT 
					b.id AS mallID, 
					b.name_zh AS mallName, 
					b.district_name AS districtName, 
					b.city_name AS cityName, 
					b.address 
				FROM tb_brand_mall AS a
				LEFT JOIN tb_mall AS b ON b.id = a.tb_mall_id
				WHERE 
					a.tb_brand_id = '%s' 
					AND 
					b.status = 1 
					AND 
					b.level IN (1,2) ";

		$queryRes = $this->db->query(sprintf($sql, $brandId))->result_array();

		return $queryRes;
	}

	/**
	 * 获取品牌店铺列表
	 *
	 */
	public function getUserBrandShopList(){

		if (!empty($this->userInfo->mall_id) && !empty($this->userInfo->brand_id)) {
			$fields = "id AS mallID,
						address,
						name_zh AS mallName,
						district_name AS districtName,
						city_name AS cityName";
			$where = array('id' => $this->userInfo->mall_id);
			$queryRes = $this->db->select($fields)->get_where(tname('mall'), $where)->result_array();

		}

		if (empty($this->userInfo->mall_id) && !empty($this->userInfo->brand_id)) {
			$sql = "SELECT
						b.id AS mallID,
						b.address,
						b.name_zh AS mallName,
						b.district_name AS districtName, 
						b.city_name AS cityName
						 FROM ".tname('brand_mall')." AS a
						LEFT JOIN ".tname('mall')." AS b ON b.id = a.tb_mall_id
						WHERE a.tb_brand_id = '".$this->userInfo->brand_id."' ORDER BY b.city_name ";
			$queryRes = $this->db->query($sql)->result_array();
		}

		return $queryRes;
	}

	/**
	 * 验证优惠券编辑权限
	 * @param string $couponId
	 * @param string $isAdmin 是否管理员
	 */
	public function checkAuthCoupon($couponId = false, $isAdmin = false){
		$where = array(
				'id'          => $couponId,
				'tb_brand_id' => $this->userInfo->brand_id,
				// 'on_sale'     => 0,
			);

		if ($isAdmin === true) unset($where['tb_brand_id']); 

		$queryRes = $this->db->get_where(tname('coupon'),$where)->result();

		return count($queryRes) > 0 ? true : false;
	}

	/**
	 * 置顶=1,推荐=2,取消置顶=101,取消推荐=102
	 * @param string $couponId 优惠券id
	 * @param int $reqStatus 状态码
	 * @param int $sort 排序数值
	 */
	public function setCouponStatus($couponId = false, $reqStatus = 0, $sort = 0){
		if (empty($couponId) || !$couponId) return false;

		$where = array('id' => $couponId);
		$update = array('status' => (int)$reqStatus);

		if ($sort > 0) $update['sort_num'] = $sort;

		$updateRes = $this->db->where($where)->update(tname('coupon'), $update);

		return $updateRes ? true : false;
	}

	/**
	 * 创建银联优惠券
	 * @param string $couponId 
	 * @param int $couponStatus 1.上架 2.下架
	 */
	public function setUnionPay($couponId = false, $couponStatus = false){
		if (!$couponId || empty($couponId)) return false;

		if (!in_array($couponStatus, array(1, 2))) return false;

		$coupon = $this->getCouponById($couponId, true);

		if (count($coupon) <= 0 || empty($coupon)) return false;

		//判断是否是银联
		if ((int)$coupon->interface_partner != 1) return true;

		// todo 查询门店id

		$form = array(
				'event_no'       => $couponId,
				'shop_no'        => '086310100000000622',
				'event_title'    => $coupon->name,
				'event_desc'     => '',
				'begin_date'     => currentTime('DATE_1', strtotime($coupon->begin_date)),
				'end_date'       => currentTime('DATE_1', strtotime($coupon->end_date)),
				'event_link'     => $coupon->external_link,
				'event_rule'     => $coupon->coupon_desc ? $coupon->coupon_desc : '活动规则',
				'rule_desc'      => $coupon->recommend_desc ? $coupon->recommend_desc : '规则描述',
				'spec_bank_flag' => 1,
				'event_status'   => 1,
			);

		$url = config_item('UNIONPAY.CREATE_EVENT');

		if ($couponStatus == 2) {
			$form = array(
					'event_no' => $couponId,
					'shop_no' => '086310100000000622',
				);

			$url = config_item('UNIONPAY.CANCEL_EVENT');
		}

		$postRes = $this->snoopy->submit($url, $form);

		$result = json_decode($postRes->results, true);

		if ($result['errcode'] != '0') log_message('info', 'couponModel 562 line - setUrl error \n\r'.var_export($result, true));

		return $result['errcode'] == '0' ? true : false;

	}

	/**
	 * 是否存在品牌id
	 * @param string $brandId 品牌id
	 */
	public function hasBrandById($brandId = false){
		$where = array(
				'id' => $brandId,
			);
		
		$queryRes = $this->db->get_where(tname('brand'), $where)->first_row();

		return count($queryRes) > 0 ? true : false;
	}

	/**
	 * 格式化优惠券数据
	 * @param array $couponData 优惠券内容
	 */
	private function _formatCouponStateList($couponData = array()){
		$date = $row = $jsonData = array();
		$total = 0;
		foreach ($couponData as $k => $v) {
			array_push($date, $v['time']);
			array_push($row, $v['total']);
			$total += $v['total'];

			$tmpData = array(
					'name'  => $v['time'],
					'value' => $v['total'],
					'color' => '#f6f9fa',
				);
			array_push($jsonData, $tmpData);
		}

		return array('labels' => $date, 'data' => $row, 'total' => $total, 'json' => $jsonData);
	}

	/**
	 * 获取优惠券状态
	 *
	 */
	private function _getCouponListWithState(){
		$cacheRes = $this->cache->get(config_item('USER_CACHE.COUPON_ID_LIST').$this->userInfo->user_id);
		if ($cacheRes) {
			return $cacheRes;
		}

		$where = array(
				'tb_brand_id' => $this->userInfo->brand_id,
			);
		
		$select = "id AS couponId, name AS title";

		$queryRes = $this->db->select($select)->order_by('begin_date', 'DESC')->get_where(tname('coupon'), $where)->result_array();

		if (count($queryRes)) {
			$couponIdArr = array();
			foreach ($queryRes as $k => $v) {
				if (!in_array($v['couponId'], $couponIdArr)) {
					array_push($couponIdArr, $v['couponId']);
				}
			}

			$cacheRes = array(
					'list'     => $queryRes,
					'couponId' => $couponIdArr,
				);

			$this->cache->save(config_item('USER_CACHE.SHOPLIST').$this->userInfo->user_id, $cacheRes);
		}

		return isset($cacheRes) ? $cacheRes : array();
	}

	/**
	 * 将日期格式化成字符
	 * @param date $start
	 * @param date $end
	 */
	private function _formatDateToStr($start, $end){
		return date('Y/m/d', strtotime($start)).' - '.date('Y/m/d', strtotime($end));
	}

	/**
	 * 将时间转成日期
	 * @param date $date
	 * @param time $time 
	 */
	private function _formatTimeToDate($date, $time){
		$date = date('Y-m-d', strtotime($date));
		return $date.' '.$time;
	}

	/**
	 * 格式化有效期、领取期
	 * @param string $dateTime 2015/05/27 - 2015/05/28
	 */
	private function _formatExpireDate($dateTime = false){

		$date = explode(' - ', $dateTime);

		$date = array(
				strtotime($date[0]),
				strtotime($date[1]),
			);

		if ($date[0] > $date[1]) {
			$dateRes = array(
					'start' => currentTime('', $date[1]),
					'end'   => currentTime('', $date[0]),
				);
		}else{
			$dateRes = array(
					'start' => currentTime('', $date[0]),
					'end'   => currentTime('', $date[1]),
				);
		}

		return $dateRes;
	}

	/**
	 * 将说明文字转成JSON
	 * @param TEXT $text 文本内容
	 */
	private function _formatGuideToJSON($text = false){

		$textArr = explode('<br />', nl2br(strip_tags($text)));

		$returnText = array();

		foreach ($textArr as $k => $v) {
			if (empty($v)) {
				continue;
			}

			array_push($returnText, trim($v));
		}

		return json_encode($returnText);
	}

	/**
	 * 优惠券查询条件
	 * @param string $where 查询条件
	 */
	private function _checkUserWhere($where = false){

		if ($this->userInfo->role_id == 1) {
			if (!empty($where) && is_string($where)) return $where;
			return ' AND a.is_delete = 0 ';
		}

		if (empty($this->userInfo->brand_id)) {
			return false;
		}

		$whereBrand = "a.tb_brand_id = '".$this->userInfo->brand_id."' ";

		if (empty($where)) {
			return " AND ".$whereBrand." AND a.is_delete = 0 ";
		}

		return $where .= " AND ".$whereBrand." AND a.is_delete = 0 ";
	}

}
