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
								<a href="<?php echo site_url('Brand/listView');?>"><?php echo $this->lang->line('TITLE_BRAND_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TITLE_BRAND_LIST');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
						</div>

						<div class="row">

							<div class="col-xs-12">
								<div class="row">

									<form role="form" id="editRestaurant-form">

									<div class="col-xs-12">
										<div class="table-responsive">
											<table id="rolelist-table" class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														
														<th><?php echo $this->lang->line('TEXT_OPERATION');?></th>
														<th>图片</th>	
													</tr>
												</thead>

												<tbody>
													<?php foreach ($pic as $v):?>
													<tr>
														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<input type="radio" name="path" value="<?php echo $v; ?>">
															</div>

														</td>
														<td>
															<img src="<?php echo $v;?>">
														</td>
													</tr>
													<?php endforeach;?>
													<tr>
														<td><input type="checkbox" name="hasMake"></td>
														<td>是否需要作图</td>
													</tr>
												</tbody>
											</table>
										</div><!-- /.table-responsive -->

									</div><!-- /span -->


									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="pic"> 上传图片 </label>

										<div class="col-sm-9">
											<input type="file" name="pic" id="pic" >
											<input type="hidden" name="picPath" id="picPath">
										</div>
									</div>



									<br>
									<br>

									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<button class="btn btn-info" type="button" onclick="subEditRestaurant()">
												<i class="icon-ok bigger-110"></i>
												<?php echo $this->lang->line('BTN_SUBMIT');?>
											</button>

											&nbsp; &nbsp; &nbsp;
											<button class="btn" type="button" onclick="returnLast()">
												<i class="icon-undo bigger-110"></i>
												返回
											</button>
										</div>
									</div>

									</form>

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

		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>
		<script type="text/javascript" src="<?php echo config_item('html_url');?>js/jquery.ajaxfileupload.js"></script>

		<script type="text/javascript">
		$(document).ready(function() {
			var interval;

			function applyAjaxFileUpload(element, filesName, filePath) {
				$(element).AjaxFileUpload({
					action: "<?php echo site_url('HongQiao/uploadPic');?>?filesName="+filesName,
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

			applyAjaxFileUpload("#pic", "pic", "picPath");
		});
		
		function subEditRestaurant(){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('HongQiao/editRestaurant').'?id='.$this->input->get('id');?>",
					data:$('#editRestaurant-form').serialize(),
					success:function(data){
						if (data.status == '0') {
							window.location.href = "<?php echo site_url('HongQiao/restaurantList').'?p='.$this->input->get('p');?>";
							return true;
						}

						alert(data.msg);
						return false;
					}
				});
		}

		function returnLast(){
			window.location.href = "<?php echo site_url('HongQiao/restaurantList').'?p='.$this->input->get('p');?>"
		}

		</script>

	</body>
</html>
