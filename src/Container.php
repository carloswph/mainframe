<?php

namespace Mainframe;

/**
 * Creates a simple ArrayObject container to store objects
 * that once stored, can have their methods used directly
 * by the container instance
 *
 * @since  1.0.0
 * @uses  \ReflectionClass
 * @author  Carlos Matos | WP Helpers  
 */
class Container extends \ArrayObject
{
	/**
	 * Array of methods and respective instances, generated by Reflection
	 * @var  $methods  array
	 */
	protected $methods = [];

	/**
	 * Class construct.
	 *
	 * @since  1.0.0
	 * @param  $objects  array  Array of objects
	 */
	public function __construct(array $objects = null)
	{
		foreach ($objects as $key => $object) {
			$this->add($key, $object);
		}
	}

	/**
	 * Adds new objects to the container.
	 *
	 * @since  1.0.0
	 * @param  $key  string  Object key.
	 * @param  $object  object  Class object to add.
	 * @return  void()
	 */
	public function add(string $key, object $object)
	{
		$reflection = new \ReflectionClass($object);
		$methods = $reflection->getMethods();

		foreach ($methods as $method) {
			$this->methods[$key . $method->getName()] = $method->getName();
		}

		$this->offsetSet($key, $object);

	}

	/**
	 * Magic method - captures methods that don't exist in the Mainframe class
	 * but searches for them inside the stored object. If it finds any match,
	 * the class uses ArrayObject methods to grab the respective objects and
	 * call the desired method on each one of them.
	 *
	 * Can be targeting an specific object, if its key is passed using the
	 * virtual method terminal(string $key, string $method, array $args).
	 * 
	 * Example:
	 * $container = new Mainframe(['classname' => new ClassName()]);
	 * $container->terminal('classname', 'methodname', [args1, args2]);
	 *
	 * @since  1.0.0
	 * @param  $method  string   Called method.
	 * @param  $args  array  Array of methods passed.
	 * @return  void
	 *
	 */
	public function __call($method, $args = null)
	{
		if($method === 'terminal') {
			$object = $this->offsetGet($args[0]);
			call_user_func([$object, $args[1]], $args[2]);
		}

		foreach ($this->methods as $key => $value) {
			if($value === $method) {
				$object = $this->offsetGet(str_replace($method, '', $key));
				call_user_func_array([$object, $method], $args);
			}
		}
	}

}