<div class="container">
    <div class="wrapper">
        <div class="class-header-column">
            <div class="header">
                <h1 id="name-read"><?= empty($class->info["courseName"]) ? "Unnamed course" : $class->info["courseName"] . " " . $class->info["courseLevelName"] ?></h1>
                <div class="info">
                    <?php
                    foreach($class->teachers as $teacher) { 
                    extract($teacher);
                    $name = Acadefly::getName($teacher);
                    $thumbnail = Acadefly::getThumbnail($teacher, "large");
                    ?>
                    <a href='<?= HOME ?>/profile/?id=<?= $userID ?>' class="teacher">
                        <img class="img-thumbnail" src='<?= $thumbnail ?>'/>
                    </a>
                    <h2><a href='<?= HOME ?>/profile/?id=<?= $userID ?>'><?= $name ?></a></h2>
                    <p>Period <?= $class->info["period"] ?> class</p>
                    <?php } ?>
                </div>
                <div class="secondary-nav">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="active"><a href="#students" role="tab" data-toggle="tab">
                            <i class="fa fa-users visible-xs-inline"></i><span class="hidden-xs">Students</span>
                        </a></li
                        ><li><a id="schedule-tab-button" href="#forum" role="tab" data-toggle="tab">
                            <i class="fa fa-comments visible-xs-inline"></i><span class="hidden-xs">Forum</span>
                        </a></li
                        >
                    </ul>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane active" id="students">
                <div class="students-list">
                    <h3><?= count($class->students) ?> <?php echo count($class->students) > 1 ? "Students" : "Student"; ?></h3>
                    <?php
                    foreach($class->students as $student) { 
                    extract($student);
                    $name = Acadefly::getName($student);
                    $role = Acadefly::getRole($student);
                    $thumbnail = Acadefly::getThumbnail($student, "medium");
                    ?>
                    <div class='thumbnail-wrapper'>
                        <a href='<?= HOME ?>/profile/?id=<?= $userID ?>'>
                        </a>
                        <div class='image'>
                            <img src='<?= $thumbnail ?>'/>
                        </div>
                        <div class='info'>
                            <span class='name'><?= $name ?></span>
                            <span class='role'><?= $role ?></span>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="tab-pane" id="forum">

            </div>
        </div>
    </div>
</div>
</div>