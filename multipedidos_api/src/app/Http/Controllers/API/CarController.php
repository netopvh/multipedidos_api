<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Car\CarUpdateRequest;
use OpenApi\Attributes as OA;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Car\CarStoreRequest;
use App\Services\Contracts\CarServiceInterface;
use Illuminate\Http\Response;

#[OA\Info(title: "Documentação API Multipedidos", version: "1.0.0")]
class CarController extends ApiController
{
    public function __construct(
        private readonly CarServiceInterface $carService
    ) {
    }


    #[OA\Get(
        path: '/api/cars',
        tags: ["Carros"],
        summary: "Lista carros",
        description: "Retorna uma lista com todos os carros cadastrados no banco de dados",
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Lista carregada com sucesso'),
        ]
    )]
    public function index()
    {
        return $this->respondWithSuccess(
            $this->carService->getAll(),
            "Lista carregada com sucesso"
        );
    }

    #[OA\Post(
        path: '/api/cars',
        tags: ["Carros"],
        summary: "Cria um carro",
        description: "Cria um novo carro de acordo com os atributos passados pelo corpo da requisição",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    required: ["name", "brand", "year", "description"],
                    properties: [
                        new OA\Property(
                            property: "model",
                            type: "string",
                            example: "Gol"
                        ),
                        new OA\Property(
                            property: "brand",
                            type: "string",
                            example: "Volkswagen"
                        ),
                        new OA\Property(
                            property: "year",
                            type: "integer",
                            example: 2021
                        ),
                        new OA\Property(
                            property: "color",
                            type: "string",
                            example: "Branco"
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
    public function store(CarStoreRequest $request)
    {
        $car = $this->carService->create($request->all());

        return $this->respondWithCreated($car, "Carro criado com sucesso");
    }

    #[OA\Get(
        path: '/api/cars/{id}',
        tags: ["Carros"],
        summary: "Exibe um carro",
        description: "Exibe as informações do carro passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do carro",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Carro carregado com sucesso'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Carro não encontrado')
        ]

    )]
    public function show(int $id)
    {
        if (! $car = $this->carService->getById($id))
            return $this->respondWithError("Carro não encontrado", Response::HTTP_NOT_FOUND);

        return $this->respondWithSuccess($car, "Carro carregado com sucesso");
    }

    #[OA\Put(
        path: '/api/cars/{id}',
        tags: ["Carros"],
        summary: "Atualiza um carro",
        description: "Atualiza as informações do carro passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do carro",
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
                    required: ["name", "brand", "year", "description"],
                    properties: [
                        new OA\Property(
                            property: "model",
                            type: "string",
                            example: "Gol"
                        ),
                        new OA\Property(
                            property: "brand",
                            type: "string",
                            example: "Volkswagen"
                        ),
                        new OA\Property(
                            property: "year",
                            type: "integer",
                            example: 2021
                        ),
                        new OA\Property(
                            property: "color",
                            type: "string",
                            example: "Branco"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Carro atualizado com sucesso'),
            new OA\Response(response: Response::HTTP_UNPROCESSABLE_ENTITY, description: 'O valor informado não consta em nosso banco de dados'),
            new OA\Response(response: Response::HTTP_BAD_REQUEST, description: 'Carro não encontrado')
        ]

    )]
    public function update(CarUpdateRequest $request, $id)
    {
        try {
            return $this->respondWithSuccess(
                $this->carService->update($request->all(), $id),
                "Carro atualizado com sucesso",
            );
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[OA\Delete(
        path: '/api/cars/{id}',
        tags: ["Carros"],
        summary: "Remove um carro",
        description: "Remove o carro passado por parâmetro",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID do carro",
                schema: new OA\Schema(
                    type: "integer",
                )
            )
        ],
        responses: [
            new OA\Response(response: Response::HTTP_OK, description: 'Carro removido com sucesso'),
            new OA\Response(response: Response::HTTP_NOT_FOUND, description: 'Carro não encontrado')
        ]

    )]
    public function destroy($id)
    {
        try {
            $car = $this->carService->delete($id);

            return $this->respondWithNoContent("Carro deletado com sucesso", Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->respondWithError($th->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }
}
