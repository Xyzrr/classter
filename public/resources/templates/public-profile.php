<div class="container">
    <div class="wrapper">
        <div class="profile-header-column">
            <div class="profile-header">
                <div class="profile-cover">
                    <img src="http://www.dpaperwall.com/wp-content/uploads/Windows-7-Desktop-Background-Clean-Wallpaper-HD.jpg" alt="Cover Image" class="cover">
                </div>
                <div class="profile-info">
                    <div class="profile-picture">
                        <img src="<?= $thumbnail ?>" alt="Cover Image" class="cover img-thumbnail">
                    </div>
                    <div class="profile-basic-info">
                        <h1 id="name-read">
                            <?= $name ?>
                        </h1>
                        <p>Placeholder for title</p>
                    </div>
                </div>
                <div class="secondary-nav">
                    <ul class="nav nav-pills" role="tablist">
                        <li><a href="#overview" role="tab" data-toggle="tab">
                            <i class="fa fa-list-ul visible-xs-inline"></i><span class="hidden-xs">Overview</span>
                        </a></li
                        ><li><a id="schedule-tab-button" href="#schedule-tab" role="tab" data-toggle="tab">
                            <i class="fa fa-calendar visible-xs-inline"></i><span class="hidden-xs">Schedule</span>
                        </a></li
                        ><li><a href="#posts" role="tab" data-toggle="tab">
                            <i class="fa fa-file visible-xs-inline"></i><span class="hidden-xs">Posts</span>
                        </a></li
                        ><li><a href="#reputation" role="tab" data-toggle="tab">
                            <i class="fa fa-caret-square-o-up visible-xs-inline"></i><span class="hidden-xs">Reputation</span>
                        </a></li
                        >
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="overview">

            </div>
            <div class="tab-pane" id="schedule-tab">
                <div class="profile-panel-column-wide">
                    <div class="profile-panel">
                        <div class="profile-panel-header">
                            <h2>Schedule</h2>
                        </div>
                        <div id="schedule" data-id="<?= $userID ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="posts">
                <div class="profile-panel-column">
                    <div class="profile-panel">
                        <div class="profile-panel-header">
                            <h2>Placeholder</h2>
                        </div>
                        <p>
                            Foo bar
                        </p>
                    </div>
                </div>
                <div class="profile-panel-column">
                    <div class="profile-panel">
                        <div class="profile-panel-header">
                            <h2>Placeholder</h2>
                        </div>
                        <p>
                            Foo bar
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>