# Mainframe

Deadly simple object container experiment that makes possible to call any methods existent in the injected objects directly through the container. If more than one object have the called function, all of them are executed. If no methods are found, then the container just ignores the calling.

# Installation

As usual, we prefer using Composer for managing dependencies, so this package can be installed using `composer require carloswph/mainframe`

# Usage

Using Mainframe is actually quite simple. Let's consider we have two classes A and B, and want to inject them into the container. Injections can be made either during the container instantiation or thereafter, by using the method add(), as follows.

```php
use Mainframe\Container;

require __DIR__ . '/vendor/autoload.php';

class A
{
	public function __construct() {}

	public function print($string)
	{
		echo $string;
	}

	public function method()
	{
		echo 'C';
	}
}

class B
{
	public function __construct() {}

	public function print()
	{
		echo 'B';
	}

	public function load()
	{
		echo 'Do something';
	}
}

$container = new Container(['class_a' => new A()]); // Class A instance injected as Container array
$container->add('class_b', new B()); // Class B instance being injected after instantiation, through add()
```

As a matter of controlling objects, all injected objects must have a key. That will allow Mainframe to gather and retrieve all of their methods using Reflection, which will generate a databank where objects and methods names are stored for being called later. Now we have both objects inside the container, we can use all of their methods through the container, directly.

Also, you can specifically target a particular method in a particular instance, by using the virtual method terminal($key, $methodName, $args).

```php
$container->print(); // Calls only class B print()
$container->print('Test'); // Calls both class A and B print() methods
$container->method(); // Calls class A method()
$container->terminal('class_a', 'print', 'Test'); // Calls specifically class A print() and pass 'Test' as argument

```

## More

As an extension of PHP's ArrayObject class, Mainframe is able to accepted any of this class existent methods. However, to make it easier, additional aliases will be created for managing the injected objects.