<?php 
$editor = Acadefly::getEditor();
echo "
<div id='write-answer-panel'>
    <div class='header'>
        Know the answer?
    </div>
    <div id='alerts'></div>
    <div class='body'>
        <div class='form-group'>
            ${editor}
        </div>
    </div>
    <div class='footer'>
        <input type='submit' class='btn btn-primary' value='Post Answer' id='post-answer'/>
    </div>
</div>
";