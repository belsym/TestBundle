TestBundle
==========

A collection of classes that can be used within any project to provide a solid base for your unit tests.

There are currently 3 classes available:

MockedUpTestCase
----------------

This testcase enables [Mockery](https://github.com/padraic/mockery) and provides a configurable factory method for
building Mock Objects.

### TODO: Add documentation for MockedUpTestCase

MockContainerAwareTestCase
--------------------------

This test extends MockedUpTestCase to provide a mock Container for your unit tests. Also includes a method to generate
a mock Doctrine EntityManager class

### TODO: Add documentation for MockContainerAwareTestCase

ContainerAwareTestCase
----------------------

The ContainerAwareTestCase creates a bootable kernel and uses a genuine ContainerInterface instance to access services
configured through the application configuration files as normal. Can only be utilised in Symfony projects as it
requires the AppKernel class to be included.