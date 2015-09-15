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
								<a href="<?php echo site_url('Approve/comment');?>"><?php echo $this->lang->line('TITLE_APPROVE_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_EDIT_YMT');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">
							<div class="col-xs-12">
								<form class="form-horizontal" role="form" id="editBrand-form">
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="name"> 姓名</label>

										<div class="col-sm-9">
											<input type="text" name="name" value="<?php echo $detail->name;?>" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="province_name"> 省份</label>

										<div class="col-sm-9">
											<input type="text" name="province_name" value="<?php echo $detail->province_name;?>" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="no"> 编号</label>

										<div class="col-sm-9">
											<input type="text" name="no" value="<?php echo $detail->no;?>" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="slogan"> 辣妈口号</label>

										<div class="col-sm-9">
											<input type="text" name="slogan" value="<?php echo $detail->slogan;?>" >
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="origPic"> <?php echo $this->lang->line('TEXT_LOGO');?> </label>

										<div class="col-sm-9">

											<?php if (isset($detail->orig_pic_url) && !empty($detail->orig_pic_url)) {?>
											<span id="origPicSpan">
												<img src="<?php echo config_item('image_url').$detail->orig_pic_url;?>" style="width: 200px;"><a onclick="removeFileImg('origPicSpan');">重置</a>
											</span>
											<?php } ?>
											<input type="file" name="origPic" id="origPic" />
											<input type="hidden" name="origPicPath" id="origPicPath" value="<?php echo $detail->orig_pic_url;?>"/>
										</div>
									</div>

<!-- 									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="brandShow"> <?php echo $this->lang->line('TEXT_LOGO_SHOW');?> </label>

										<div class="col-sm-9">
											<?php if (isset($brand->pic_url) && !empty($brand->pic_url)) {?>
											<span id="brandShowSpan">
												<img src="<?php echo config_item('image_url').$brand->pic_url;?>" style="width: 200px;"><a onclick="removeFileImg('brandShowSpan');">重置</a>
											</span>
											<?php } ?>
											<input type="file" name="brandShow" id="brandShow" />
											<input type="hidden" name="brandShowPath" id="brandShowPath" value="<?php echo $brand->pic_url;?>"/>
										</div>
									</div> -->

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subEditYmt()">
												<i class="icon-ok bigger-110"></i>
												<?php echo $this->lang->line('BTN_SUBMIT');?>
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="button" onclick="window.location.href=document.referrer;">
												<i class="icon-undo bigger-110"></i>
												返回
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

			function applyAjaxFileUpload(element, filesName, filePath, fileSpan) {
				$(element).AjaxFileUpload({
					action: "<?php echo site_url('Approve/uploadPic');?>?filesName="+filesName,
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
							if (fileSpan) {
								$('#'+fileSpan).html('');
							}
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

			applyAjaxFileUpload("#origPic", "origPic", "origPicPath", "origPicSpan");
			 // applyAjaxFileUpload("#brandShow", "brandShow", "brandShowPath", "brandShowSpan");
		});


			function subEditYmt(){

				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Approve/editYmt').'?yid='.$this->input->get('yid');?>",
					data:$('#editBrand-form').serialize(),
					success:function(data){
						if (data.status == '0') {
							window.location.href=document.referrer;							
							return true;
						}

						alert(data.msg);
						return false;
					}
				});
			}

			function removeFileImg(element){
				$('#'+element).html('');
			}

			function returnLast(){
				window.location.history(-1);
			}
		</script>

	</body>
</html>
