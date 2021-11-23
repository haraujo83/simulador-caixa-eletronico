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

use function lcfirst;
use function method_exists;

/**
 * Classe abstrata para os handlers
 */
abstract class AbstractHandler implements RequestHandlerInterface
{
    /** @var array */
    protected array $config = [];

    protected ContainerInterface $container;

    private ProblemDetailsResponseFactory $problemDetailsFactory;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        ContainerInterface $container
    ) {
        $this->container = $container;

        $this->problemDetailsFactory = $container->get(ProblemDetailsResponseFactory::class);

        GlobalAdapterFeature::setStaticAdapter($container->get(Adapter::class));
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
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
            if (! $inputFilter->isValid()) {
                throw new AppException\PageNotFoundException();
            }

            $action = lcfirst($inputFilter->getValue('action')) . 'Action';

            if (! method_exists($this, $action)) {
                throw new AppException\PageNotFoundException();
            }
        } catch (AppException\PageNotFoundException $e) {
            $response = $this->errorResponse($e, $request);
        }

        if (! $response) {
            try {
                $response = $this->$action($request);
            } catch (
                AppException\PageNotFoundException |
                AppException\MethodNotAllowedException |
                AppException\InputFilterValidationFailedException $e
            ) {
                $response = $this->errorResponse($e, $request);
            }
        }

        if (! $response) {
            $response = new EmptyResponse();
        }

        return $response;
    }

    /**
     * @param Throwable $e Exceção
     * @param ServerRequestInterface $request Requisição
     * @return ResponseInterface Resposta
     */
    private function errorResponse(Throwable $e, ServerRequestInterface $request): ResponseInterface
    {
        $additional = [];
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
