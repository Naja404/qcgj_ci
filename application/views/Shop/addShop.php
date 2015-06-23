<?php $this->load->view('Public/header');?>

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/ace-skins.min.css" />

		<link rel="stylesheet" href="<?php echo config_item('html_url');?>css/jquery-ui-1.10.3.full.min.css" />


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
								<a href="<?php echo config_item('base_url');?>"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="<?php echo site_url('Shop/shopList');?>"><?php echo $this->lang->line('TEXT_SHOP_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_ADD_SHOP');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="addManagerForm">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="fieldMagerName"> <?php echo $this->lang->line('TEXT_CITY_NAME');?> </label>

										<div class="col-sm-9">
											<select  name="cityName">
												<?php foreach($cityList as $v){?>
												<option value="<?php echo $v->cityId;?>"><?php echo $v->cityName;?></option>
												<?php };?>
											</select>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="fieldPasswd"> <?php echo $this->lang->line('TEXT_AREA_NAME');?> </label>

										<div class="col-sm-9">
											<select  name="areaName">
												<?php foreach($areaList as $v){?>
												<option value="<?php echo $v->areaId;?>"><?php echo $v->areaName;?></option>
												<?php };?>
											</select>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="fieldConfirmPasswd"> <?php echo $this->lang->line('TEXT_MALL_NAME');?> </label>

										<div class="col-sm-9">
											<select  name="cityName">
												<?php foreach($cityList as $v){?>
												<option value="<?php echo $v->cityId;?>"><?php echo $v->cityName;?></option>
												<?php };?>
											</select>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="fieldSelectShop"><?php echo $this->lang->line('TEXT_ADDRESS');?></label>
										<div class="col-sm-9">
											<select  id="fieldSelectShop" name="mallID">
												<?php foreach($shopList as $v){?>
												<option value="<?php echo $v->mallID;?>"><?php echo $v->shopName;?></option>
												<?php };?>
											</select>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subAddManager();subAddManager">
												<i class="icon-ok bigger-110"></i>
												<?php echo $this->lang->line('BTN_SUBMIT');?>
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="reset">
												<i class="icon-undo bigger-110"></i>
												<?php echo $this->lang->line('BTN_RESET');?>
											</button>
										</div>
									</div>
								</form>
							</div>
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

		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.validate.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/bootbox.min.js"></script>

		<script type="text/javascript">
			jQuery(function($) {
			$('#addManagerForm').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						managerName: {
							required:true,
							rangelength:[5, 32],
							email:true
						},
						passwd: {
							required:true,
							minlength:6
						},
						confirmPasswd: {
							required:true,
							minlength:6,
							equalTo:"#fieldPasswd"
						}
					},
			
					messages: {
						managerName: {
							required:"<?php echo $this->lang->line('ERR_ENTER_MANAGER_NAME');?>",
							rangelength:"<?php echo $this->lang->line('ERR_MANAGER_NAME_LENGTH');?>",
							email:"<?php echo $this->lang->line('ERR_MANAGER_NAME_FORMAT');?>"
						},
						passwd: {
							required:"<?php echo $this->lang->line('ERR_ENTER_PASSWD');?>",
							minlength:"<?php echo $this->lang->line('ERR_PASSWD_LENGTH');?>"
						},
						confirmPasswd: {
							required:"<?php echo $this->lang->line('ERR_ENTER_CONFIRM_PASSWD');?>",
							minlength:"<?php echo $this->lang->line('ERR_CONFIRM_PASSWD_LENGTH');?>",
							equalTo:"<?php echo $this->lang->line('ERR_CONFIRM_PASSWD_NOTSAME');?>"
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
			});



			function subAddManager(){
				if(!$('#addManagerForm').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Shop/addShopManager');?>",
					data:$('#addManagerForm').serialize(),
					success:function(data){
						if (data.status) {
							alert(data.msg);
							return false;
						}else{
							bootbox.dialog({
								message: "<?php echo $this->lang->line('TEXT_ADDMANAGER_SUCCESS');?>", 
								buttons: {
									"goList" : {
										"label" : "<?php echo $this->lang->line('TEXT_GO_MANAGERLIST');?>",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.href = "<?php echo site_url('Shop/managerList');?>";
										}
									},
									"continue" : {
										"label" : "<?php echo $this->lang->line('TEXT_CONTINUE_ADDMANAGER');?>",
										"className" : "btn-sm btn-primary",
										callback: function(){
											window.location.reload();
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
