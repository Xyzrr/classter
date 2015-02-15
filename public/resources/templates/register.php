<div class="background">
	<div class="container-fluid">
		<div class="wrapper">
			<div class="form-column">
				<h1>Acadefly</h1>
				<form action="" class="form-signin" role="form" method="post">
					<input type="text" class="form-control top-left" placeholder="First Name" name="firstName" 
					value="<?= isset($firstName) ? $firstName : '' ?>" required autofocus/
					><input type="text" class="form-control top-right" placeholder="Last Name" name="lastName" 
					value="<?= isset($lastName) ? $lastName : '' ?>" required autofocus/
					><input type="email" class="form-control middle" placeholder="Email" name="email" 
					value="<?= isset($email) ? $email : '' ?>" required>
			        <input type="password" class="form-control bottom" placeholder="Password" name="password" 
			        value="<?= isset($password) ? $password : '' ?>" required/>
			        <input type="hidden" name="registered" value="1"/>
<!-- 			        <div class="checkbox">
			          <label>
			            <input type="checkbox" value="remember-me"> Remember me
			          </label>
			        </div> -->
			        <div id="error-message">
				        <?= isset($error) ? $error : "" ?>
				    </div>
		        	<button class="btn btn-lg btn-primary btn-block" type="submit">Sign Up</button>
		      </form>
		      <p>
		      	<span class="question">Already have an account?</span>
		      	<a href="../login/" class="btn btn-default btn-switch-form" type="submit">Login</a>
		      </p>
			</div>
		</div>
	</div>
</div>