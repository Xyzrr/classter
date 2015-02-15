<div class="container">
    <div class="wrapper">
        <div class="main-column">
            <a href="ask/" class="ask-question-mobile visible-xs-block btn">
                Ask a Question
            </a>
            <div class="user-info-box hidden-xs">
                <div class="info">
                    <div class="profile-thumbnail hidden-xs">
                        <a href="<?= HOME ?>/profile/"><img class="user-thumbnail img-thumbnail" src="<?= $profilePicture ?>"></a>
                    </div>
                    <div class="name hidden-xs">
                        <a href="<?= HOME ?>/profile/"><?= $name ?></a>
                    </div>
                    <a href="ask/" class="ask-question-button btn btn-primary btn-lg">
                        Ask a Question
                    </a>
                </div>
                <div class="stats-wrapper">
                    <table class="table stats">
                        <tr>
                            <td>
                                <span class="stat-count"><?= $reputation ?></span>
                                <span class="stat-text">Reputation</span>
                            </td> 
                            <td>
                                <span class="stat-count"><?= $questionCount ?></span>
                                <span class="stat-text">Questions</span>
                            </td>
                            <td>
                                <span class="stat-count"><?= $answerCount ?></span>
                                <span class="stat-text">Answers</span>
                            </td>
                            <td>
                                <span class="stat-count"><?= $commentCount ?></span>
                                <span class="stat-text">Comments</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="questions-panel">
                <div class="secondary-nav questions-nav">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="active"><a id="schedule-tab-button" href="#school" role="tab" data-toggle="tab">
                            <i class="fa fa-institution visible-xs-inline"></i><span class="hidden-xs">School</span>
                        </a></li
                        ><li><a href="#world" role="tab" data-toggle="tab">
                            <i class="fa fa-globe visible-xs-inline"></i><span class="hidden-xs">World</span>
                        </a></li
                        ><li><a href="#mine" role="tab" data-toggle="tab">
                            <i class="fa fa-user visible-xs-inline"></i><span class="hidden-xs">Mine</span>
                        </a></li
                        >
                    </ul>
                </div>
                <div class="questions-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="classes">
                            <!-- Ajax -->
                        </div>
                        <div class="tab-pane" id="school">
                            <!-- Ajax -->
                        </div>
                        <div class="tab-pane" id="world">
                            <!-- Ajax -->
                        </div>
                    </div>
                </div>
                <div class="footer text-center">
                    <ul class='pagination'>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>