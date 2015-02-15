<div class="container-fluid">
  <div class="home-navbar">
      <a class="acadefly-logo" href="<?= HOME ?>/">ACADEFLY</a>
      <button class="btn btn-primary login-button" href="#" data-toggle="modal" data-target="#login-modal" id="login-toggle">Sign In</button> 
  </div>
</div>

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Welcome back!</h4>
      </div>
      <form accept-charset="UTF-8" id="login-form">
        <div class="modal-body">
          <div class="form-group">
            <input id="login-email" type="email" class="form-control" name="user_email" placeholder="Email"/>
          </div>
          <input id="login-password" type="password" class="form-control" name="user_password" placeholder="Password"/>
        </div>
        <div class="modal-footer">
          <a href="<?= HOME ?>/forgot-password/enter-email/" class="forgot-password">Forgot password</a>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <input type="submit" class="btn btn-primary" value="Login" id="login-button"/>
        </div>
      </form>
    </div>
  </div>
</div>