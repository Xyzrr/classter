<div class="container">
	<div class="wrapper">
		<div class="forgot-password-form-wrapper">
			<h1>Forgot your password?</h1>
			<ol class="progtrckr" data-progtrckr-steps="3">
			    <li class="progtrckr-done">Identify</li
		    	><li class="progtrckr-done">Verify</li
			    ><li class="progtrckr-active">Reset</li
			    >
			</ol>
			<div class="body">
				<form action="?" class="form-signin" role="form" method="post">
					<div class="form-group">
						<p>Please enter the password you would like to assign to the account of <span class="email"><?= $email ?></span></p>
					</div>
			        <div class="form-group">		        	
				        <input type="password" class="form-control middle" placeholder="New password" name="password" id="password-field" required autofocus/>
			        </div>
			        <div class="form-group">		        	
				        <input type="password" class="form-control bottom" placeholder="Confirm new password" name="confirm-password" id="confirm-password-field" required/>
			        </div>
			        <input type="hidden" name="set-new-password" value="1"/>
			        <input type="hidden" name="userID" value="<?= $userID ?>" id="user-id-field"/>
			        <a href="<?= HOME ?>/" class="btn btn btn-default cancel">Cancel</a>
		        	<button class="btn btn-primary" type="submit" id="reset-password-button">Set New Password</button>
			    </form>
		    </div>
		</div>
	</div>
</div>