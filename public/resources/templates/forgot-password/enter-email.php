<div class="container">
	<div class="wrapper">
		<div class="forgot-password-form-wrapper">
			<h1>Forgot your password?</h1>
			<ol class="progtrckr" data-progtrckr-steps="3">
			    <li class="progtrckr-active">Identify</li
		    	><li class="progtrckr-todo">Verify</li
			    ><li class="progtrckr-todo">Reset</li
			    >
			</ol>
			<div class="body">
				<form action="" role="form" method="post">
					<div class="form-group">
						<p>Please enter your email below to reset your password.</p>
					</div>
					<div class='form-group'>
				        <input type="email" class="form-control single" placeholder="Email" name="email" id="email-field" required/>
				    </div>
			        <input type="hidden" name="reset-password" value="1"/>
			        <a href="<?= HOME ?>/" class="btn btn btn-default cancel">Cancel</a>
		        	<button class="btn btn-danger reset" type="submit" id="send-email-button">Reset Password</button>
			    </form>
			</div>
		</div>
	</div>
</div>