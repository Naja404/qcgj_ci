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
								<form class="form-horizontal" role="form" id="addBrand-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="nameZh"> <?php echo $this->lang->line('TEXT_NAME_ZH');?> </label>

										<div class="col-sm-9">
											<input type="text" name="nameZh" id="nameZh" placeholder="<?php echo $this->lang->line('PLACEHOLDER_NAME_ZH');?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="nameEn"> <?php echo $this->lang->line('TEXT_NAME_EN');?> </label>

										<div class="col-sm-9">
											<input type="text" name="nameEn" id="nameEn" placeholder="<?php echo $this->lang->line('PLACEHOLDER_NAME_EN');?>"/>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandLogo"> <?php echo $this->lang->line('TEXT_LOGO');?> </label>

										<div class="col-sm-9">
											<input type="file" name="brandLogo" id="brandLogo" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandShow"> <?php echo $this->lang->line('TEXT_LOGO_SHOW');?> </label>

										<div class="col-sm-9">
											<input type="file" name="brandShow" id="brandShow" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="summary"> <?php echo $this->lang->line('TEXT_DESCRIPTION');?> </label>

										<div class="col-sm-9">
											<textarea name="summary" id="summary"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> <?php echo $this->lang->line('TEXT_DESCRIPTION');?> </label>

										<div class="col-sm-9">
												<?php foreach ($brandCate as $k => $v) :?>
												<label>
													<input type="checkbox" name="category[]" class="ace" value="<?php echo $v->id;?>">
													<span class="lbl"><?php echo $v->name;?></span>
												</label>&nbsp;&nbsp;
												<?php if (($k+1) % 4 == 0) echo '<br>';?>
												<?php endforeach; ?>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="searchMall"> <?php echo $this->lang->line('TEXT_SHOPMALL');?> </label>

										<div class="col-sm-9">
												<input type="type" name="searchMall" id="searchMall" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SEARCH_MALL');?>">
										</div>

										<div class="col-sm-9" id="searchMallRes">
												<br>
										</div>
									</div>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subAddBrand()">
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

				$("#searchMall").keyup(function(){
					$.ajax({
			      		type:"POST",
			      		url:"<?php echo site_url('Brand/searchMall');?>",
			      		data:{mall:$("#searchMall").val()},
			      		success:function(data){
			      			if (data.status != 0) { return false;}
			      			
			      			var sourceData = new Array();
			      				mallHtml = '';

			      			$.each(data.list, function(k, v){
			      				sourceData.push(v);
			      			});

						    $( "#searchMall" ).autocomplete({
						      source: sourceData,
						      select:function(event, ui){
						      		mallHtml = '<div id="mall_'+ui.item.id+'"><label><span class="ace">'+ui.item.label+'</span></label><input type="hidden" name="mallId[]" value="'+ui.item.id+'"><input type="text" class="ace" name="floor[]" placeholder="<?php echo $this->lang->line("PLACEHOLDER_FLOOR");?>"><a onclick="delMall(\'mall_'+ui.item.id+'\')"><?php echo $this->lang->line("BTN_DELETE");?></a><br><br></div>';
						      		$("#searchMallRes").append(mallHtml);
						      },
						      close:function(event, ui){
						      	$('#searchMall').val("");
						      }
						  });
						
			      		}
					});
				});

				$('#addBrand-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						nameZh: {
							required:true
						},
						'category[]':{
							required:true
						}
					},
			
					messages: {
						nameZh: {
							required:"<?php echo $this->lang->line('ERR_NAME_ZH');?>",
						},
						'category[]':{
							required:"<?php echo $this->lang->line('ERR_CHECKBOX_MIN');?>"
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
						console.log(error);
						error.insertAfter(element.parent().children());
					}
				});

			});
			
			function delMall(id){
				$("#"+id).remove();
			}

			function subAddBrand(){
				
				if(!$('#addBrand-form').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Brand/addBrand');?>",
					data:$('#addBrand-form').serialize(),
					success:function(data){
						if (data.status == '0') {
							window.location.href = "<?php echo site_url('Brand/listView');?>";
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
