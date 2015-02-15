<div class="container-fluid">
    <div class="wrapper relative">
        <ul class="nav nav-pills pages-nav" role="tablist">
          <li style="display:none" class="active">
            <a class='page-tab' role='tab' href='#welcome' data-toggle='tab'>
            </a>
          </li>
          <?= $tabs ?>
        </ul>
          <button class="btn btn-default settings">
            <i class="fa fa-cog"></i>
          </button>
        <div class="settings-wrapper">
          <form>
            <div class="input-group url">
              <input type="text" class="form-control" id="url-input" placeholder="Homework page URL"/>
              <span class="input-group-btn">
                <input class="btn btn-default" type="submit" id="url-submit" value="Go"/>
              </span>
            </div>
          </form>
          <div class="btn-group btn-group-justified">
            <div class="btn-group">
              <button class="btn btn-default page-settings-button" id="home-button">
                <i class="fa fa-home"></i>
                <span class="button-label">Home page</span>
              </button>
            </div>
            <div class="btn-group">
              <button class="btn btn-default page-settings-button" id="homework-button">
                <i class="fa fa-list-ul"></i>
                <span class="button-label">Homework page</span>
              </button>
            </div>
            <div class="btn-group">
              <button class="btn btn-default page-settings-button" id="save-url-button">
                <i class="fa fa-save"></i>
                <span class="button-label">Save URL as HW page</span>
              </button>
            </div>
          </div>
        </div>
        <div class="page tab-content">
          <?= $iframes ?> 
          <div class='tab-pane active' id="welcome">
            <iframe src="<?= HOME ?>/pages/pages-welcome/" class="page-frame">
              <p>Sorry, your browser does not support iFrames. Please consider switching to a more modern browser.</p>
            </iframe>
          </div>
        </div>
    </div>
</div>