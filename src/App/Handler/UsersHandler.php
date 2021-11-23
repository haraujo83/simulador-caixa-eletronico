<?php

declare(strict_types=1);

namespace App\Handler;

use App\Exception as AppException;
use App\TableGateway\UsersTableGateway;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Laminas\InputFilter\Input;
use Laminas\InputFilter\InputFilter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 *
 */
class UsersHandler extends AbstractHandler
{
    /**
     *
     *
     * @param ServerRequestInterface $request
     * @return void
     * @throws AppException\MethodNotAllowedException
     */
    protected function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'GET') {
            throw new AppException\MethodNotAllowedException();
        }

        $inputFilter = $this->indexInputFilter();
        $inputFilter->setData($request->getQueryParams());

        $query = null;
        if ($inputFilter->isValid()) {
            $query = $inputFilter->getValue('q');
        }

        $projectTable = new UsersTableGateway();
        $result = $projectTable->search($query);

        $data = compact('query', 'result');

        return new JsonResponse($data);
    }

    /**
     * @return InputFilter
     */
    protected function indexInputFilter(): InputFilter
    {
        $inputFilter = new InputFilter();

        $input = (new Input())->setRequired(false);
        $input->getFilterChain()
            ->attach(new StringTrim())
            ->attach(new StringToLower());

        $inputFilter->add($input, 'q');

        return $inputFilter;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws AppException\MethodNotAllowedException
     */
    protected function incluirAction(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'GET') {
            throw new AppException\MethodNotAllowedException();
        }

        $data = [];

        return new JsonResponse($data);
    }
}
