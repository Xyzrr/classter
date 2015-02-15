<div class="background">
	<div class="container-fluid">
		<div class="wrapper">
			<div class="form-column">
				<h1>Acadefly</h1>
				<form action="" class="form-signin" role="form" method="post">
					<?php if(isset($error)) { ?>
					<div class="alert alert-danger" role-"alert">
						<?= $error ?>
					</div>
					<?php } ?>
			        <input type="email" class="form-control single" placeholder="Email" name="email" required/>

			        <input type="hidden" name="reset-password" value="1"/>
<!-- 			        <div class="checkbox">
			          <label>
			            <input type="checkbox" value="remember-me"> Remember me
			          </label>
			        </div> -->
			        <a href="../login/" class="btn btn-lg btn-default cancel">Cancel</a>
		        	<button class="btn btn-lg btn-danger reset" type="submit">Reset Password</button>
			    </form>
			</div>
		</div>
	</div>
</div>