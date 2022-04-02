<?php

namespace SwaggerBake\Test\TestCase;

use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\Command\Command;

class BakeTemplateTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    public $fixtures = [
        'plugin.SwaggerBake.Bakers',
    ];

    /** @var string  */
    private $controller;

    public function setUp() : void
    {
        parent::setUp();
        $this->setAppNamespace('SwaggerBakeTest\App');
        $this->useCommandRunner();

        $this->controller = APP . DS . 'Controller' . DS . 'BakersController.php';

        if (is_file($this->controller)) {
            unlink($this->controller);
        }
    }

    public function testBakeController()
    {
        $this->exec('bake controller Bakers --no-test --force --theme SwaggerBake');

        $controllerFile = 'BakersController.php';
        $assets = TEST . DS . 'assets' . DS;

        $this->assertOutputContains('Baking controller class for Bakers...');
        $this->assertOutputContains('<success>Wrote</success>');
        $this->assertOutputContains('tests/test_app/src/Controller/' . $controllerFile);
        $this->assertFileExists($this->controller);
        $this->assertFileEquals($assets . $controllerFile, $this->controller);
    }
}