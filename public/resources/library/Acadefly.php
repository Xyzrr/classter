<?php
    class Acadefly {
        static function secureSessionStart() {
            $session_name = 'sec_session_id';   // Set a custom session name
            $secure = SECURE;
            // This stops JavaScript being able to access the session id.
            $httponly = true;
            // Forces sessions to only use cookies.
            if (ini_set('session.use_only_cookies', 1) === false) {
                header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
                exit();
            }
            // Gets current cookies params.
            $cookieParams = session_get_cookie_params();
            session_set_cookie_params($cookieParams["lifetime"],
                $cookieParams["path"], 
                $cookieParams["domain"], 
                $secure,
                $httponly
            );
            // Sets the session name to the one set above.
            session_name($session_name);
            session_start();            // Start the PHP session 
            session_regenerate_id();    // regenerated the session, delete the old one. 
        }

        static function verifyUserIsLoggedIn() {
          if(!empty($_SESSION["userID"]) && !empty($_SESSION["user-is-logged-in"]) && $_SESSION["user-is-logged-in"]) {
            return true;
          } elseif(isset($_COOKIE["login_token"])) {
            if(self::loginWithCookie()) {
              return true;
            } else {
              header("Location: " . HOME . "/");
              exit();
            }
          } else {
            header("Location: " . HOME . "/");
            exit();
          }
        }

        static function loginWithCookie() {
            $token = $_COOKIE["login_token"];
            $db = new SqlConnection();
            $db->query(
              "SELECT loginTokenID FROM LoginTokens
              WHERE token = '${token}'
              ");
            if($db->numRows() > 0) {
              $row = $db->fetchArray();
              $tokenID = $row["loginTokenID"];
              $arr = explode("a", $token);
              $userID = $arr[0];

              //Change token
              $newToken = $userID . "a" . rand(100000000000000000000, 999999999999999999999);
              $db->query(
                "UPDATE LoginTokens SET
                token = '${newToken}',
                creationDate = NOW()
                WHERE loginTokenID = ${tokenID}
              ");
              setcookie("login_token", $newToken, time() + 60*60*24*365, "/");

              $db->query(
                "SELECT firstName, lastName FROM Users 
                LEFT JOIN Teachers USING (userID) 
                LEFT JOIN Students USING (userID) 
                WHERE userID = ${userID}");

              $_SESSION["userID"] = $userID;
              $_SESSION["user-is-logged-in"] = true;
              $_SESSION["userName"] = Acadefly::getName($db->fetchArray());
              return true;
            } else {
              return false;
            }
        }

        static function getRole($row) {
            $role = "";
            if(!empty($row["studentID"])) {
                $role .= "Student ";
            }
            if(!empty($row["teacherID"])) {
                $role .= "Teacher ";
            }
            if(!empty($row["adminID"])) {
                $role .= "Admin ";
            }
            return $role;
        }

        static function getName($user) {
          extract($user);
          if(!empty($teacherID)) {
            return htmlspecialchars($lastName . ", " . $firstName);
          } else {
            return htmlspecialchars($firstName . " " . $lastName);
          }
        }

        static function joinClass($classID) {
            $userID = $_SESSION["userID"];

            $connection = new SqlConnection();

            $connection->query("
              INSERT INTO UserClasses SET
              userID = ${userID},
              classID = ${classID}
            ");

            header("Location: ../classes/");
            exit();
        }

        static function stripAllSlashes () {
          if (get_magic_quotes_gpc()) {   
            function stripslashes_deep($value) {   
              $value = is_array($value) ?   
                  array_map('stripslashes_deep', $value) :   
                  stripslashes($value);   
              return $value;   
            }   
            $_POST = array_map('stripslashes_deep', $_POST);   
            $_GET = array_map('stripslashes_deep', $_GET);   
            $_COOKIE = array_map('stripslashes_deep', $_COOKIE);   
            $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
          }
        }

        static function getThumbnail($row, $size = "small") {
          $folder = HOME . "/img/thumbnails";
          if(empty($row["thumbnailID"])) {
            if($size == "large") {
              return "${folder}/default-large.jpg";
            } elseif($size == "medium") {
              return "${folder}/default-medium.jpg";
            } else {
              return "${folder}/default-small.jpg";
            }
          } else {
            if($size == "large") {
              return $row["largeDataURL"];
            } elseif($size == "medium") {
              return $row["mediumDataURL"];
            } else {
              return $row["smallDataURL"];
            }
          }
        }

        static function timeToString($d) {
          $ts = time() - strtotime(str_replace("-","/",$d)); 
          
          if($ts>31536000) $val = round($ts/31536000,0).' year'; 
          else if($ts>2419200) $val = round($ts/2419200,0).' month'; 
          else if($ts>604800) $val = round($ts/604800,0).' week'; 
          else if($ts>86400) $val = round($ts/86400,0).' day'; 
          else if($ts>3600) $val = round($ts/3600,0).' hour'; 
          else if($ts>60) $val = round($ts/60,0).' minute'; 
          else $val = $ts.' second'; 
          
          if($val>1) $val .= 's'; 
          return $val;
        }

        static function getUserBox($user, $vertical = "bottom", $horizontal = "right") {
          extract($user);
          $profilePicture = Acadefly::getThumbnail($user);
          $name = Acadefly::getName($user);
          return "
          <a class='user-box ${vertical} ${horizontal}' href='" . HOME . "/profile/?id=${userID}'>
              <img src='${profilePicture}' alt='Profile Picture'/>
              <div class='data'>
                  <p class='name'>${name}</p>
                  <p class='rep'>${reputation} rep</p>
              </div>
          </a>
          ";
        }

        static function getEditor() {
          return '
          <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
                <ul class="dropdown-menu">
                </ul>
            </div>
            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
                    <li><a data-edit="fontSize 4"><font size="4">Large</font></a></li>
                    <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
                    <li><a data-edit="fontSize 2"><font size="2">Small</font></a></li>
                    <li><a data-edit="fontSize 1"><font size="1">Tiny</font></a></li>
                </ul>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
                <a class="btn btn-default" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
                <a class="btn btn-default hidden-xs" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
                <a class="btn btn-default hidden-xs" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
                <a class="btn btn-default" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
                <a class="btn btn-default hidden-sm hidden-xs" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="glyphicon glyphicon-indent-left"></i></a>
                <a class="btn btn-default hidden-sm hidden-xs" data-edit="indent" title="Indent (Tab)"><i class="glyphicon glyphicon-indent-right"></i></a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
                <a class="btn btn-default" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
                <a class="btn btn-default" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
                <a class="btn btn-default hidden-sm hidden-xs" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
            </div>
            <div class="btn-group">
                <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
                <div class="dropdown-menu input-append">
                    <input class="form-control" placeholder="URL" type="text" data-edit="createLink"/>
                    <button class="btn btn-default" type="button">Add</button>
                </div>
                <a class="btn btn-default" data-edit="unlink" title="Remove Hyperlink" style="margin-left: -1px;"><i class="fa fa-cut"></i></a>

            </div>

            <div class="btn-group">
                <a class="btn btn-default" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
                <a class="btn btn-default" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
            </div>
            <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
          </div>

          <div id="editor" class="post-body"></div>
          ';
        }

        static function getWorkingDays($startDate, $endDate, $holidays){
            // do strtotime calculations just once
            $endDate = strtotime($endDate);
            $startDate = strtotime($startDate);


            //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
            //We add one to inlude both dates in the interval.
            $days = ($endDate - $startDate) / 86400 + 1;

            $no_full_weeks = floor($days / 7);
            $no_remaining_days = fmod($days, 7);

            //It will return 1 if it's Monday,.. ,7 for Sunday
            $the_first_day_of_week = date("N", $startDate);
            $the_last_day_of_week = date("N", $endDate);

            //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
            //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
            if ($the_first_day_of_week <= $the_last_day_of_week) {
                if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
                if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
            }
            else {
                // (edit by Tokes to fix an edge case where the start day was a Sunday
                // and the end day was NOT a Saturday)

                // the day of the week for start is later than the day of the week for end
                if ($the_first_day_of_week == 7) {
                    // if the start date is a Sunday, then we definitely subtract 1 day
                    $no_remaining_days--;

                    if ($the_last_day_of_week == 6) {
                        // if the end date is a Saturday, then we subtract another day
                        $no_remaining_days--;
                    }
                }
                else {
                    // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
                    // so we skip an entire weekend and subtract 2 days
                    $no_remaining_days -= 2;
                }
            }

            //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
        //---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
           $workingDays = $no_full_weeks * 5;
            if ($no_remaining_days > 0 )
            {
              $workingDays += $no_remaining_days;
            }

            //We subtract the holidays
            foreach($holidays as $holiday){
                $time_stamp=strtotime($holiday);
                //If the holiday doesn't fall in weekend
                if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
                    $workingDays--;
            }

            return $workingDays;
        }

        static function getBlockDay($date) {
          $db = new SqlConnection();
          $db->query(
            "SELECT referenceBlockDayDate, referenceBlockDayID
            FROM Schools
            WHERE schoolID = 1");
          extract($db->fetchArray());

          $db->query("SELECT COUNT(blockDayID) AS blockDayCount FROM BlockDays");
          extract($db->fetchArray());

          $db->query(
            "SELECT date, skipBlock FROM SpecialDays 
            LEFT JOIN DaySchedules USING (dayScheduleID)
            WHERE skipBlock = 1");
          $specialDays = array();
          $noBlock = false;
          while($row = $db->fetchArray()) {
            if($row["date"] == $date) {
              $noBlock = true;
            }
            $specialDays[] = $row["date"];
          }
          if($noBlock) {
            $cycleNumber = 1;
          } else {
            $workingDays = self::getWorkingDays($referenceBlockDayDate, $date, $specialDays) - 1;
            $cycleNumber = (($referenceBlockDayID - 1 + $workingDays) % $blockDayCount) + 1;
          }
          $db->query("SELECT * FROM BlockDays WHERE cycleNumber = ${cycleNumber} AND schoolID = 1");
          return $db->fetchArray();
        }

      static function isWeekend($date) {
        $dayOfWeek = (int) date("w", strtotime($date));
        return ($dayOfWeek == 0 or $dayOfWeek == 6);
      }

      static function isInSchoolYear($date, $db) {
        $db->query("SELECT startDate, endDate FROM Schools WHERE schoolID = 1");
        extract($db->fetchArray());
        if(strtotime($date) < strtotime($startDate)) {
          return 1;
        }
        if(strtotime($date) > strtotime($endDate)) {
          return 2;
        }
        return false;
      }

      static function isHoliday($date, $db) {
        $db->query("SELECT specialDayID, reason FROM SpecialDays LEFT JOIN DaySchedules USING (dayScheduleID) WHERE noSchool = 1 AND date = '${date}'");
        if($db->numRows() > 0) {
          extract($db->fetchArray());
          if(empty($reason)) {
            return "Unspecified Reason";
          } else {
            return $reason;
          }
        }
        return false;
      }
    }
?>