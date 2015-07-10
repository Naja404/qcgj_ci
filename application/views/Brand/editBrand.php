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
								<a href="<?php echo site_url('Brand/listView');?>"><?php echo $this->lang->line('TITLE_BRAND_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_EDIT_BRAND');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="editBrand-form">
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
											<input type="hidden" name="brandLogoPath" id="brandLogoPath" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandShow"> <?php echo $this->lang->line('TEXT_LOGO_SHOW');?> </label>

										<div class="col-sm-9">
											<input type="file" name="brandShow" id="brandShow" />
											<input type="hidden" name="brandShowPath" id="brandShowPath" />
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="summary"> <?php echo $this->lang->line('TEXT_DESCRIPTION');?> </label>

										<div class="col-sm-9">
											<textarea name="summary" id="summary"></textarea>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="category"> <?php echo $this->lang->line('TEXT_CATEGORY');?> </label>

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
										<label class="col-sm-3 control-label no-padding-right" for="style"> <?php echo $this->lang->line('TEXT_STYLE');?> </label>

										<div class="col-sm-9">
												<?php foreach ($brandStyle as $k => $v) :?>
												<label>
													<input type="checkbox" name="style[]" class="ace" value="<?php echo $v->id;?>">
													<span class="lbl"><?php echo $v->name;?></span>
												</label>&nbsp;&nbsp;
												<?php if (($k+1) % 4 == 0) echo '<br>';?>
												<?php endforeach; ?>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="age"> <?php echo $this->lang->line('TEXT_AGE');?> </label>

										<div class="col-sm-9">
												<select name="age">
													<option></option>
												<?php foreach ($brandAge as $k => $v) :?>
													<option value="<?php echo $v->id;?>"><?php echo $v->name;?></option>
												<?php endforeach; ?>
												</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="price"> <?php echo $this->lang->line('TEXT_PRICE');?> </label>

										<div class="col-sm-9">
												<select name="price">
													<option></option>
												<?php foreach ($brandPrice as $k => $v) :?>
													<option value="<?php echo $v->id;?>"><?php echo $v->name;?></option>
												<?php endforeach; ?>
												</select>
										</div>
									</div>

<!-- 									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="searchMall"> <?php echo $this->lang->line('TEXT_SHOPMALL');?> </label>

										<div class="col-sm-9">
												<input type="type" name="searchMall" id="searchMall" placeholder="<?php echo $this->lang->line('PLACEHOLDER_SEARCH_MALL');?>">
										</div>

										<div class="col-sm-9" id="searchMallRes">
												<br>
										</div>
									</div> -->

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subEditBrand()">
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
		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery.ajaxfileupload.js"></script>

		<script type="text/javascript">
		$(document).ready(function() {
			var interval;

			function applyAjaxFileUpload(element, filesName, filePath) {
				$(element).AjaxFileUpload({
					action: "<?php echo site_url('Brand/uploadPic');?>?filesName="+filesName,
					onChange: function(filename) {
						var $span = $("<span />")
							.attr("class", $(this).attr("id"))
							.text("Uploading")
							.insertAfter($(this));

						$(this).remove();

						interval = window.setInterval(function() {
							var text = $span.text();
							if (text.length < 13) {
								$span.text(text + ".");
							} else {
								$span.text("Uploading");
							}
						}, 200);
					},
					onSubmit: function(filename) {
						return true;
					},
					onComplete: function(filename, response) {
						window.clearInterval(interval);
						console.log(response);
						var $span = $("span." + $(this).attr("id")).text(filename + " "),
							$fileInput = $("<input />")
								.attr({
									type: "file",
									name: $(this).attr("name"),
									id: $(this).attr("id")
								});

						if (response.status) {
							$span.replaceWith($fileInput);

							applyAjaxFileUpload($fileInput, filesName);

							alert(response.msg);

							return;
						}else{
							$("<img />").attr("src", response.url).css("width", 200).appendTo($span);
							$("<a />").attr("href", "#").text("<?php echo $this->lang->line('BTN_RESET');?>").bind("click", function(e) {
									$span.replaceWith($fileInput);
									applyAjaxFileUpload($fileInput, filesName);
									$("input[name="+filePath+"]").val('');
								}).appendTo($span);
							$("input[name="+filePath+"]").val(response.path);
						}
					}
				});
			}

			applyAjaxFileUpload("#brandLogo", "brandLogo", "brandLogoPath");
			 applyAjaxFileUpload("#brandShow", "brandShow", "brandShowPath");
		});

			jQuery(function($) {

				$('#editBrand-form').validate({
					errorElement: 'div',
					errorClass: 'help-block',
					focusInvalid: false,
					rules: {
						nameZh: {
							required:true
						}
					},
			
					messages: {
						nameZh: {
							required:"<?php echo $this->lang->line('ERR_NAME_ZH');?>",
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

			function subEditBrand(){
				
				if(!$('#editBrand-form').valid()){
					return false;
				}

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Brand/editBrand');?>",
					data:$('#editBrand-form').serialize(),
					success:function(data){
						if (data.status == '0') {
							window.location.href = "<?php echo site_url('Brand/listView').'?p='.$this->input->get('p');?>";
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
