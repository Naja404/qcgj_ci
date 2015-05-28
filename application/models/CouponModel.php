<?php
/**
 * 优惠券管理模型
 */

class CouponModel extends CI_Model {
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
	 * 新建优惠券
	 * @param array $couponData 优惠券数组数据
	 */
	public function addCoupon($couponData = array()){
		$couponInsertData = array(
				'coupon_id'   => makeUUID(),
				'name'        => $couponData['couponTitle'],
				'type'        => $couponData['couponType'],
				'price'       => $couponData['couponMoney'] == 2 ? floatval($couponData['couponMoneyNum']) : 0,
				'create_time' => currentTime(),
				'update_time' => currentTime(),
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

		// TODO 创建优惠券图片

		// TODO 创建优惠券代码
		$this->returnRes['error'] = false;
		return $this->returnRes;
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
}
