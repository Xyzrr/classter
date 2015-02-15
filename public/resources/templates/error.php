<div class="background">
	<div class="container-fluid">
		<div class="wrapper">
			<div class="form-column">
				<h1>Acadefly</h1>
				<div action="" class="form-signin" role="form" method="post">
			        <p>We're sorry... An error occured while loading this page.</p>
			        <?php if(isset($error)) { ?>
					<p>
						<?= $error ?>
					</p>
					<?php } ?>
		        	<a href="../dashboard/" class="btn btn-lg btn-primary btn-block">Dang.</a>
			    </div>
			</div>
		</div>
	</div>
</div>