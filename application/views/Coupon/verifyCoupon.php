<?php $this->load->view('Public/header');?>

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-skins.min.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/datepicker.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/daterangepicker.css" />

		<!--[if lte IE 8]>
		  <link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-ie.min.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->

		<script src="<?php echo config_item('html_url');?>js/ace-extra.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="<?php echo config_item('html_url');?>js/html5shiv.js"></script>
		<script src="<?php echo config_item('html_url');?>js/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>

		<?php $this->load->view('Public/navbar');?>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-container-inner">
				<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>

				<?php $this->load->view('Public/sidebar');?>

				<div class="main-content">
					<div class="breadcrumbs" id="breadcrumbs">
						<script type="text/javascript">
							try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
						</script>

						<ul class="breadcrumb">
							<li>
								<i class="icon-home home-icon"></i>
								<a href="<?php echo site_url();?>"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="{:U('Coupon/rolelist')}"><?php echo $this->lang->line('TEXT_COUPON_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_COUPON_VERIFY');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">

								<div class="row-fluid">
									<div class="span12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th class="center">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</th>
														<th><?php echo $this->lang->line('TEXT_COUPON_TITLE');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_EXPIRE');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_STATUS');?></th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($couponList as $v):?>
													<tr>
														<td class="center">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</td>

														<td>
															<a href="#modal-review-coupon" role="button" data-toggle="modal" onclick="getCouponDetail('<?php echo $v->id;?>')"><?php echo $v->title;?></a>
														</td>
														<td><?php echo $v->expire;?></td>
														<td id="userSpan_<?php echo strEncrypt($v->id);?>"><?php echo $this->lang->line('TEXT_STATUS_'.$v->saleStatus);?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<a style="color:red;" onclick="delCoupon('<?php echo strEncrypt($v->id);?>');">
																	<i class="icon-trash bigger-120">删除</i>
																</a>
																<?php if($v->saleStatus == 0){?>
																<a onclick="saleCoupon('<?php echo strEncrypt($v->id);?>');">
																	<i id="userIcon_<?php echo strEncrypt($v->id);?>" class="icon-ok bigger-120">通过</i>
																</a>
																<input type="hidden" name="userHide_<?php echo strEncrypt($v->id);?>" value="1" />
																<?php } ?>
															</div>

														</td>
													</tr>
													<?php endforeach;?>
												</tbody>
											</table>
										</div><!-- /.table-responsive -->
									</div>
								</div>

									</div><!-- /span -->
								</div>
								<?php echo $couponListPage;?>
							</div><!-- /.col -->

						</div><!-- /.row -->

						<div id="modal-review-coupon" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header no-padding">
										<div class="table-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												<span class="white">&times;</span>
											</button>
											优惠券预览
										</div>
									</div>

									<div class="modal-body no-padding">
										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
											<tbody>
												<tr>
													<td style="width:100px;">
														优惠券标题
													</td>
													<td id="modal_name">

													</td>
												</tr>

												<tr>
													<td>
														优惠券类型
													</td>
													<td id="modal_type">

													</td>
												</tr>
												<tr>
													<td>
														优惠券金额
													</td>
													<td id="modal_price">

													</td>
												</tr>
												<tr>
													<td>
														优惠券总数
													</td>
													<td id="modal_count">

													</td>
												</tr>
												<tr>
													<td>
														每人领取上限
													</td>
													<td id="modal_limit_received">

													</td>
												</tr>
												<tr>
													<td>
														有效期
													</td>
													<td id="modal_expire_date">

													</td>
												</tr>
												<tr>
													<td>
														领取期
													</td>
													<td id="modal_received_date">

													</td>
												</tr>
												<tr>
													<td>
														使用说明
													</td>
													<td id="modal_use_note">

													</td>
												</tr>
												<tr>
													<td>
														验券说明
													</td>
													<td id="modal_check_note">

													</td>
												</tr>
												<tr>
													<td>
														优惠券码类型
													</td>
													<td id="modal_code_type">

													</td>
												</tr>
												<tr>
													<td>
														验券合作商
													</td>
													<td id="modal_partner">

													</td>
												</tr>
												<tr>
													<td>
														所在商场
													</td>
													<td id="modal_mall">

													</td>
												</tr>
											</tbody>
										</table>
									</div>

									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal" id="">
											<i class="icon-remove"></i>
											<?php echo $this->lang->line('BTN_CLOSE');?>
										</button>
									</div>
								</div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div>

					</div><!-- /.page-content -->
				</div><!-- /.main-content -->
			</div><!-- /.main-container-inner -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="icon-double-angle-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->

		<script src="<?php echo config_item('html_url');?>js/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery-ui-1.10.3.full.min.js"></script>

		<!-- <![endif]-->

		<!--[if IE]>
<script src="<?php echo config_item('html_url');?>js/jquery-1.10.2.min.js"></script>
<![endif]-->

		<!--[if !IE]> -->

		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo config_item('html_url');?>js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script type="text/javascript">
		 window.jQuery || document.write("<script src='<?php echo config_item('html_url');?>js/jquery-1.10.2.min.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo config_item('html_url');?>js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo config_item('html_url');?>js/bootstrap.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/typeahead-bs2.min.js"></script>

		<!-- page specific plugin scripts -->
		
		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">

			function getCouponDetail(couponId){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/getCouponDetail');?>",
					data:{couponId:couponId},
					success:function(data){
						if (data.status == '0') {
							$('#modal_name').html(data.data.name);
							$('#modal_type').html(data.data.typeHTML);
							$('#modal_price').html(data.data.price);
							$('#modal_count').html(data.data.limit_count_used);
							$('#modal_limit_received').html(data.data.limit_count_per_person);
							$('#modal_expire_date').html(data.data.couponExpireDate);
							$('#modal_received_date').html(data.data.couponReceiveDate);
							$('#modal_use_note').html(data.data.coupon_desc);
							$('#modal_check_note').html(data.data.recommend_desc);
							$('#modal_code_type').html(data.data.geneHTML);
							$('#modal_partner').html(data.data.partnerHTML);
							$('#modal_mall').html(data.data.mallHTML);

							return true;
						}
						alert(data.msg);
						return false;
					}
				});	
			}

			function saleCoupon(couponId){
				var couponStatus = $('input[name=userHide_'+couponId+']').val();

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/saleCoupon');?>",
					data:{couponId:couponId, status:couponStatus},
					success:function(data){
						if (data.status == '0') {
							$('#userSpan_' + couponId).html(data.html);
							$('#userIcon_'+couponId).attr("class", data.class);
							$('#userIcon_'+couponId).text(data.showText);
							$('input[name=userHide_'+couponId+']').val(data.couponStatus);
							return true;
						}
						alert(data.msg);
						return false;
					}
				});
			}

			function delCoupon(couponId){
				if (!confirm("<?php echo $this->lang->line('TEXT_CONFIRM_DEL_COUPON');?>")) {
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/delCoupon');?>",
					data:{couponId:couponId},
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							window.location.reload();
						}
					}
				});
			}

			function setCouponStatus(obj, couponId){

				var reqStatus = obj.value;

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/setCouponStatus');?>",
					data:{couponId:couponId, reqStatus:reqStatus},
					success:function(data){
						if (data.status == '0') {

							return true;
						}

						alert(data.msg);
						return false;
					}
				});
			}
		</script>
	</body>
</html>

