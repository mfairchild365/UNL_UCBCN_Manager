<?php

require_once 'PHPUnit/Framework.php';
chdir(dirname(__FILE__).'/../../../');
require_once 'Manager.php';

/**
 * Test class for UNL_UCBCN_Manager.
 * Generated by PHPUnit on 2007-09-26 at 09:23:05.
 */
class UNL_UCBCN_ManagerTest extends PHPUnit_Framework_TestCase {
    
    public $dsn = 'mysqli://eventcal:eventcal@localhost/eventcal';
    
    public $auth;
    
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once 'PHPUnit/TextUI/TestRunner.php';

        $suite  = new PHPUnit_Framework_TestSuite('UNL_UCBCN_ManagerTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp() {
        $this->auth = $this->getMock('Auth', array('start','checkAuth','getUsername'),array('array'));
        $this->auth->expects($this->any())
                   ->method('getUsername')
                   ->will($this->returnValue('test'));
        $this->b = new UNL_UCBCN_Manager(array('dsn'=>$this->dsn,'a'=>$this->auth));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown() {
    }

    /**
     * @todo Implement testStartupPlugins().
     */
    public function testStartupPlugins() {
        // Remove the following lines when you implement this test.
        $this->b->startupPlugins();
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * testStartSession().
     */
    public function testStartSession() {
        $this->b->startSession();
        $this->assertEquals($this->auth->getUsername(), $this->b->user->uid);
        $this->assertEquals($this->auth->getUsername(), $this->b->session->user_uid);
        $this->assertEquals(get_class($this->b->calendar),'UNL_UCBCN_Calendar');
        $this->assertEquals(get_class($this->b->account), 'UNL_UCBCN_Account');
        $this->assertEquals($this->b->user->calendar_id, $this->b->calendar->id);
    }

    /**
     * Tests testEndSession().
     */
    public function testEndSession() {
        $this->b->endSession();
        $this->assertFalse(isset($_SESSION['calendar_id']));
    }

    /**
     * testShowLoginForm().
     */
    public function testShowLoginForm() {
        $this->assertEquals(get_class($this->b->showLoginForm()), 'UNL_UCBCN_Manager_Login');
    }

    /**
     * testShowEventSubmitForm().
     */
    public function testShowEventSubmitForm() {
        //$this->assertNotEquals(strpos('<form',$this->b->showEventSubmitForm()), false);
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowImportForm().
     */
    public function testShowImportForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testRun().
     */
    public function testRun() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowSubscribeForm().
     */
    public function testShowSubscribeForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowSubscriptions().
     */
    public function testShowSubscriptions() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowSearchResults().
     */
    public function testShowSearchResults() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testProcessPostStatusChange().
     */
    public function testProcessPostStatusChange() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowPermissionsForm().
     */
    public function testShowPermissionsForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowEventListing().
     */
    public function testShowEventListing() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowAccountForm().
     */
    public function testShowAccountForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowCalendarForm().
     */
    public function testShowCalendarForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowEventDateTimeForm().
     */
    public function testShowEventDateTimeForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowCalendars().
     */
    public function testShowCalendars() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowCalendarUsers().
     */
    public function testShowCalendarUsers() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowAddUserForm().
     */
    public function testShowAddUserForm() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testShowChooseCalendar().
     */
    public function testShowChooseCalendar() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testRegisterPlugin().
     */
    public function testRegisterPlugin() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testPagerWrapper().
     */
    public function testPagerWrapper() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
