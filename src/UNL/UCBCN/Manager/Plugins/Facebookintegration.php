<?php
/**
 * Facebook Integration class.
 * 
 * PHP version 5
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 *
 */
require_once 'UNL/UCBCN/Manager/Plugin.php';
require_once 'HTML/QuickForm.php';

/**
 * Facebook Integration Class
 * A plugin class that allows for calendar integration with facebook.
 * It allows the user to log into facebook from the manager and link the 
 * currently selected calendar with the facebook account.
 * 
 * Also give an interface to change variables such as createEvents
 * 
 * @category  Events 
 * @package   UNL_UCBCN
 * @author    Michael Fairchild <mfairchild365@gmail.com>
 * @copyright 2010 Regents of the University of Nebraska
 * @license   http://www1.unl.edu/wdn/wiki/Software_License BSD License
 * @link      http://code.google.com/p/unl-event-publisher/
 */
class UNL_UCBCN_Manager_FacebookIntegration extends UNL_UCBCN_Manager_Plugin
{
    public $name    = 'Facebook Integration';
    public $version = '0.0.1';
    public $author  = 'Michael Fairchild';
    
    public $manager;
    public $facebookAccount;
    public $uri;
    public $output = array();
    public $facebookConfig;
    public $facebookInterface;
    public $me;
    public $logoutUrl;
    public $loginUrl;
    public $session;
    private $config;
    
    /** startup
     * Initializes all variables for the class on when the class is loaded by the manager.
     * 
     * @param manager &$manager = the manager.
     * @param string   $uri = the uri to the current page.
     * 
     * @return void
     **/
    public function startup(&$manager,$uri) 
    {
        $this->manager =& $manager;
        $this->uri        = $uri;
        $this->facebookAccount = UNL_UCBCN::factory('facebook_accounts');
        $this->facebookAccount->calendar_id = $this->manager->calendar->id;
        if (!$this->facebookAccount->find(true)) {
            $this->facebookAccount->insert();
        }
        $this->config = UNL_UCBCN_FacebookInstance::getConfig();
        $this->facebookInterface = UNL_UCBCN_FacebookInstance::initFacebook($this->config['appID'], $this->config['secret']);
        $this->session = $this->facebookInterface->getSession();
        if ($this->session) {
            try {
                $this->me = $this->facebookInterface->api('/me');
            } catch (FacebookApiException $e) {
                error_log($e);
            }
        }
        if ($this->me) {
            $this->logoutUrl = $this->facebookInterface->getLogoutUrl();
        } else {
            $this->loginUrl = $this->facebookInterface->getLoginUrl();
        }
        //for facebook login: 
        $this->output[] = "
        <div id='fb-root'></div>
        <script>
            window.fbAsyncInit = function() {
                FB.init({
                    appId   : '{$this->config['appID']}',
                    session : ".json_encode($this->session).", // don't refetch the session when PHP already has it
                    status  : true, // check login status
                    cookie  : true, // enable cookies to allow the server to access the session
                    xfbml   : true // parse XFBML
                });
                // whenever the user logs in, we refresh the page
                FB.Event.subscribe('auth.login', function() {
                    window.location.reload();
                });
                FB.Event.subscribe('auth.logout', function() {
                    window.location.reload();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
        </script>";
    }

    /** run
     * Function called by plugin system.  Runs the main logic of the plugin.
     * 
     * @return void
     **/
    public function run()
    {
        $this->output[] = '<p class="sec_main">Facebook Integration Manager</p>' .
                '<p>Use this tool to register a calander with a facebook account and to change options.</p>';
        if (UNL_UCBCN_FacebookInstance::getConfig() ) {
            if ($this->me) {
                $this->output[] = "<img src='http://graph.facebook.com/".$this->me['id']."/picture'>";
                $this->output[] = "Welcome, " . $this->me['name'] . "<br>";
                $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='{$this->logoutUrl}'>logout of facebook</a><br><hr>";
                if (!isset($_GET['authorize'])) {
                    $this->output[] = "<a href='{$this->uri}&authorize=true'>Use this facebook account for this calendar</a><br>";
                }
            } else {
                $url = urlencode(UNL_UCBCN_FacebookInstance::getURL()."&");
                $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='https://graph.facebook.com/oauth/authorize?client_id={$this->config['appID']}&redirect_uri=$url&scope=rsvp_event,user_events,create_event,offline_access'>Log Into Facebook</a><br>";
            }
            $this->output[] = "<a href='{$this->uri}&edit=true'>Edit Settings for this calendar</a><br>";
            if (isset($_GET['submit'])) {
                $this->doEdit();
            }
        } else {
            $this->output[] = "<h2>Setting up facebook: </h2>";
            $this->output[] = "There are a few things that you need to do before you can use this delightful feature.
                                Please follow these instructions to get started...<br>
                              <ol>
                                  <li>Create a PHP file in your root directory named 'config.inc.php'.  There should already be a file
                                      in that directory called 'config.sample.inc.php'.  For an example of how to use the file that you just made
                                      open that file and read it.  The next few instructions will guide you though editing the 'config.inc.php'
                                  <li>
                                      Go to <a href='http://developers.facebook.com/setup/'>Facebook</a> and create an application.
                                      <ol>
                                          <li>For 'Site name:' Put in the name of this site.</li>
                                          <li>For 'Site URL:' Put in the url for this site: 'http://".$_SERVER["SERVER_NAME"]."/?'</li>
                                      </ol>
                                      </li>
                                  <li>Complete the security check if needed.</li>
                                  <li>A results page will show your App Id and App Secret
                                      <ol>
                                      <li>Copy the 'App ID:' to config.inc.php replacing the value of \$fb_appId (ex: \$fb_appId = xxxxxxxxxx)</li>
                                      <li>Copy the 'App Secret:' to config.inc.php replacing the value of \$fb_secret (ex: \$fb_secret = 'xxxxxxxxxx')</li>
                                      </ol>
                                  </li>
                                  <li>Reload this page.  If you entered the correct values, you can then authorize a facebook account.</li>
                              </ol>";
            //  http://developers.facebook.com/setup/
        }
        
        $this->showStatus();
        
        if (isset($_GET['authorize'])) {
            $action = 'authorize';
        } else if (isset($_GET['edit'])) {
            $action = 'edit';
        } else {
            $action = 'start';
        }
        switch( $action )
        {
        case 'start':
            break;
        case 'authorize':
            $this->output[] = $this->facebookAuthorize();
            break;
        case 'edit':
            $this->output[] = $this->showEdit();
            break;
        case 'authorize':
            $this->facebookAuthorize();
            break;
        default:
            //$this->output[] = $this->showChooseDateForm();
        }
    }
    
    /** facebookAuthorize
     * Saves facebook account information to the DB for a calendar.
     * 
     * @return void
     **/
    public function facebookAuthorize()
    {
        $this->output[] = "<h3>Authorize Facebook Account for this calendar</h3>";
        //DB_DataObject::debugLevel(4);
        unset($this->facebookAccount);
        $this->facebookAccount = UNL_UCBCN::factory('facebook_accounts');
        $this->facebookAccount->calendar_id = $this->manager->calendar->id;
        $this->facebookAccount->find(true);
        $this->facebookAccount->facebook_account = $this->me['id'];
        $this->facebookAccount->access_token = $this->session['access_token'];
        $rows = $this->facebookAccount->update();
        if ($rows) {
            $this->output[] = "Facebook account has been authorized for this calendar.<br>";
            $this->output[] = "Facebook account id: {$this->facebookAccount->facebook_account}<br>";
            $this->output[] = "Facebook access_token: {$this->facebookAccount->access_token}<br><hr>";
        }
    }
    
    /** showStatus
     * Displays the status of the facebook integration.  Show Like Buttons, create events, etc.
     * 
     * @return void
     **/
    function showStatus(){
        $this->output[] = "<hr>";
        $this->output[] = "Create events is currently set to: ";
        if ($this->facebookAccount->create_events) {
            $this->output[] = "True<br>";
        } else {
            $this->output[] = "False<br>";
        }
        $this->output[] = "Show Like Buttons is currently set to: ";
        if ($this->facebookAccount->show_like_buttons) {
            $this->output[] = "True<br>";
        } else {
            $this->output[] = "False<br>";
        }
        $this->output[] = "<strong>Events will be created: ";
        if ($this->facebookAccount->createEvents()) {
            $this->output[] = "True";
        } else {
            $this->output[] = "False";
        }
        $this->output[] = "</strong><br>";
    }
    
    /** showEdit
     * Displays editable options for the facebook integraion system.
     * 
     * @return void
     **/
    public function showEdit()
    {
        $form = new HTML_QuickForm('UNL_UCBCN_Manager_FacebookIntegration', 'get', $this->uri);
        $form->addElement('hidden', 'action', 'plugin');
        $form->addElement('hidden', 'p', 'UNL_UCBCN_Manager_FacebookIntegration');
        //Create Events Option:
        $createEvents = $form->createElement('advcheckbox', 'createEvents', 'Create Events');
        if ($this->facebookAccount->create_events) {
            $createEvents->setChecked(true);
        } else {
            $createEvents->setChecked(false);
        }
        $form->addElement($createEvents);
        
        //Show Like Button Options:
        $showLike = $form->createElement('advcheckbox', 'showLike', 'Show Like Buttons');
        if ($this->facebookAccount->show_like_buttons) {
            $showLike->setChecked(true);
        } else {
            $showLike->setChecked(false);
        }
        $form->addElement($showLike);
        $form->addElement('submit', 'submit', 'Submit');
        
        return $form->toHtml();
    }
    
    /** doEdit
     * Preforms edits done by showEdit().
     * 
     * @return void
     **/
    public function doEdit()
    {
        $this->facebookAccount->create_events = $_GET['createEvents'];
        $this->facebookAccount->show_like_buttons = $_GET['showLike'];
        $this->facebookAccount->update();
    }
    
}

//Register the plugin.
UNL_UCBCN_Manager::registerPlugin('UNL_UCBCN_Manager_FacebookIntegration');
?>