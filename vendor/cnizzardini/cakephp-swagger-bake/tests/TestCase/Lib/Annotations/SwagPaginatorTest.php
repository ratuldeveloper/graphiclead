<?php


namespace SwaggerBake\Test\TestCase\Lib\Annotations;

use Cake\Routing\Router;
use Cake\Routing\RouteBuilder;
use Cake\TestSuite\TestCase;
use SwaggerBake\Lib\AnnotationLoader;
use SwaggerBake\Lib\Model\ModelScanner;
use SwaggerBake\Lib\Route\RouteScanner;
use SwaggerBake\Lib\Configuration;
use SwaggerBake\Lib\Swagger;

class SwagPaginatorTest extends TestCase
{
    public $fixtures = [
        'plugin.SwaggerBake.Departments',
    ];

    private $router;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $router = new Router();
        $router::scope('/', function (RouteBuilder $builder) {
            $builder->setExtensions(['json']);
            $builder->resources('Departments');
        });
        $this->router = $router;

        $this->config = new Configuration([
            'prefix' => '/',
            'yml' => '/config/swagger-bare-bones.yml',
            'json' => '/webroot/swagger.json',
            'webPath' => '/swagger.json',
            'hotReload' => false,
            'exceptionSchema' => 'Exception',
            'requestAccepts' => ['application/x-www-form-urlencoded'],
            'responseContentTypes' => ['application/json'],
            'namespaces' => [
                'controllers' => ['\SwaggerBakeTest\App\\'],
                'entities' => ['\SwaggerBakeTest\App\\'],
                'tables' => ['\SwaggerBakeTest\App\\'],
            ]
        ], SWAGGER_BAKE_TEST_APP);

        AnnotationLoader::load();
    }

    public function testSwagPaginator()
    {
        $cakeRoute = new RouteScanner($this->router, $this->config);

        $swagger = new Swagger(new ModelScanner($cakeRoute, $this->config));
        $arr = json_decode($swagger->toString(), true);

        $this->assertArrayHasKey('/departments', $arr['paths']);
        $this->assertArrayHasKey('get', $arr['paths']['/departments']);
        $operation = $arr['paths']['/departments']['get'];

        foreach (['paginatorPage','paginatorLimit','paginatorDirection'] as $item) {
            $this->assertCount(1, array_filter($operation['parameters'], function ($param) use ($item) {
                return isset($param['$ref']) && $param['$ref'] == "#/x-swagger-bake/components/parameters/$item";
            }), "$item paginator parameter not found");
        }

        $this->assertCount(1, array_filter($operation['parameters'], function ($param) {
            return isset($param['name']) && $param['name'] == 'sort' && $param['schema']['type'] == 'string';
        }), 'sort paginator parameter not found');
    }
}