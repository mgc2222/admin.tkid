<?php if (!isset($webpage)) die('Direct access not allowed');?>
<div class="container">
	<div class="row edit_table">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div id="divErrors" class="system_message error">
					<?php if ($webpage->DisplayMessage) echo $webpage->Message ?>
				</div>
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $trans['login.sign_in']?></h3>
				</div>
				<div class="panel-body">
					<fieldset>
						<div class="form-group">
							<input class="form-control" placeholder="<?php echo $trans['login.email']?>" name="txtUsername" id="txtUsername" type="email" value="<?php echo $dataView->txtUsername?>" autofocus />
						</div>
						<div class="form-group">
							<input class="form-control" placeholder="<?php echo $trans['login.password']?>" name="txtPassword" id="txtPassword" type="password" value="" />
						</div>
						<div class="checkbox">
							<label>
								<input name="remember" id="remember" type="checkbox" value="1" /><label for="remember"><?php echo $trans['login.remember_me']?></label>
							</label>
						</div>
						<!-- Change this to a button or input when using this as a form -->
						<input type="submit" class="btn btn-lg btn-success btn-block" value="<?php echo $trans['login.login']?>"/>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>