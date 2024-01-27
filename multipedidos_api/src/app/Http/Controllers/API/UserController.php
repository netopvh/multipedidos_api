<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\User\UserAttachCarRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;
use App\Http\Controllers\ApiController;
use App\Services\Contracts\UserServiceInterface;

class UserController extends ApiController
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
    }

    #[OA\Get(
        path: '/api/users',
        tags: ["Usuários"],
        summary: "Lista usuários",
        description: "Retorna uma lista com todos os usuários cadastrados no banco de dados",
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Lista carregada com sucesso'),
        ]
    )]
    public function index()
    {
        return $this->respondWithSuccess(
            $this->userService->getAll(),
            "Lista carregada com sucesso"
        );
    }

    #[OA\Put(
        path: '/api/users/update/{id}',
        tags: ["Usuários"],
        summary: "Atualizar e-mail e senha",
        description: "Atualiza o e-mail e a senha do usuário passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do usuário",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["email", "password", "password_confirmation"],
                    properties: [
                        new OA\Property(
                            property: "email",
                            type: "string",
                            example: "janedoe@gmail.com"
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example: "123456"
                        ),
                        new OA\Property(
                            property: "password_confirmation",
                            type: "string",
                            example: "123456"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Senha atualizada com sucesso'),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'O valor informado para o campo id não existe na base de dados'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Usuário não encontrado')
        ]

    )]
    public function updatePassword(UserUpdatePasswordRequest $request, int $id)
    {
        if (! $user = $this->userService->update($request->validated(), $id))
            return $this->respondWithError("Usuário não encontrado", Response::HTTP_NOT_FOUND);

        return $this->respondWithNoContent("Senha atualizada com sucesso", Response::HTTP_OK);
    }

    #[OA\Post(
        path: '/api/users',
        tags: ["Usuários"],
        summary: "Cria um novo usuário",
        description: "Cria um novo usuário de acordo com os atributos passados pelo corpo da requisição",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["name", "email", "password", "password_confirmation"],
                    properties: [
                        new OA\Property(
                            property: "name",
                            type: "string",
                            example: "John Doe"
                        ),
                        new OA\Property(
                            property: "email",
                            type: "string",
                            example: "johndoe@gmail.com"
                        ),
                        new OA\Property(
                            property: "password",
                            type: "string",
                            example: "123456"
                        ),
                        new OA\Property(
                            property: "password_confirmation",
                            type: "string",
                            example: "123456"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'Usuário criado com sucesso'),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Erro de validação')
        ]

    )]
    public function store(UserStoreRequest $request)
    {
        $user = $this->userService->create($request->all());

        return $this->respondWithCreated($user, "Usuário criado com sucesso");
    }

    #[OA\Get(
        path: '/api/users/{id}',
        tags: ["Usuários"],
        summary: "Exibe um usuário",
        description: "Exibe as informações do usuário passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do usuário",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Usuário carregado com sucesso'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Usuário não encontrado')
        ]

    )]
    public function show(Request $request, int $id)
    {
        $perPage = $request->query('per_page', 10);

        if (! $car = $this->userService->getByIdPaginated($id, $perPage))
            return $this->respondWithError("Usuário não encontrado", Response::HTTP_NOT_FOUND);

        return $this->respondWithSuccess($car, "Usuário carregado com sucesso");
    }

    #[OA\Post(
        path: '/api/users/attach-car/{id}',
        tags: ["Usuários"],
        summary: "Atribui veículo ao usuário",
        description: "Faz a atribuição de um veículo ao usuário passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do usuário",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["car_id"],
                    properties: [
                        new OA\Property(
                            property: "car_id",
                            type: "integer",
                            example: 1
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'Carro criado com sucesso'),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Erro de validação')
        ]
    )]
    public function attachCar(UserAttachCarRequest $request, $id)
    {
        try {
            $this->userService->attachCar($request->all(), $id);
            return $this->respondWithNoContent("Carro associado com sucesso", Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }



    #[OA\Post(
        path: '/api/users/detach-car/{id}',
        tags: ["Usuários"],
        summary: "Remove veículo do usuário",
        description: "Remove um veículo que foi atribuído ao usuário passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do usuário",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["car_id"],
                    properties: [
                        new OA\Property(
                            property: "car_id",
                            type: "integer",
                            example: 1
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_CREATED, description: 'Carro criado com sucesso'),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'Erro de validação')
        ]
    )]

    public function detachCar(UserAttachCarRequest $request, $id)
    {
        try {
            $this->userService->detachCar($request->all(), $id);
            return $this->respondWithNoContent("Carro desassociado com sucesso", Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->respondWithError($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[OA\Delete(
        path: '/api/users/{id}',
        tags: ["Usuários"],
        summary: "Remove um usuário",
        description: "Remove o usuário passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do usuário",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Usuário removido com sucesso'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Usuário não encontrado')
        ]

    )]
    public function destroy($id)
    {
        try {
            $car = $this->userService->delete($id);

            return $this->respondWithNoContent("Usuário deletado com sucesso", Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
