<div class="background">
	<div class="container-fluid">
		<div class="wrapper">
			<div class="form-column">
				<h1>Acadefly</h1>
				<form action="" class="form-signin" role="form" method="post">
			        <input type="email" class="form-control top" placeholder="Email" name="email" 
			         value="<?= isset($email) ? $email : '' ?>" required/>
			        <div class="password-row">
				        <input type="password" class="form-control bottom" placeholder="Password" name="password" 
				         value="<?= isset($password) ? $password : '' ?>" required/>
				        <a href="../forgot-password/" class="forgot-password-link">
				        	<i class="fa fa-question"></i>
				        </a>
				    </div>

			        <input type="hidden" name="loggedIn" value="1"/>
			        <?= isset($error) ? $error : "" ?>
		        	<button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
			    </form>
			    <p>
			    	<span class="question">Need an account?</span>
			    	<a href="../register/" class="btn btn-default btn-switch-form" type="submit">Register</a>
			    </p>
			</div>
		</div>
	</div>
</div>