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
								<a href="<?php echo site_url('Role/roleList');?>"><?php echo $this->lang->line('TEXT_ROLE_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_ROLE_RULE_USER_ADD');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="addRoleUser-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="roleUsername"> <?php echo $this->lang->line('TEXT_ROLE_USERNAME');?> </label>

										<div class="col-sm-9">
											<input type="text" name="roleUsername" id="roleUsername" placeholder="<?php echo $this->lang->line('PLACEHOLDER_USERNAME');?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="passwd"> <?php echo $this->lang->line('TEXT_ROLE_PASSWD');?> </label>

										<div class="col-sm-9">
											<input type="password" name="passwd" id="passwd" placeholder="<?php echo $this->lang->line('PLACEHOLDER_PASSWORD');?>"/>
										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="confirmPasswd"> <?php echo $this->lang->line('TEXT_ROLE_CONFIRM_PASSWD');?> </label>

										<div class="col-sm-9">
											<input type="password" name="confirmPasswd" id="confirmPasswd" placeholder="<?php echo $this->lang->line('PLACEHOLDER_CONFIRM_PASSWORD');?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="roleIdSelect"><?php echo $this->lang->line('TEXT_ROLE_NAME');?></label>
										<div class="col-sm-9">
											<select name="role_id" id="roleIdSelect">
												<?php foreach($roleSelect as $v):?>
												<option value="<?php echo $v->role_id;?>">
													<?php echo $v->name;?>
												</option>
												<?php endforeach;?>
											</select>
										</div>
									</div>

									<div class="form-group" style="display:none;" id="brandDiv">
										<label class="col-sm-3 control-label no-padding-right" for="brandSelect"><?php echo $this->lang->line('TEXT_BRAND');?></label>
										<div class="col-sm-9">
											<input type="text" name="brandName" id="brandName" value="" placeholder="<?php echo $this->lang->line('TEXT_INPUT_BRAND_NAME');?>" />
										</div>
									</div>

									<div class="form-group" style="display:none;" id="mallDiv">
										<label class="col-sm-3 control-label no-padding-right" for="mallSelect"><?php echo $this->lang->line('TEXT_SHOP');?></label>
										<div class="col-sm-9">
											<select name="mallId" id="mallSelect" >
												<option><?php echo $this->lang->line('TEXT_PLASE_SELECT_MALL');?></option>
											</select>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subAddRoleUser()">
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
				$("#roleIdSelect").on('change', function(){
					if (this.value == '1') {
						$('#brandDiv').hide();
						$('#mallDiv').hide();
					}

					if (this.value == '2') {
						$('#brandDiv').show();
						$('#mallDiv').hide();
					}

					if (this.value == '3') {
						$('#brandDiv').show();
						$('#mallDiv').show();
					}
				});
				$("#brandName").keyup(function(){
					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('Role/searchBrand');?>",
			      		data:{brand:$("#brandName").val()},
			      		success:function(data){
			      			if (data.status != 0) { return false;}
			      			
			      			var sourceData = new Array();

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $( "#brandName" ).autocomplete({
						      source: sourceData,
						      select:function(event, ui){
						      	$.ajax({
						      		type:"POST",
						      		url:"<?php echo site_url('Role/searchMallByBrand');?>",
						      		data:{brand:ui.item.value},
						      		success:function(data){
						      			if (data.status == '0') {
						      				$("#mallSelect").html(data.html);
						      			}
						      		}
						      	});
						      }
						  });

			      		}
					});
				});

				$('#addRoleUser-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						roleUsername: {
							required:true,
							maxlength:20,
						},
						passwd: {
							required:true,
							minlength:6,
							maxlength:20
						},
						confirmPasswd :{
							required:true,
							equalTo: "#passwd"
						}
					},
			
					messages: {
						roleUsername: {
							required:"<?php echo $this->lang->line('ERR_ROLEUSER_NAME');?>",
							maxlength:"<?php echo $this->lang->line('ERR_ROLEUSER_NAME_LENGTH');?>"
						},
						passwd: {
							required:"<?php echo $this->lang->line('ERR_ROLEUSER_PASSWD');?>",
							minlength:"<?php echo $this->lang->line('ERR_ROLEUSER_PASSWD_MIN');?>",
							maxlength:"<?php echo $this->lang->line('ERR_ROLEUSER_PASSWD_MAX');?>"
						},
						confirmPasswd :{
							required:"<?php echo $this->lang->line('ERR_ROLEUSER_CONFIRM_PASSWD');?>",
							equalTo: "<?php echo $this->lang->line('ERR_ROLEUSER_CONFIRM_PASSWD');?>"
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

						error.insertAfter(element.parent().children());
					}
				});

			});
			function subAddRoleUser(){
				
				if(!$('#addRoleUser-form').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Role/addRoleUser');?>",
					data:$('#addRoleUser-form').serialize(),
					success:function(data){
						if (data.status == '0') {
							window.location.href = "<?php echo site_url('Role/roleList');?>";
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
