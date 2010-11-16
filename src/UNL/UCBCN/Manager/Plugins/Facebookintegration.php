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
                    appId   : '{$this->facebookInterface->getAppId()}',
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
        if ($this->me) {
            $this->output[] = "<img src='http://graph.facebook.com/".$this->me['id']."/picture'>";
            $this->output[] = "Welcome, " . $this->me['name'] . "<br>";
            $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='{$this->logoutUrl}'>logout of facebook</a><br><hr>";
            if (!isset($_GET['authorize'])) {
                $this->output[] = "<a href='{$this->uri}&authorize=true'>Use this facebook account for this calendar</a><br>";
            }
        } else {
            $this->output[] = "<a href='index.php?action=plugin&p=UNL_UCBCN_Manager_FacebookIntegration'>Integration Home</a> | <a href='{$this->loginUrl}&scope=rsvp_event,user_events,create_event,offline_access'>Log Into Facebook</a><br>";
        }
        $this->output[] = "<a href='{$this->uri}&edit=true'>Edit Settings for this calendar</a><br>";
        if (isset($_GET['submit'])) {
            $this->doEdit();
        }
        if (isset($_GET['authorize'])) {
            $this->facebookAuthorize();
        } else if (isset($_GET['edit'])) {
            $action = 'edit';
        } else {
            $action = 'start';
        }
        
        $this->output[] = "Create events is currently set to: {$this->facebookAccount->create_events}<br>";
        $this->output[] = "<strong>Events will be created: {$this->facebookAccount->createEvents()}</strong><br>";
        switch($action)
        {
        case 'start':
            break;
        case 'authorize':
            $this->output[] = $this->facebookAuthorize();
            break;
        case 'edit':
            $this->output[] = $this->showEdit();
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
        $createEvents = $form->createElement('advcheckbox', 'createEvents', 'Create Events');
        if ($this->facebookAccount->create_events) {
            $createEvents->setChecked(true);
        } else {
            $createEvents->setChecked(false);
        }
        $form->addElement($createEvents);
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
        $this->facebookAccount->update();
    }
    
}

//Register the plugin.
UNL_UCBCN_Manager::registerPlugin('UNL_UCBCN_Manager_FacebookIntegration');
?>