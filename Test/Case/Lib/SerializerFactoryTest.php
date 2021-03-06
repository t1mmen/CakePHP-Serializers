<?php

App::uses('SerializerFactory', 'Serializers.Lib');
App::uses('Serializer', 'Serializers.Serializer');
App::uses('Model', 'Model');

class TestCommentSerializer extends Serializer {
}

class TestTag extends Model {

	public $useTable = false;
}

class SerializerFactoryTest extends CakeTestCase {

	public function testLooksUpConventionallyNamedClasses() {
		$factory = new SerializerFactory('TestComment');
		$serializer = $factory->generate();

		$this->assertTrue(is_subclass_of($serializer, 'Serializer'));
		$this->assertTrue($serializer instanceof TestCommentSerializer);
		$this->assertEquals(array(), $serializer->required);
		$this->assertEquals('TestComment', $serializer->rootKey);
	}

	public function testGetsDefaultInstanceWhenClassNotDefined() {
		$testTagSchema = array(
			'id' => array(),
			'tag' => array(),
			'created' => array(),
			'modified' => array(),
		);
		$TestTag = $this->getMockForModel('TestTag', array(
			'schema',
		));
		$TestTag->expects($this->any())
			->method('schema')
			->will($this->returnValue($testTagSchema));
		ClassRegistry::addObject('TestTag', $TestTag);
		$factory = new SerializerFactory('TestTag');
		$serializer = $factory->generate();
		$this->assertTrue($serializer instanceof Serializer);
		$this->assertEquals(array_keys($testTagSchema), $serializer->required);
		$this->assertEquals('TestTag', $serializer->rootKey);
	}

	public function testNoModelExistsNorSerializerClassExists() {
		$factory = new SerializerFactory('TestNoModelExistsWithThisName');
		$serializer = $factory->generate();
		$this->assertTrue($serializer instanceof Serializer);
		$this->assertEquals(array(), $serializer->required);
	}
}
