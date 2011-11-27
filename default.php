<?php if (!defined('APPLICATION')) exit();

$PluginInfo['GoogleAnalytics'] = array(
	'Name' => 'Google Analytics',
	'Description' => 'Google Analytics. Require theme that has AfterBody event.',
	'Version' => '1.1.8',
	'Date' => 'Autumn 2011',
	'Author' => 'Skaarj',
	'AuthorEmail' => 'info@return-to-na-pali.org',
	'AuthorUrl' => 'http://www.return-to-na-pali.org/',
	'SettingsUrl' => 'settings/googleanalytics',
	'RequiredApplications' => array('Dashboard' => '>=2.1')
);

/*
$Configuration['Plugins']['GoogleAnalytics']['PageTrackerID'] = 'UA-XXXXX-X';
*/


class GoogleAnalyticsPlugin implements Gdn_IPlugin {
	
	public function SettingsController_GoogleAnalytics_Create($Sender) {
		$Sender->Permission('Garden.Plugins.Manage');
		$Sender->AddSideMenu();
		$Sender->Title('Google Analytics');
		$ConfigurationModule = new ConfigurationModule($Sender);
		$ConfigurationModule->RenderAll = True;
		$Schema = array(
			'Plugins.GoogleAnalytics.PageTrackerID' => array('LabelCode' => 'TrackerID', 'Control' => 'TextBox', 'Default' => C('Plugins.GoogleAnalytics.PageTrackerID', 'UA-00000000-0'))
		);
		$ConfigurationModule->Schema($Schema);
		$ConfigurationModule->Initialize();
		$Sender->View = dirname(__FILE__) . DS . 'views' . DS . 'settings.php';
		$Sender->ConfigurationModule = $ConfigurationModule;
		$Sender->Render();
	}
	
	
	public function Base_AfterBody_Handler($Sender) {
		if ($Sender->MasterView == 'admin') return;
		$PageTrackerID = C('Plugins.GoogleAnalytics.PageTrackerID');
		if ($PageTrackerID) echo <<<GOOGLE
<!-- Google Analytics -->
<script type="text/javascript">
var _gaq = [['_setAccount', '$PageTrackerID'], ['_trackPageview']];
(function(ga, s) {
ga.async = true;
ga.src = 'http://www.google-analytics.com/ga.js';
s.parentNode.insertBefore(ga, s);
}(
document.createElement('script'),
document.getElementsByTagName('script')[0]
));
</script>
<!--/ Google Analytics -->
GOOGLE;
	}
	
	public function Setup() {
	}
}