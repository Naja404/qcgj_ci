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
							<li class="active"><?php echo $this->lang->line('TEXT_COUPON_ADDCOUPON');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="row">
							<div class="col-xs-12">
								<div class="row">
									<div class="col-xs-12">

								<div class="row-fluid">
									<div class="span12">
										<div class="widget-box">
											<div class="widget-header widget-header-blue widget-header-flat">
												<h4 class="lighter"><?php echo $this->lang->line('TEXT_COUPON_ADDCOUPON');?></h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<div id="fuelux-wizard" class="row-fluid" data-target="#step-container">
														<ul class="wizard-steps">
															<li data-target="#step1" class="active">
																<span class="step">1</span>
																<span class="title"><?php echo $this->lang->line('TEXT_COUPON_FORM_STEP_1');?></span>
															</li>

															<li data-target="#step2">
																<span class="step">2</span>
																<span class="title"><?php echo $this->lang->line('TEXT_COUPON_FORM_STEP_2');?></span>
															</li>

															<li data-target="#step3">
																<span class="step">3</span>
																<span class="title"><?php echo $this->lang->line('TEXT_COUPON_FORM_STEP_3');?></span>
															</li>
														</ul>
													</div>

													<hr />
													<div class="step-content row-fluid position-relative" id="step-container">
														<div class="step-pane active" id="step1">

															<form class="form-horizontal" id="addCoupon-form" method="post" enctype="multipart/form-data" >
																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponTitle"><?php echo $this->lang->line('TEXT_COUPON_TITLE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" name="couponTitle" id="couponTitle" class="col-xs-12 col-sm-6" maxlength="40" placeholder="<?php echo $this->lang->line('TEXT_COUPON_TITLE_PLACEHOLDER');?>"/>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_TYPE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="row">
																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponType" value="1" type="radio" class="ace" />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_VOUCHERS');?></span>
																			</label>
																		</div>

																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponType" value="2" type="radio" class="ace" />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_DISCOUNT');?></span>
																			</label>
																		</div>

																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponType" value="3" type="radio" class="ace" />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_DELIVERY');?></span>
																			</label>
																		</div>

																		</div>
																	</div>
																</div>

																<div class="space-2"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_MONEY');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="row">
																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponMoney" value="1" type="radio" class="ace" />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_FREE');?></span>
																			</label>
																		</div>

																		<div class="col-xs-3">
																			<label class="blue">
																				<input name="couponMoney" value="2" type="radio" class="ace" />
																				<span class="lbl"><?php echo $this->lang->line('TEXT_COUPON_TOLL');?></span>
																				<div class="form-inline">
																				<input type="text" name="couponMoneyNum" class="input-small" placeholder="如:50.00" />
																				</div>
																			</label>

																		</div>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponSum"><?php echo $this->lang->line('TEXT_COUPON_SUM');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" class="input-mini" id="couponSum" name="couponSum"/>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponEveryoneSum"><?php echo $this->lang->line('TEXT_COUPON_EVERYONE_SUM');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" class="input-mini" id="couponEveryoneSum" name="couponEveryoneSum"/>
																		</div>
																	</div>
																</div>

																<div class="hr hr-dotted"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponExpireDate"><?php echo $this->lang->line('TEXT_COUPON_EXPIRE');?>:</label>
																	<div class="col-xs-12 col-sm-9">
																		<div class="input-group">
																			<span class="input-group-addon">
																				<i class="icon-calendar bigger-110"></i>
																			</span>

																			<input type="text" name="couponExpireDate" id="couponExpireDate" class="col-xs-6 col-sm-4"/>
																		</div>
																	</div>
																</div>

																<div class="space-2"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponReceiveDate"><?php echo $this->lang->line('TEXT_COUPON_RECEIVE');?>:</label>
																	<div class="col-xs-12 col-sm-9">
																		<div class="input-group">
																			<span class="input-group-addon">
																				<i class="icon-calendar bigger-110"></i>
																			</span>

																			<input type="text" name="couponReceiveDate" id="couponReceiveDate" class="col-xs-6 col-sm-4"/>
																		</div>
																	</div>
																</div>

																<div class="space-2"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponUseTime"><?php echo $this->lang->line('TEXT_COUPON_USE_TIME');?>:</label>

																	<div class="col-xs-12 col-sm-8">
																		<div class="row">
																			<div class="col-xs-6">
																			<div class="input-group bootstrap-timepicker">
																				<span class="input-group-addon">
																					<i class="icon-time bigger-110"></i>
																				</span>
																				<input id="couponUseTimeStart" type="text" name="couponUseTimeStart" class="col-xs-6 col-sm-4" />
																			</div>
																			</div>

																			<div class="col-xs-6">
																				<div class="input-group bootstrap-timepicker">
																					<span class="input-group-addon">
																						<i class="icon-time bigger-110"></i>
																					</span>
																					<input id="couponUseTimeEnd" type="text" name="couponUseTimeEnd" class="col-xs-6 col-sm-4" />
																				</div>
																			</div>

																		</div>
																	</div>
																</div>

																<div class="hr hr-dotted"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponUseGuide"><?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="couponUseGuide" id="couponUseGuide" placeholder="<?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE_PLACEHOLDER');?>"></textarea>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponNotice"><?php echo $this->lang->line('TEXT_COUPON_NOTICE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="couponNotice" id="couponNotice" placeholder="<?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE_PLACEHOLDER');?>"></textarea>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="couponVerification"><?php echo $this->lang->line('TEXT_COUPON_VERIFICATION');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="couponVerification" id="couponVerification" placeholder="<?php echo $this->lang->line('TEXT_COUPON_USE_GUIDE_PLACEHOLDER');?>"></textarea>
																		</div>
																	</div>
																</div>

																<div class="hr hr-dotted"></div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right"><?php echo $this->lang->line('TEXT_COUPON_CODE_TYPE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div>
																			<label>
																				<input name="couponAutoCode" value="1" type="checkbox" class="ace" id="couponAutoCode"/>
																				<span class="lbl" for="couponAutoCode"><?php echo $this->lang->line('TEXT_COUPON_AUTO_CODE');?></span>
																			</label>
																		</div>
																	</div>
																</div>
																<div class="space-8"></div>
															</form>
														</div>

														<div class="step-pane" id="step2">
															<div class="row-fluid">
									<div class="col-xs-12">
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
														<th><?php echo $this->lang->line('TEXT_ROLE_USERNAME');?></th>
														<th><?php echo $this->lang->line('TEXT_ROLE_RULE');?></th>
														<th><?php echo $this->lang->line('TEXT_CREATED_TIME');?></th>
														<th><?php echo $this->lang->line('TEXT_STATUS');?></th>
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
													</tr>
												</thead>

												<tbody>
													<?php foreach ($roleList as $v):?>
													<tr>
														<td class="center">
															<label>
																<input type="checkbox" class="ace" />
																<span class="lbl"></span>
															</label>
														</td>

														<td>
															<a href="#"><?php echo $v->name;?></a>
														</td>
														<td><?php echo $v->role_name;?></td>
														<td class="hidden-480"><?php echo date('Y-m-d H:i:s', $v->created_time);?></td>

														<td class="hidden-480">
															<?php if ($v->status == 1){?>
																<span class="label label-error"><?php echo $this->lang->line('TEXT_STATUS_NORMAL');?></span>
															<?php }else{ ?>
																<span class="label label-success"><?php echo $this->lang->line('TEXT_STATUS_STOP');?></span>
															<?php }?>
															
														</td>

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
										<?php echo $pagination;?>
									</div><!-- /span -->
															</div>
														</div>

														<div class="step-pane" id="step3">
															<div class="center">
																<h3 class="blue lighter">This is step 3</h3>
															</div>
														</div>

													</div>

													<hr />
													<div class="row-fluid wizard-actions">
														<button class="btn btn-prev">
															<i class="icon-arrow-left"></i>
															<?php echo $this->lang->line('BTN_PREV');?>
														</button>

														<button class="btn btn-success btn-next" data-last="Finish ">
															<?php echo $this->lang->line('BTN_NEXT');?>
															<i class="icon-arrow-right icon-on-right"></i>
														</button>
													</div>
												</div><!-- /widget-main -->
											</div><!-- /widget-body -->
										</div>
									</div>
								</div>

									</div><!-- /span -->
								</div>
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

				$('#id-input-file-2').ace_file_input({
					no_file:'<?php echo $this->lang->line("TEXT_COUPON_CHOOSEN_FILE");?>',
					btn_choose:'<?php echo $this->lang->line("BTN_CHOOSE");?>',
					btn_change:'<?php echo $this->lang->line("BTN_CHANGE");?>',
					droppable:false,
					onchange:null,
					thumbnail:false //| true | large
					// whitelist:'gif|png|jpg|jpeg'
					//blacklist:'exe|php'
					//onchange:''
					//
				});

				$('[data-rel=tooltip]').tooltip();
			
				$(".select2").css('width','200px').select2({allowClear:true})
				.on('change', function(){
					$(this).closest('form').validate().element($(this));
				}); 
			
			
				var $validation = true;
				$('#fuelux-wizard').ace_wizard().on('change' , function(e, info){
					if(info.step == 1 && $validation) {
						if(!$('#addCoupon-form').valid()){
							// return false;
						}else{
							// subAddCouponForm();
						}
					}
				}).on('finished', function(e) {
					bootbox.dialog({
						message: "Thank you! Your information was successfully saved!", 
						buttons: {
							"success" : {
								"label" : "OK",
								"className" : "btn-sm btn-primary"
							}
						}
					});
				}).on('stepclick', function(e){
					//return false;//prevent clicking on steps
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
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Coupon/addCoupon');?>",
					data:$('#addCoupon-form').serialize(),
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							// $('#addCoupon-form').reset();
						}
					}
				});
			}
		</script>
	</body>
</html>

