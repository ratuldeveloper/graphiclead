<?php
//declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Authenticator\AuthenticatorInterface;
use Authentication\Identifier\IdentifierInterface;
use Authentication\Identifier\JwtSubjectIdentifier;
use Authentication\Authenticator\JwtAuthenticator;
use Authentication\Middleware\AuthenticationMiddleware;
use Authentication\Authenticator\ResultInterface;
use Cake\Routing\Router;
use Cake\Utility\Security;
use Psr\Http\Message\ServerRequestInterface;
use Firebase\JWT\Key;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    /**
     * Load all the application configuration and bootstrap logic.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }
        $this->addPlugin('Authentication');
        // Load more plugins here
        //$this->addPlugin('SwaggerBake');
       // $this->addPlugin('ApiTokenAuthenticator');
        $this->addPlugin('SwaggerBake',['bootstrap' => true, 'routes' => true]);
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $csrf = new CsrfProtectionMiddleware([
            'httponly' => true,
        ]);
        $bodyParser = new BodyParserMiddleware();
        $middlewareQueue
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))

            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this))

            // Parse various types of encoded request bodies so that they are
            // available as array through $request->getData()
            // https://book.cakephp.org/4/en/controllers/middleware.html#body-parser-middleware
            ->add($bodyParser)

            // Cross Site Request Forgery (CSRF) Protection Middleware
            // https://book.cakephp.org/4/en/controllers/middleware.html#cross-site-request-forgery-csrf-middleware
            ->add(new AuthenticationMiddleware($this));

            // $bodyParser->addParser(['multipart/form-data'],function($body,$request) {
            //     echo "<pre>";
            //     print_r($body);
            //     echo "</pre>";
            //     exit();
            //     return $body;
            // });
            // $bodyParser->addParser(['text/html'],function($body){
            //     return $this;
            // });

            $csrf->skipCheckCallback(function ($request) {
                // Skip token check for API URLs.
                if ($request->getParam('prefix') === 'api') {
                    return true;
                }
            });
        

        return $middlewareQueue;
    }
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface {
        $service = new AuthenticationService();
        $fieldsForIdentify = [
            IdentifierInterface::CREDENTIAL_USERNAME => 'user_name',
            IdentifierInterface::CREDENTIAL_PASSWORD => 'password'
        ];
        $service->loadIdentifier('Authentication.JwtSubject');
        $service->loadAuthenticator('Authentication.Jwt', [
            'secretKey' => file_get_contents(CONFIG . '/ssl/jwt.pem'),
            'algorithm' => 'RS256',
            'returnPayload' => false,
            'tokenPrefix' => 'Bearer',
            'header' => 'BearerAuth'
        ]);
        $service->loadIdentifier('Authentication.Password', [
            'returnPayload' => false,
            'fields' => $fieldsForIdentify,
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users',
            ]
        ]);
        

        // // Load the authenticators, you want session first
         //$service->loadAuthenticator('Authentication.Session');
        // // Configure form data check to pick email and password
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'user_name',
                'password' => 'password',
            ],
            'loginUrl' => Router::url('/api/login'),
            'returnPayload' => false,
            'passwordHasher' => 'default',
        ]);

        return $service;
}

/**
 * @return * @return \Authentication\Authenticator\ResultInterface
 */
//  public function authenticate(ServerRequestInterface $request){
//      echo "<pre>";
//      print_r($request);
//      echo "</pre>";
//      exit();

//     return;

//  }
    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void
    {
    }

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        $this->addOptionalPlugin('Cake/Repl');
        $this->addOptionalPlugin('Bake');

        $this->addPlugin('Migrations');

        // Load more plugins here
    }
}
