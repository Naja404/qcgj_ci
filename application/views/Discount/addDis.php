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
								<a href="{:U('Discount/disList')}"><?php echo $this->lang->line('TITLE_DISCOUNT_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_DISCOUNT_ADD');?></li>
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
												<h4 class="lighter"><span style="color:red;"><?php echo isset($brandName) ? $brandName : '';?></span><?php echo $this->lang->line('TITLE_DISCOUNT_ADD');?></h4>
											</div>

											<div class="widget-body">
												<div class="widget-main">
													<hr />
													<div class="step-content row-fluid position-relative" id="step-container">
														<div class="step-pane active" id="step1">

															<form class="form-horizontal" id="addDis-form" method="post" enctype="multipart/form-data" >
																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="discountTitle"><?php echo $this->lang->line('TEXT_DISCOUNT_TITLE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" name="discountTitle" id="discountTitle" class="col-xs-12 col-sm-6" placeholder="<?php echo $this->lang->line('PLACEHOLDER_DISCOUNT_TITLE');?>"/>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="discountType"><?php echo $this->lang->line('TEXT_DISCOUNT_TYPE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<select name="discountType">
																				<?php foreach (config_item('DISCOUNT_TYPE') as $k => $v) {?>
																					<option value="<?php echo $v['key']?>"><?php echo $v['value'];?></option>
																				<?php }?>
																			</select>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="discountCate"><?php echo $this->lang->line('TEXT_DISCOUNT_CATEGORY');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<select name="discountCate">
																				<?php foreach ($discountCate as $k => $v) {?>
																					<option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
																				<?php }?>
																			</select>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="discountDate"><?php echo $this->lang->line('TEXT_DISCOUNT_DATE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<input type="text" name="discountDate" id="discountDate" class="col-xs-12 col-sm-6" placeholder="<?php echo $this->lang->line('PLACEHOLDER_DISCOUNT_DATE');?>"/>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="discountDescription"><?php echo $this->lang->line('TEXT_DISCOUNT_DESCRIPTION');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<textarea class="input-xlarge" name="discountDescription" id="discountDescription" placeholder="<?php echo $this->lang->line('PLACEHOLDER_DISCOUNT_DESCRIPTION');?>"></textarea>
																		</div>
																	</div>
																</div>

																<div class="form-group">
																	<label class="control-label col-xs-12 col-sm-3 no-padding-right" for="discountImg"><?php echo $this->lang->line('TEXT_DISCOUNT_IMAGE');?>:</label>

																	<div class="col-xs-12 col-sm-9">
																		<div class="clearfix">
																			<?php foreach ($brandImg as $k => $v) {?>
																				<img src="<?php echo config_item('image_url').$v;?>" width="100px;" height="100px;">
																				<input type="radio" name="discountImg" value="<?php echo $v;?>" />
																				<br>
																				<br>
																			<?php }?>
																		</div>
																	</div>
																</div>
																
																<div class="hr hr-dotted"></div>
																<div class="form-group">
																	<select name="cityName" id="citySelect">
																		<option value=""><?php echo $this->lang->line('TEXT_CITY');?></option>
																		<?php foreach ($cityList as $k => $v) {?>
																		<option value="<?php echo $v['cityId'];?>"><?php echo $v['name'];?></option>
																		<?php }?>
																	</select>
																	<select name="areaName" id="areaSelect">
																		<option value=""><?php echo $this->lang->line('TEXT_AREA');?></option>
																		<?php foreach ($areaList as $k) {?>
																		<option value="<?php echo $k;?>"><?php echo $k;?></option>
																		<?php }?>
																	</select>
																</div>
																<div class="form-group">
																	<div class="row-fluid">
																		<div class="col-xs-12">
																			<div class="table-responsive">
																				<table class="table table-striped table-bordered table-hover">
																					<thead>
																						<tr>
																							<th class="center">
																								<label>
																									<input type="checkbox" class="ace" id="cityListCheck"/>
																									<span class="lbl"></span>
																								</label>
																							</th>
																							<th><?php echo $this->lang->line('TEXT_CITY_NAME');?></th>
																							<th>区域</th>
																							<th><?php echo $this->lang->line('TEXT_MALL_NAME');?></th>
																							<th><?php echo $this->lang->line('TEXT_ADDRESS');?></th>
																						</tr>
																					</thead>

																					<tbody id="shopListHTML">
																						<?php foreach ($shopList as $k => $v):?>
																						<tr>
																							<td class="center">
																								<label>
																									<input type="checkbox" class="ace" name="mallID[]" value="<?php echo $v['mallID']?>"/>
																									<span class="lbl"></span>
																								</label>
																							</td>
																							<td><?php echo $v['cityName'];?></td>
																							<td><?php echo $v['districtName'];?></td>
																							<td>
																								<a href="#"><?php echo $v['mallName'];?></a>
																							</td>
																							<td>
																								<a href="#"><?php echo $v['address'];?></a>
																							</td>
																						</tr>
																						<?php endforeach;?>
																					</tbody>
																				</table>
																			</div><!-- /.table-responsive -->

																		</div><!-- /span -->
																	</div>
																</div>
																<?php if(isset($brandId) && !empty($brandId)){?>
																<input type="hidden" name="brandId" value="<?php echo $brandId;?>" >
																<?php }?>
																<div class="space-8"></div>

															</form>
														</div>

													</div>

													<hr />
													<div class="row-fluid wizard-actions">
														<button class="btn btn-success" onclick="sudAddDiscountForm();">
															<?php echo $this->lang->line('BTN_SUBMIT');?>
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
		<script src="<?php echo config_item('html_url');?>js/jquery.validate.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/moment.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/date-time/daterangepicker.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/bootbox.min.js"></script>
		
		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {

				// 日期选择
				$('input[name=discountDate]').daterangepicker().prev().on(ace.click_event, function(){
					$(this).next().focus();
				});

				var areaSelect = '<?php echo json_encode($shopList);?>';
					areaSelectValue = this.value;

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
						areaHTML = "<option><?php echo $this->lang->line('TEXT_SELECT_CITY_NAME');?></option>";
						shopListHTML = '';
						$.each($.parseJSON(areaSelect), function(k, v){
							shopListHTML += '<tr><td class="center"><label><input type="checkbox" class="ace" name="mallID[]" value="'+v.mallID+'"><span class="lbl"></span></label><\/td>';
							shopListHTML += '<td>'+v.cityName+'<\/td>';
							shopListHTML += '<td>'+v.districtName+'</td>';
							shopListHTML += '<td><a href="#">'+v.mallName+'</a></td>';
							shopListHTML += '<td><a href="#">'+v.address+'</a></td>';
							shopListHTML += '<\/tr>';
						});

						$('#shopListHTML').html(shopListHTML);
						break;
					}

					$('#areaSelect').html(areaHTML);
				});

				$('#areaSelect').on('change', function(){
					var areaSelectValue = this.value;
						shopListHTML = '';
					$('#cityListCheck').attr("checked",false);
					$.each($.parseJSON(areaSelect), function(k, v){
						if (v.districtName == areaSelectValue) {
							shopListHTML += '<tr><td class="center"><label><input type="checkbox" class="ace" name="mallID[]" value="'+v.mallID+'"><span class="lbl"></span></label><\/td>';
							shopListHTML += '<td>'+v.cityName+'<\/td>';
							shopListHTML += '<td>'+v.districtName+'</td>';
							shopListHTML += '<td><a href="#">'+v.mallName+'</a></td>';
							shopListHTML += '<td><a href="#">'+v.address+'</a></td>';
							shopListHTML += '<\/tr>';
						}
					});

					$('#shopListHTML').html(shopListHTML);
				});

				$('#addDis-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						discountTitle: {
							required:true
						},
						discountDate: {
							required:true
						},
						discountDescription: {
							required:true
						}
					},
			
					messages: {
						discountTitle: {
							required:"<?php echo $this->lang->line('EMPTY_DISCOUNT_TITLE');?>"
						},
						discountDate: {
							required:"<?php echo $this->lang->line('EMPTY_DISCOUNT_DATE');?>"
						},
						discountDescription:{
							required:"<?php echo $this->lang->line('EMPTY_DISCOUNT_DESCRIPTION');?>"
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

			})
			
			function sudAddDiscountForm(){
				if(!$('#addDis-form').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Discount/addDis');?>",
					data:$('#addDis-form').serialize(),
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							bootbox.dialog({
								message: "<?php echo $this->lang->line('TEXT_DISCOUNT_ADD_SUCCESS');?>", 
								buttons: {
									"goList" : {
										"label" : "<?php echo $this->lang->line('TITLE_DISCOUNT_LIST');?>",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.href = "<?php echo site_url('Discount/disList');?>";
										}
									},
									"continue" : {
										"label" : "<?php echo $this->lang->line('TEXT_CONTINUE_DISCOUNT_ADD');?>",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.href = "<?php echo site_url('Discount/addDis');?>";
										}
									}
								}
							});
							return true;
						}
					}
				});
			}
		</script>
	</body>
</html>

