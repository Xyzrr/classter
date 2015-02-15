<?php
$isPublic = $userID == $_SESSION["userID"] ? false : true;
?>
<div class="container">
    <div class="wrapper">
        <input type="hidden" id="target-id" value="<?= $userID ?>"/>
        <div class="profile-header-column">
            <div class="profile-header">
                <div class="profile-cover">
                    <div alt="Cover Image" class="cover"></div>
                </div>
                <div class="profile-info">
                <?php if($isPublic) { ?>
                    <div class="profile-picture">
                        <img src="<?= $thumbnail ?>" alt="Cover Image" class="img-thumbnail">
                    </div>
                    <div class="profile-basic-info">
                        <h1 id="name-read">
                            <?= $name ?>
                        </h1>
                        <p>Placeholder for title</p>
                    </div>
                <?php } else { ?>
                    <div class="profile-picture">
                        <img src="<?= $thumbnail ?>" alt="Profile Picture" class="img-thumbnail" id="profile-picture-image">
                        <div class="hidden-wrapper">
                            <form enctype="multipart/form-data" id="profile-picture-form">
                                <input type="file" id="profile-picture-file">
                            </form>
                        </div>
                        <div class="caption" id="profile-picture-caption">
                            <i class="fa fa-camera"></i>
                            <p>Update Profile Picture</p>
                        </div>
                    </div>
                    <div class="profile-basic-info">
                        <div id="name">
                            <div data-profile="read">
                                <h1 id="name-read">
                                    <?= $name ?>
                                </h1>
                                <button class="edit-button btn btn-default" data-profile="toggle-edit">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            </div>
                            <form class="name-wrapper form-inline" data-profile="edit">
                                <div class="form-group">
                                    <input type="text" class="form-control name-input" name="firstName" value="<?= $firstName ?>" placeholder="First Name">
                                </div>
                                <div class="form-group">                                    
                                    <input type="text" class="form-control name-input" name="lastName" value="<?= $lastName ?>" placeholder="Last Name">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-default" data-profile="cancel-edit">Cancel</button>
                                </div>
                                <div class="form-group">                                
                                    <button class="btn btn-primary" data-profile="save-edit">Save</button>
                                </div>
                            </form>
                        </div>
                        <p>Placeholder for title</p>
                    </div>
                    <?php } ?>
                </div>
                <div class="secondary-nav">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="active"><a href="#overview" role="tab" data-toggle="tab">
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

            </div>
            <div class="tab-pane" id="posts">

            </div>
            <div class="tab-pane" id="reputation">

            </div>
        </div>
    </div>
</div>
</div>


<div class="modal fade" id="join-class" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <input type="text" class="form-control" id="teacher-search"/>
  </div>
  <div class="modal-body modal-nopadding">
    <div class="wrapper modal-wrapper" id="teacher-search-results">
        <!-- Search Results -->
    </div>
  </div>
  <div class="modal-footer">
  </div>
</div>
</div>
</div>

<div class="modal fade" id="set-course" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
  <div class="modal-header">
    <input type="text" class="form-control" id="course-search"/>
  </div>
  <div class="modal-body modal-nopadding">
    <div class="wrapper modal-wrapper" id="course-search-results">
        <!-- Search Results -->
    </div>
  </div>
  <div class="modal-footer">
  </div>
</div>
</div>