<?php $this->load->view('public/header');?>

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
							<li class="active"><?php echo $this->lang->line('TEXT_COUPON_LIST');?></li>
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
														<th><?php echo $this->lang->line('TEXT_CITY_NAME');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_RECEIVECOUNT');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_USECOUNT');?></th>
														<th><?php echo $this->lang->line('TEXT_COUPON_SHOPCOUNT');?></th>
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
															<a href="#"><?php echo $v->title;?></a>
														</td>
														<td><?php echo $v->expire;?></td>
														<td><?php echo $v->status;?></td>
														<td><?php echo $v->cityName;?></td>
														<td><?php echo $v->received;?></td>
														<td><?php echo $v->used;?></td>
														<td><?php echo $v->mallCount;?></td>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<button class="btn btn-xs btn-success">
																	<i class="icon-ok bigger-120"></i>
																</button>

																<button class="btn btn-xs btn-info">
																	<i class="icon-edit bigger-120"></i>
																</button>

																<button class="btn btn-xs btn-danger">
																	<i class="icon-trash bigger-120"></i>
																</button>

																<button class="btn btn-xs btn-warning">
																	<i class="icon-flag bigger-120"></i>
																</button>
															</div>

															<div class="visible-xs visible-sm hidden-md hidden-lg">
																<div class="inline position-relative">
																	<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">
																		<i class="icon-cog icon-only bigger-110"></i>
																	</button>

																	<ul class="dropdown-menu dropdown-only-icon dropdown-yellow pull-right dropdown-caret dropdown-close">
																		<li>
																			<a href="#" class="tooltip-info" data-rel="tooltip" title="View">
																				<span class="blue">
																					<i class="icon-zoom-in bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" class="tooltip-success" data-rel="tooltip" title="Edit">
																				<span class="green">
																					<i class="icon-edit bigger-120"></i>
																				</span>
																			</a>
																		</li>

																		<li>
																			<a href="#" class="tooltip-error" data-rel="tooltip" title="Delete">
																				<span class="red">
																					<i class="icon-trash bigger-120"></i>
																				</span>
																			</a>
																		</li>
																	</ul>
																</div>
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
		<script src="<?php echo config_item('html_url');?>js/fuelux/fuelux.spinner.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/bootstrap-datepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/bootstrap-timepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/moment.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/daterangepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.inputlimiter.1.3.1.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/fuelux/fuelux.wizard.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.validate.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/additional-methods.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/bootbox.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.maskedinput.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/select2.min.js"></script>
		
		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<!-- inline scripts related to this page -->

		<script type="text/javascript">
			jQuery(function($) {
				$('#citySelect').on('change', function(){
					var areaHTML_1 = '<?php echo $bjAreaList;?>';
						areaHTML_2 = '<?php echo $shAreaList?>';
						areaHTML_3 = '<?php echo $gzAreaList;?>';
						areaHTML = '';

					switch(this.value){
						case ('1'):
						areaHTML = areaHTML_1;
						break;
						case ('2'):
						areaHTML = areaHTML_2;
						break;
						case ('3'):
						areaHTML = areaHTML_3;
						break;
						default:
						areaHTML = areaHTML_2;
						break;
					}

					$('#areaSelect').html(areaHTML);
				});

				$('#areaSelect').on('change', function(){
					var areaSelect = '<?php echo json_encode($shopList);?>';
						shopListHTML = '';
						areaSelectValue = this.value;

					$.each($.parseJSON(areaSelect), function(k, v){
						// if (!$('#citySelect').val() || !this.value) {
						// 	return false;
						// }

						if (v.areaName == areaSelectValue) {
							shopListHTML += '<tr><td class="center"><label><input type="checkbox" class="ace" name="mallID[]" value="'+v.mallID+'"><span class="lbl"></span></label><\/td>';
							shopListHTML += '<td>'+v.cityName+'<\/td>';
							shopListHTML += '<td>'+v.areaName+'</td>';
							shopListHTML += '<td><a href="#">'+v.mallName+'</a></td>';
							shopListHTML += '<td><a href="#">'+v.address+'</a></td>';
							shopListHTML += '<\/tr>';
						}
					});

					$('#shopListHTML').html(shopListHTML);
				});

				// 数字选择
				$('#couponSum').ace_spinner({value:0,min:0,max:9999,step:10, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
				.on('change', function(){
					//alert(this.value)
				});

				$('#couponEveryoneSum').ace_spinner({value:0,min:0,max:9999,step:1, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
				.on('change', function(){
					//alert(this.value)
				});

				// 日期选择
				$('input[name=couponExpireDate]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});
				$('input[name=couponReceiveDate]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});

				$('.date-picker').datepicker({autoclose:true}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				
				$('#couponUseTimeStart').timepicker({
					minuteStep: 1,
					showSeconds: true,
					showMeridian: false
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				$('#couponUseTimeEnd').timepicker({
					minuteStep: 1,
					showSeconds: true,
					showMeridian: false
				}).next().on(ace.click_event, function(){
					$(this).prev().focus();
				});

				// 字数限制
				$('#couponTitle').inputlimiter({
					remText: '<?php echo $this->lang->line("TEXT_COUPON_TITLE_LENGTH")?>',
					limitText: '<?php echo $this->lang->line("TEXT_COUPON_TITLE_LENGTH_MAX")?>'
				});

				$('table th input:checkbox').on('click' , function(){
					var that = this;
					$(this).closest('table').find('tr > td:first-child input:checkbox')
					.each(function(){
						this.checked = that.checked;
						$(this).closest('tr').toggleClass('selected');
					});

				});

				$('[data-rel=tooltip]').tooltip();
			
				$(".select2").css('width','200px').select2({allowClear:true})
				.on('change', function(){
					$(this).closest('form').validate().element($(this));
				}); 
			
				//documentation : http://docs.jquery.com/Plugins/Validation/validate
			
				$('#addCoupon-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						couponTitle: {
							required:true,
							maxlength:40,
						},
						couponType: {
							required:true
						},
						couponMoney: {
							required:true
						},
						// couponEveryoneSum: {
						// 	required:true
						// },
						// couponSum: {
						// 	required:true
						// },
						couponExpireDate: {
							required:true
						},
						couponReceiveDate: {
							required:true
						}
					},
			
					messages: {
						couponTitle: {
							required:"<?php echo $this->lang->line('ERR_COUPON_TITLE');?>",
							maxlength:"<?php echo $this->lang->line('ERR_COUPON_TITLE_LENGTH');?>"
						},
						couponType: {
							required:"<?php echo $this->lang->line('ERR_COUPON_TYPE');?>"
						},
						couponMoney: {
							required:"<?php echo $this->lang->line('ERR_COUPON_MONEY');?>"
						},
						// couponEveryoneSum: {
						// 	required:"<?php echo $this->lang->line('ERR_COUPON_EXPIRE_DATE');?>"
						// },
						// couponSum: {
						// 	required:"<?php echo $this->lang->line('ERR_COUPON_RECEIVEDATE');?>"
						// },
						couponExpireDate: {
							required:"<?php echo $this->lang->line('ERR_COUPON_EXPIRE_DATE');?>"
						},
						couponReceiveDate: {
							required:"<?php echo $this->lang->line('ERR_COUPON_RECEIVEDATE');?>"
						}
					},
			
					invalidHandler: function (event, validator) { //display error alert on form submit   
						$('.alert-danger', $('.login-form')).show();
					},
			
					highlight: function (e) {
						$(e).closest('.form-group').removeClass('has-info').addClass('has-error');
					},
			
					success: function (e) {
						$(e).closest('.form-group').removeClass('has-error').addClass('has-info');
						$(e).remove();
					},
			
					errorPlacement: function (error, element) {
						if(element.is(':checkbox') || element.is(':radio')) {
							var controls = element.closest('div[class*="col-"]');
							if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
							else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
						}
						else if(element.is('.select2')) {
							error.insertAfter(element.siblings('[class*="select2-container"]:eq(0)'));
						}
						else if(element.is('.chosen-select')) {
							error.insertAfter(element.siblings('[class*="chosen-container"]:eq(0)'));
						}
						else error.insertAfter(element.parent());
					},
			
					submitHandler: function (form) {
					},
					invalidHandler: function (form) {
					}
				});

				$('#modal-wizard .modal-header').ace_wizard();
				$('#modal-wizard .wizard-actions .btn[data-dismiss=modal]').removeAttr('disabled');
			})

			function subAddCouponForm(){
				if(!$('#addCoupon-form').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/addCoupon');?>",
					data:$('#addCoupon-form').serialize(),
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							return true;
						}
					}
				});
			}

			function subMallForm(){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/addCouponMall');?>",
					data:$('#mallForm').serialize(),
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}

						return true;
					}
				});
			}
		</script>
	</body>
</html>

