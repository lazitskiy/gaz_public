<?php

declare(strict_types=1);

namespace App\Controller\Rest\Book;

use App\Application\Command\Book\CreateBook\CreateBookCommand;
use App\Infrastructure\Http\HttpSpec;
use App\Infrastructure\Http\ParamFetcher;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

final class CreateBookAction
{
    use HandleTrait;

    private RouterInterface $router;

    public function __construct(MessageBusInterface $commandBus, RouterInterface $router)
    {
        $this->messageBus = $commandBus;
        $this->router = $router;
    }

    /**
     * @Route("/api/book", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="JSON Payload",
     *          required=true,
     *          format="application/json",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="name_ru", type="string", example="Войма и мир"),
     *              @SWG\Property(property="name_en", type="string", example="War and peace"),
     *              @SWG\Property(property="author_ids", type="array", @SWG\Items(type="integer"), example={1,2}),
     *          )
     * )
     *
     * @SWG\Response(response=Response::HTTP_CREATED, description=HttpSpec::STR_HTTP_CREATED)
     * @SWG\Response(response=Response::HTTP_BAD_REQUEST, description=HttpSpec::STR_HTTP_BAD_REQUEST)
     * @SWG\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @SWG\Tag(name="Book")
     */
    public function __invoke(Request $request): Response
    {
        $paramFetcher = ParamFetcher::fromRequestBody($request);

        $command = new CreateBookCommand(
            $paramFetcher->getRequiredString('name_ru'),
            $paramFetcher->getRequiredString('name_en'),
            $paramFetcher->getRequiredArray('author_ids'),
        );

        $id = $this->handle($command);

        $resourceUrl = $this->router->generate('api_get_book', ['id' => $id, '_locale' => $request->getLocale()]);

        return new JsonResponse(null, Response::HTTP_CREATED, [HttpSpec::HEADER_LOCATION => $resourceUrl]);
    }
}
