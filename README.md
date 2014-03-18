sR Unit
=======

This module contains all necessary components to write unit tests for oxid modules. Including bootstrapping and mocking capabilities.

Installation
------------
Just add the following requirement to the `composer.json` of your project, and call `composer update superreal/srunit`

    "superreal/srunit": "dev-master"

All required packages will be installed automatically (e.g. PHPUnit, Mockery).

Setup Unit Tests for Module
---------------
The following steps are needed to setup unit testing for your module.

### Configuration

Add phpunit.xml to module-root with at least the following content:

	<phpunit bootstrap="./tests/bootstrap.php">
      <testsuites>
        <testsuite>
            <directory>./tests</directory>
        </testsuite>
      </testsuites>
      <listeners>
        <listener
            class='SrUnit\Adapter\Phpunit\TestListener'
            file='./../srunit/src/SrUnit/Adapter/Phpunit/TestListener.php'/>
      </listeners>
    </phpunit>
    
**Note:** Adding the `TestListener` has the effect, that after each test the expectations are verified.

### Bootstrap

Your tests should be placed in `tests`. Under tests you place your `bootstrap.php` with the following content:

    \SrUnit\Boostrap::create()->bootstrap();
    
Further informations and functionalities are described in a later section of this documentation.

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

When it comes to extension of OXID classes (e.g. oxArticle) you mighty need to test whether your implemenation is correct or not. In case you don't need to have the whole OXID stack to test your implementation, you can mock just the _parent class like this:

    $mock = Factory::create('\SrMyExtensionOxArticle')
        ->isOxidParentClass()
        ->getMock();

Be aware that this call will actually define a class `SrMyExtensionOxArticle_parent` with the behaviour you will apply on it. 

Meaning: After initial instantiating the class it will have the same behaviour for the whole process. Whenever you'll create a new instance, you will get the same results.
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
        ->registerForOXIDFactory()
        ->getMock();
        
This call will create a mock, and will also register this mock-object to be retrieved on every call of `oxNew('oxArticle')`.
This pretty usefull when you have dependend classes that make usage of oxNew() calls very often, and you're not able to change this behaviour from the outside.

### Provisioned Mocks

Often you don't want to create mocks, and apply the same behaviour over and over again. For this case you can use provisioning to get back mocks with default values/stubs.

    $mock = Factory::create('\oxArticle')
        ->useProvisioning()
        ->getMock();
        
In some cases this call will lead to an Exception because no provisioner is set up. You need to implement a provisioner on your own then.

### Mocks with Interfaces

You can define interfaces a mock should implement like this:

    $mock = Factory::create('TestClass')
        ->implementsInterface('\Iterator')
        ->implementsInterface('\Mockable')
        ->getMock();
        
Aware: the Interface must exist!

For some Iterator interfaces is already the stubbing of the particular methods enabled:

* Iterator
* ArrayAccess

For those interfaces it is needed to pass on data to the method in order to have the desired behaviour:

    $data = array('foo', 'bar', 'barz');
    
    $mock = Factory::create('TestClass')
        ->implementsInterface('\Iterator', $data)
        ->getMock();





