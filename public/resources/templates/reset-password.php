<div class="background">
	<div class="container-fluid">
		<div class="wrapper">
			<div class="form-column">
				<h1>Acadefly</h1>
				<form action="?" class="form-signin" role="form" method="post">
					<?php if(isset($error)) { ?>
					<div class="alert alert-danger" role-"alert">
						<?= $error ?>
					</div>
					<?php } ?>
			        <input type="text" class="form-control top" value="<?= $email ?>" name="email" readonly/>
			        <input type="password" class="form-control middle" placeholder="New password" name="password" required autofocus/>
			        <input type="password" class="form-control bottom" placeholder="Confirm new password" name="confirm-password" required/>
			        <input type="hidden" name="set-new-password" value="1"/>
			        <input type="hidden" name="userID" value="<?= $userID ?>"/>

		        	<button class="btn btn-lg btn-primary btn-block" type="submit">Set New Password</button>
		      </form>
			</div>
		</div>
	</div>
</div>