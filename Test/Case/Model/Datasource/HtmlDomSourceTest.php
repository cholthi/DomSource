<?php 

/** Dom source test file
* php version >4 < 5 
*
*
* @copyright  copyright Cholthi Tiopic, 2015
* @license MIT (http://www.opensource.org/licenses/mit-license.php)
**/
 App::uses('HtmlDomSource', 'DomSource.Model/Datasource');
 App::uses('ConnectionManager', 'Model');

  //new db config
 ConnectionManager::create('test_source', array('Datasource'=> 'DomSource.HtmlDomSource'));

 // sample testing Model

 class PostModel extends CakeTestModel {

 	/**
 	* use DB config
 	*
 	* @var $useDbConfig string 
 	*/
 	public $useDbConfig = 'test_source';

 	
 	public $source = 'http://slashdot.org/';
 }


/**
* DomSource Test
*
*/
 class HtmlDomSourceTest extends CakeTestCase {

 /**
 * Dom Source Instance
 *
 * @var DomSource
 */
	public $Model = null;
/**
 * Set up for Tests
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Model = ClassRegistry::init('PostModel');
	}
/**
 * Tear down for tests
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		ClassRegistry::flush();
		$this->Model = null;
	}

	/**
	* testFind() 
	* @return void
	*/

	public  function testFind() {
             $result = $this->Model->find('all',array('conditions'=>array('title'=>'h2.story span a 0','body'=> 'div.body div i 0'),'fields'=> array('body','title')));
             $expected = array(
			array('PostModel' => array('title' => 'Does Elon Musk\'s Hyperloop Make More Sense On Mars?', 'body' => ''))
		);
        $this->assertContains($expected , $result);
	}	
 }
 ?>