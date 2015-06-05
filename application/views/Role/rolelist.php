<?php $this->load->view('public/header');?>

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
								<a href="{:C('WEBSITE_URL')}"><?php echo $this->lang->line('TEXT_HOME_PAGE');?></a>
							</li>

							<li>
								<a href="{:U('Coupon/rolelist')}"><?php echo $this->lang->line('TEXT_ROLE_MANAGER');?></a>
							</li>
							<li class="active"><?php echo $this->lang->line('TEXT_ROLE_LIST');?></li>
						</ul>

					</div>

					<div class="page-content">

						<div class="page-header">
							<button class="btn">
								<a href="#modal-addrole" role="button" data-toggle="modal"><i class="icon-pencil bigger-100"><?php echo $this->lang->line('TEXT_ROLE_ADD');?></i></a>
							</button>
							<button class="btn">
								<a href="#modal-addrule" role="button" class="white" data-toggle="modal"><i class="icon-pencil bigger-100"><?php echo $this->lang->line('TEXT_ROLE_RULE_ADD');?></i></a>
							</button>
							<button class="btn">
								<a href="#modal-addruleuser" role="button" class="white" data-toggle="modal"><i class="icon-pencil bigger-100"><?php echo $this->lang->line('TEXT_ROLE_RULE_USER_ADD');?></i></a>
							</button>
						</div>

						<div class="row">
							<div class="col-xs-12">
								<div class="row">
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
														<td class="hidden-480"><?php echo $v->created_time;?></td>

														<td class="hidden-480" id="status_<?php echo $v->user_id
														;?>">
															<?php if ($v->status == 1){?>
																<span class="label label-error"><?php echo $this->lang->line('TEXT_STATUS_NORMAL');?></span>
															<?php }else{ ?>
																<span class="label label-success"><?php echo $this->lang->line('TEXT_STATUS_STOP');?></span>
															<?php }?>
															
														</td>

														<td>
															<div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
																<?php if($v->status == 0){?>
																<button class="btn btn-xs btn-success" onclick="setUserStatus('<?php echo $v->user_id;?>', 1);">
																	<i class="icon-ok bigger-120"></i>
																</button>
																<?php }else{ ?>
																<button class="btn btn-xs btn-danger" onclick="setUserStatus('<?php echo $v->user_id;?>', 0);">
																	<i class="icon-remove bigger-120"></i>
																</button>
																<?php }?>
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
							</div><!-- /.col -->

						</div><!-- /.row -->


						<div id="modal-addrole" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<form id="addrole_form" >
								<div class="modal-content">
									<div class="modal-header no-padding">
										<div class="table-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												<span class="white">&times;</span>
											</button>
											<?php echo $this->lang->line('TEXT_ROLE_ADD');?>
										</div>
									</div>

									<div class="modal-body no-padding">
										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top">
											<tbody>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_ROLE_NAME');?>
													</td>
													<td>
														<input type="text" name="role_name" />
													</td>
												</tr>

												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_ROLE_RULE');?>
													</td>
													
													<td>

													</td>
													
												</tr>
												<?php foreach($ruleList as $rule){?>
												<tr>
													<td class="center">
														<?php echo $rule['title'];?>
													</td>
													<td>
														<?php foreach ($rule['list'] as $sub) {?>
														<input type="checkbox" name="role_rule[]" id="ruleId_<?php echo $sub['id'];?>" value="<?php echo $sub['id'];?>"/>
														<label for="ruleId_<?php echo $sub['id'];?>">
															<?php echo $sub['title'];?>
														</label>
														<br/>
														<?php }?>
													</td>
												</tr>
												<?php }?>
											</tbody>
										</table>
									</div>

									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal" id="addrole_dismiss">
											<i class="icon-remove"></i>
											<?php echo $this->lang->line('BTN_CLOSE');?>
										</button>

										<button class="btn pull-right" onclick="subAddRoleForm();return false;">
											<?php echo $this->lang->line('BTN_SUBMIT');?>
										</button>

										<button class="btn btn-info pull-right" type="reset">
											<?php echo $this->lang->line('BTN_RESET');?>
										</button>
									</div>
								</div><!-- /.modal-content -->
								</form>
							</div><!-- /.modal-dialog -->
						</div>

						<div id="modal-addrule" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<form id="addrule_form">

								<div class="modal-content">
									<div class="modal-header no-padding">
										<div class="table-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												<span class="white">&times;</span>
											</button>
											<?php echo $this->lang->line('TEXT_ROLE_RULE_ADD');?>
										</div>
									</div>

									<div class="modal-body no-padding">

										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top" >
											<tbody>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_RULE_MODULE');?>
													</td>
													<td>
														<input type="text" name="module" placeholder="<?php echo $this->lang->line('TEXT_PLACEHOLDER_RULE_MODULE');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_RULE_MODULE_TITLE');?>
													</td>
													<td>
														<input type="text" name="module_title" placeholder="<?php echo $this->lang->line('TEXT_PLACEHOLDER_RULE_MODULE_TITLE');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_RULE_ACTION_TITLE');?>
													</td>
													<td>
														<input type="text" name="action_title" placeholder="<?php echo $this->lang->line('TEXT_PLACEHOLDER_RULE_ACTION_TITLE');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_RULE_ACTION_URL');?>
													</td>
													<td>
														<input type="text" name="action_url" placeholder="<?php echo $this->lang->line('TEXT_PLACEHOLDER_RULE_ACTION_URL');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_RULE_SORT');?>
													</td>
													<td>
														<input type="text" name="sort" placeholder="<?php echo $this->lang->line('TEXT_PLACEHOLDER_RULE_SORT');?>"/>
													</td>
												</tr>

												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_RULE_TYPE');?>
													</td>
													<td>
														<select name="type">
															<option value="1"><?php echo $this->lang->line('TEXT_RULE_TYPE_1');?></option>
															<option value="2"><?php echo $this->lang->line('TEXT_RULE_TYPE_2');?></option>
														</select><?php echo $this->lang->line('TEXT_RULE_TYPE_NOTE');?>
													</td>
												</tr>

											</tbody>
										</table>

									</div>

									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal" id="addrule_dismiss">
											<i class="icon-remove"></i>
											<?php echo $this->lang->line('BTN_CLOSE');?>
										</button>

										<button class="btn pull-right" onclick="subAddRuleForm();return false;">
											<?php echo $this->lang->line('BTN_SUBMIT');?>
										</button>

										<button class="btn btn-info pull-right" type="reset">
											<?php echo $this->lang->line('BTN_RESET');?>
										</button>

									</div>
								</form>
								</div><!-- /.modal-content -->
							</div><!-- /.modal-dialog -->
						</div>

						<div id="modal-addruleuser" class="modal fade" tabindex="-1">
							<div class="modal-dialog">
								<form id="addruleuser_form">

								<div class="modal-content">
									<div class="modal-header no-padding">
										<div class="table-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
												<span class="white">&times;</span>
											</button>
											<?php echo $this->lang->line('TEXT_ROLE_RULE_USER_ADD');?>
										</div>
									</div>

									<div class="modal-body no-padding">

										<table class="table table-striped table-bordered table-hover no-margin-bottom no-border-top" >
											<tbody>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_ROLE_USERNAME');?>
													</td>
													<td>
														<input type="text" name="role_username" placeholder="<?php echo $this->lang->line('PLACEHOLDER_USERNAME');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_ROLE_USERNAME');?>
													</td>
													<td>
														<input type="password" name="passwd" placeholder="<?php echo $this->lang->line('PLACEHOLDER_PASSWORD');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_ROLE_USERNAME');?>
													</td>
													<td>
														<input type="password" name="confirm_passwd" placeholder="<?php echo $this->lang->line('PLACEHOLDER_CONFIRM_PASSWORD');?>"/>
													</td>
												</tr>
												<tr>
													<td>
														<?php echo $this->lang->line('TEXT_ROLE_NAME');?>
													</td>
													<td>
														<select name="role_id" id="roleIdSelect">
															<?php foreach($roleSelect as $v):?>
															<option value="<?php echo $v->role_id;?>">
																<?php echo $v->name;?>
															</option>
														<?php endforeach;?>
														</select>
													</td>
												</tr>

											</tbody>
										</table>

									</div>

									<div class="modal-footer no-margin-top">
										<button class="btn btn-sm btn-danger pull-left" data-dismiss="modal" id="addruleuser_dismiss">
											<i class="icon-remove"></i>
											<?php echo $this->lang->line('BTN_CLOSE');?>
										</button>

										<button class="btn pull-right" onclick="subAddRoleUserForm();return false;">
											<?php echo $this->lang->line('BTN_SUBMIT');?>
										</button>

										<button class="btn btn-info pull-right" type="reset">
											<?php echo $this->lang->line('BTN_RESET');?>
										</button>

									</div>
								</form>
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

		<!--  <script src="<?php echo config_item('html_url');?>js/jquery.dataTables.min.js"></script> -->

		<script src="<?php echo config_item('html_url');?>js/datatables_1_10_7.js"></script>
		<script src="<?php echo config_item('html_url');?>js/jquery.dataTables.bootstrap.js"></script>

		<!-- ace scripts -->

		<script src="<?php echo config_item('html_url');?>js/ace-elements.min.js"></script>
		<script src="<?php echo config_item('html_url');?>js/ace.min.js"></script>

		<script type="text/javascript">

			function subAddRuleForm(){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Role/addRule');?>",
					data:$('#addrule_form').serialize(),
					success:function(data){

						if (data.status) {
							alert(data.msg);
						}else{
							setTimeout('$("#addrule_dismiss").trigger("click");', 1000);
							$('#addrule_form').each(function(){
								this.reset();
							});
						}
					}
				});
			}

			function subAddRoleUserForm(){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Role/addRoleUser');?>",
					data:$('#addruleuser_form').serialize(),
					success:function(data){

						if (data.status) {
							alert(data.msg);
						}else{
							setTimeout('$("#addruleuser_dismiss").trigger("click");', 1000);
							$('#addruleuser_form').each(function(){
								this.reset();
							});
						}
					}
				});
			}

			function subAddRoleForm(){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Role/addRole');?>",
					data:$('#addrole_form').serialize(),
					success:function(data){

						if (data.status) {
							alert(data.msg);
						}else{
							setTimeout('$("#addrole_dismiss").trigger("click");', 1000);
							$('#addrole_form').each(function(){
								this.reset();
							});
						}
					}
				});
			}

			function setUserStatus(userID, userStatus){
				$.ajax({
					type:"POST",
					url:"<?php echo site_url('Role/updateUser');?>",
					data:{user_id:userID, status:userStatus, type:'userStatus'},
					success:function(data){

						if (data.status) {
							alert(data.msg);
						}else{
							$('#status_' + userID).html(data.html);
							console.log(this);
						}
					}
				});
			}
		</script>

	</body>
</html>
