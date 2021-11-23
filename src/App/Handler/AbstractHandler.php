<?php

declare(strict_types=1);

namespace App\Handler;

use App\Exception as AppException;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\TableGateway\Feature\GlobalAdapterFeature;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Filter as LaminasFilter;
use Laminas\InputFilter as LaminasInputFilter;
use Laminas\Validator as LaminasValidator;
use Mezzio\ProblemDetails\ProblemDetailsResponseFactory;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

/**
 *
 */
abstract class AbstractHandler implements RequestHandlerInterface
{
    /**
     *
     * @var array
     */
    protected array $config = [];

    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     *
     * @var ProblemDetailsResponseFactory
     */
    private ProblemDetailsResponseFactory $problemDetailsFactory;

    /**
     * @param ContainerInterface $container
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->setContainer($container);

        $this->setProblemDetails($container->get(ProblemDetailsResponseFactory::class));

        GlobalAdapterFeature::setStaticAdapter($container->get(Adapter::class));

        $this->setConfig($container->get('config'));
    }

    /**
     * @param ContainerInterface $container
     * @return self
     */
    public function setContainer(ContainerInterface $container) : self
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param ProblemDetailsResponseFactory $problemDetailsFactory
     * @return $this
     */
    public function setProblemDetails(ProblemDetailsResponseFactory $problemDetailsFactory): self
    {
        $this->problemDetailsFactory = $problemDetailsFactory;

        return $this;
    }

    /**
     * @param array $config
     * @return self
     */
    public function setConfig(array $config) : self
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $action = $request->getAttribute('action');

        $response = null;

        $inputFilter = new LaminasInputFilter\InputFilter();

        $input = new LaminasInputFilter\Input('action');
        $input->getValidatorChain()->attach(new LaminasValidator\NotEmpty(
            ['messages' => ['isEmpty' => 'A action não foi informada']]
        ));
        $input->getFilterChain()->attach(new LaminasFilter\Word\DashToCamelCase());
        $inputFilter
            ->add($input);

        $inputFilter->setData(['action' => $action]);

        try {
            if ($inputFilter->isValid()) {
                $validatedData = $inputFilter->getValues();
                $action = lcfirst($validatedData['action']) . 'Action';

                if (!method_exists($this, $action)) {
                    throw new AppException\PageNotFoundException();
                }
            } else {
                throw new AppException\PageNotFoundException();
            }
        } catch (AppException\PageNotFoundException $e) {
            $response = $this->errorResponse($e, $request);
        }

        if (!$response) {
            try {
                $response = $this->$action($request);
            } catch (AppException\PageNotFoundException|AppException\MethodNotAllowedException $e) {
                $response = $this->errorResponse($e, $request);
            }
        }

        if (!$response) {
            $response = new EmptyResponse();
        }

        return $response;
    }

    /**
     *
     * @param Throwable $e
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function errorResponse(Throwable $e, ServerRequestInterface $request) : ResponseInterface
    {
        $additional = [] ;
        if (method_exists($e, 'getAdditional')) {
            $additional = $e->getAdditional();
        }

        return $this->problemDetailsFactory->createResponse(
            $request,
            $e->getCode(),
            $e->getMessage(),
            '',
            '',
            $additional
        );
    }
}
