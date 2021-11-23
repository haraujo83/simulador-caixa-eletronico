<?php

declare(strict_types=1);

namespace App\Handler;

use App\Exception as AppException;
use App\InputFilter\UsersDeleteInputFilter;
use App\InputFilter\UsersInsertInputFilter;
use App\InputFilter\UsersSearchInputFilter;
use App\InputFilter\UsersUpdateInputFilter;
use App\TableGateway\UsersTableGateway;
use Fig\Http\Message\StatusCodeInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Classe com métodos para gerenciar Usuários
 */
class UsersHandler extends AbstractHandler
{
    /**
     * @returns ResponseInterface
     * @throws AppException\MethodNotAllowedException
     */
    protected function indexAction(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'GET') {
            throw new AppException\MethodNotAllowedException();
        }

        $inputFilter = new UsersSearchInputFilter();
        $inputFilter->setData($request->getQueryParams());

        $query = null;
        if ($inputFilter->isValid()) {
            $query = $inputFilter->getValue('q');
        }

        $table  = new UsersTableGateway();
        $result = $table->search($query);

        $data = ['query' => $query, 'result' => $result];

        return new JsonResponse($data);
    }

    /**
     * @throws AppException\MethodNotAllowedException
     * @throws AppException\InputFilterValidationFailedException
     */
    protected function incluirAction(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'POST') {
            throw new AppException\MethodNotAllowedException();
        }

        $post = $request->getParsedBody();

        $post['id_status'] = 1;

        $inputFilter = new UsersInsertInputFilter();
        $inputFilter->setData($post);

        if (! $inputFilter->isValid()) {
            throw AppException\InputFilterValidationFailedException::forInputErr($inputFilter->getMessages());
        }

        $table = new UsersTableGateway();

        $num = $table->insert($inputFilter->getValues());

        if ($num) {
            $h = StatusCodeInterface::STATUS_CREATED;

            $id = $table->lastInsertValue;

            $data = [
                'success' => true,
                'msg'     => 'Inclusão de registro efetuada com sucesso',
                'id'      => $id,
            ];
        } else {
            $h = StatusCodeInterface::STATUS_ACCEPTED;

            $data = [
                'success' => false,
                'err'     => 'Falha na tentativa de incluir registro',
            ];
        }

        return new JsonResponse($data, $h);
    }

    /**
     * @throws AppException\MethodNotAllowedException
     * @throws AppException\InputFilterValidationFailedException
     */
    protected function alterarAction(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'POST') {
            throw new AppException\MethodNotAllowedException();
        }
        $id = $request->getParsedBody()['id'] ?? null;
        if (isset($id)) {
            $id = (int) $id;
        }

        $inputFilter = new UsersUpdateInputFilter($id);
        $inputFilter->setData($request->getParsedBody());

        if (! $inputFilter->isValid()) {
            throw AppException\InputFilterValidationFailedException::forInputErr($inputFilter->getMessages());
        }

        $values = $inputFilter->getValues();
        $id     = $values['id'];

        $table = new UsersTableGateway();

        $num = $table->update($inputFilter->getValues(), ['id' => $id]);

        if ($num) {
            $h = StatusCodeInterface::STATUS_CREATED;

            $data = [
                'success' => true,
                'msg'     => 'Alteração de registro efetuada com sucesso',
                'id'      => $id,
            ];
        } else {
            $h = StatusCodeInterface::STATUS_ACCEPTED;

            $data = [
                'success' => false,
                'err'     => 'Nenhum registro foi alterado',
            ];
        }

        return new JsonResponse($data, $h);
    }

    /**
     * @throws AppException\MethodNotAllowedException
     * @throws AppException\InputFilterValidationFailedException
     */
    protected function excluirAction(ServerRequestInterface $request): ResponseInterface
    {
        if ($request->getMethod() !== 'POST') {
            throw new AppException\MethodNotAllowedException();
        }

        $inputFilter = new UsersDeleteInputFilter();
        $inputFilter->setData($request->getParsedBody());

        if (! $inputFilter->isValid()) {
            throw AppException\InputFilterValidationFailedException::forInputErr($inputFilter->getMessages());
        }

        $id = $inputFilter->getValue('id');

        $table = new UsersTableGateway();

        $values = [
            'deleted' => 1,
        ];

        $num = $table->update($values, ['id' => $id]);

        if ($num) {
            $h = StatusCodeInterface::STATUS_CREATED;

            $data = [
                'success' => true,
                'msg'     => 'Registro excluído com sucesso',
                'id'      => $id,
            ];
        } else {
            $h = StatusCodeInterface::STATUS_ACCEPTED;

            $data = [
                'success' => false,
                'err'     => 'Nenhum registro foi alterado',
            ];
        }

        return new JsonResponse($data, $h);
    }
}
