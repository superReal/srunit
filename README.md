sR Unit
=======

This package contains all necessary components to write unit tests for oxid modules - including bootstrapping and mocking capabilities.

Installation
------------
Just add the following requirement to the `composer.json` of your project, and call `composer update superreal/srunit`

    "superreal/srunit": "0.9.*@dev"

All required packages will be installed automatically (e.g. PHPUnit, Mockery).

Setup Unit Tests for Module
---------------
The following steps are needed to setup unit testing for your module.

### Configuration

Add phpunit.xml to module-root with at least the following content:

	<phpunit bootstrap="tests/bootstrap.php">
      <testsuites>
        <testsuite>
            <directory>tests</directory>
        </testsuite>
      </testsuites>
      <listeners>
        <listener class='SrUnit\Adapter\Phpunit\TestListener' />
      </listeners>
    </phpunit>
    
**Note:** Adding the `TestListener` has the effect, that after each test the expectations are verified.

### Bootstrap

Your tests should be placed in `tests`. Under tests you place your `bootstrap.php` with the following content:

    \SrUnit\Boostrap::create(__DIR__)->bootstrap();
    
The bootstrapping process will retrieve all needed directories on its own, and will load the composer autoloader, and a custom autoloader for the classes of your OXID module - based on the configuration in your `metadata.php`.

#### Loading OXID

OXID is **not** loaded by default. Basic functionalities like `oxNew()` or `oxDb::getDb()` are emulated. You can control their behaviour by using mocks.

In case you need OXID loaded (e.g. for integration tests) you can either load OXID on bootstrap:

    \SrUnit\Boostrap::create(__DIR__)->loadOXID()->bootstrap();

or you even better you add the test that needs OXID to the group `needs-oxid` by adding a annotation:

    @group needs-oxid

In the latter case the `TestListener` will take care of loading OXID, and enabling/disabling the needed module `superreal/srunit-module`. This module has to be required in your `composer.json` as well - otherwise the test will die with an Exception.

### TestCases should derive from SrUnit\TestCase

All of your TestCases should extend the SrUnit\TestCase in order to enable the OXID related functionatities or convience-methods. 


Using the Mock-Factory
----------------------

The Factory supports you on creating mocks to replace the dependencies of your SUT. It takes care of OXID-related requirements also, with a easy understandable fluent interface. 

When you call the getMock() method at the end of the method-chain you will get back a Mockery\MockInterface with small additions (e.g. implementsArrayAccess()).

The underlying libraries is Mockery, even if it is not called directly, you will get back the Mock object from Mockery. 


### Creating Simple Mocks

Simple Mock, simple call:

    $mock = Factory::create('TestClass')->getMock();

Afterwards you can define the behaviour of the mock by simply use the Mockery methods: 

    $mock->shouldReceive('getParam')->andReturn('a-value')

### Testing OXID Extensions

When it comes to extending OXID core classes (e.g. oxArticle) you might need to test whether your implementation is correct or not. In case you don't need to have the whole OXID stack to test your implementation, you can mock just the _parent class by doing this:

    $mock = Factory::createParentClass('\SrMyExtensionOxArticle')->getMock();

Be aware that this call will actually define a class `SrMyExtensionOxArticle_parent` with the behaviour you will apply on it. 

Meaning: After the initial instantiation the class it will have the same behaviour for the whole PHP process. Whenever you'll create a new instance, you will get the same results.
When you need different behaviour for different tests you have to run your tests in isolation. 

You can achieve this by simply add the annotations to your test class:

    @runTestsInSeparateProcesses
    
or to a particular method:

    @runTestInSeparateProcess
 
When you load the OXID framework on boostrap, you have to add the following annotation as well:

    @preserveGlobalState disabled
    
Otherwise your tests will die in vain.


### Integration Tests with Usage of OXID-Factory

In case you need to test the integration of your module or you'd like to use the OXID factory in order to have the whole stack available, you can use the following call:

    $mock = Factory::create('\oxArticle')
        ->registerForOxNew()
        ->getMock();
        
This call will create a mock, and will also register this mock-object to be retrieved on every call of `oxNew('oxArticle')`.
This is pretty usefull when you have dependend classes that make usage of oxNew() calls very often, and you're not able to change this behaviour from the outside.

### Provisioned Mocks

Often you don't want to create mocks, and apply the same behaviour over and over again. For this case you can use provisioning to get back mocks with default values/stubs.

    $mock = Factory::create('\oxArticle')
        ->useProvisioning()
        ->getMock();
        
In some cases this call will lead to an Exception because no provisioner is available. You need to implement a provisioner on your own then.

### Mocks with Interfaces

You can define the interfaces a mock should implement, like this:

    $mock = Factory::create('TestClass')
        ->implementsInterface('\Iterator')
        ->implementsInterface('\Mockable')
        ->getMock();
        
Be Aware: the interface must exist!

For some Iterator interfaces there is already a stubbing mechanism of the particular methods enabled:

* Iterator
* ArrayAccess

For those interfaces it is needed to pass on data to the method in order to have the desired behaviour:

    $data = array('foo', 'bar', 'barz');
    
    $mock = Factory::create('TestClass')
        ->implementsInterface('\Iterator', $data)
        ->getMock();

You will iterate over the given data, when you use this mock.



