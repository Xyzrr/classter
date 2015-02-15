<!-- Page content -->
<div id="page-content-wrapper">
    <div class="content-header">
        <h1>
            Edit Profile
        </h1>
    </div>
    <!-- Keep all page content within the page-content inset div! -->
    <div class="page-content inset container">
        <div class="wrapper">
            <div class="profile-column">
                <form action="?" method="post" role="form">
                    <input type="hidden" name="edited" value="1"/>
                    <input type="hidden" name="userID" value="<?= $userID ?>"/>
                    <div class="panel profile-info">
                        <img src="http://s3.amazonaws.com/37assets/svn/765-default-avatar.png" class="profile-picture img-responsive img-thumbnail"/>
                        <div class="user-info">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="firstName" class="form-control top" placeholder="First" value="<?= $firstName ?>"/>
                                <input type="text" name="lastName" class="form-control bottom" placeholder="Last" value="<?= $lastName ?>"/>
                            </div>
                            <div class="form-group">
                                <label>Grade</label>
                                <input type="number" name="grade" class="form-control" value="<?= $grade ?>"/>
                            </div>
                        </div>
                        <input class="btn btn-default edit-button" type="submit" value="Save"/>
                    </div>
                    <div class="panel">
                        <h1>About</h1>
                        <div class="form-group">
                            <div class="input-column">
                                <textarea name="about" placeholder="Some information about you" rows="6" class="form-control"><?= $about ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>