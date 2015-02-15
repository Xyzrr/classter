<?php

$layout = new BasicLayout("
	<div class='container welcome-message'>
		<h1>Homework Pages</h1>
		<p>This is a tool to help you browse all of your homework pages in one location. To get started, click one of the tabs above.</p>
		<p>By default, the homework pages for each class is set to the most commonly chosen URL amongst your classmates.
		If no such URL exists, you will be sent to your teacher's home page.
		In order to change the homework page for a certain class, click the settings icon in the top-right, enter the URL, and hit the save button.</p>
	</div>
");

$layout->render();