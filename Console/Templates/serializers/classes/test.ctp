<?php
/**
 * Test Case bake template
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.classes
 * @since         CakePHP(tm) v 1.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

echo "<?php\n";
?>
<?php if ($type === 'Controller'): ?>
App::uses('JsonApiControllerTestCase', 'Test');
<?php endif; ?>
<?php foreach ($uses as $dependency): ?>
App::uses('<?php echo $dependency[0]; ?>', '<?php echo $dependency[1]; ?>');
<?php endforeach; ?>

/**
 * <?php echo $fullClassName; ?> Test Case
 *
 */
<?php if ($type === 'Controller'): ?>
class <?php echo $fullClassName; ?>Test extends JsonApiControllerTestCase {
<?php else: ?>
class <?php echo $fullClassName; ?>Test extends CakeTestCase {
<?php endif; ?>

<?php if (!empty($fixtures)): ?>
	/**
	 * Fixtures
	 *
	 * @var	array
	 */
	public $fixtures = array(
		'<?php echo join("',\n\t\t'", $fixtures); ?>',
	);

<?php endif; ?>
<?php if (!empty($construction)): ?>
	/**
	 * setUp method
	 *
	 * @return	void
	 */
	public function setUp() {
		parent::setUp();
<?php echo $preConstruct ? "\t\t" . $preConstruct : ''; ?>
		$this-><?php echo $className . ' = ' . $construction; ?>
<?php echo $postConstruct ? "\t\t" . $postConstruct : ''; ?>
<?php if ($type === 'Controller'): ?>
		// setup the XMLHttpRequest aka AJAX style request
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
<?php endif; ?>
	}

	/**
	 * tearDown method
	 *
	 * @return	void
	 */
	public function tearDown() {
		unset($this-><?php echo $className; ?>);
		parent::tearDown();
	}

<?php endif; ?>
<?php if (empty($methods)) {
	$methods = array('placeholder');
} ?>
<?php foreach ($methods as $method): ?>

	/**
	 * test<?php echo Inflector::camelize($method); ?> method
	 *
	 * @return	void
	 */
	public function test<?php echo Inflector::camelize($method); ?>() {
<?php if ($method == 'placeholder'): ?>
		$this->markTestSkipped('<?php echo $fullClassName; ?> has no methods to test yet.');
<?php else: ?>
		$this->markTestIncomplete('test<?php echo Inflector::camelize($method); ?> not implemented.');
<?php endif; ?>
<?php if ($type === 'Model'): ?>
		$dummy = $this-><?php echo $className; ?>->find('first'); // This sometimes "fixes" code coverage reports when there are no actual methods in the Model.
<?php endif; ?>
	}
<?php endforeach; ?>
}
