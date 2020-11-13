<?php

declare(strict_types=1);

namespace App\Controller\Rest\Book;

use App\Application\Query\Book\DTO\BookDTO;
use App\Application\Query\Book\GetBook\GetBookQuery;
use App\Infrastructure\Http\HttpSpec;
use App\Infrastructure\Http\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class GetBookAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/api/{_locale}/book/{id}", methods={"GET"}, requirements={"id": "\d+","_locale":"en|ru"}, name="api_get_book")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     *
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description=HttpSpec::STR_HTTP_OK,
     *     @SWG\Schema(ref=@Model(type=BookDTO::class, groups={"book_view"}))
     * )
     * @SWG\Response(response=Response::HTTP_NOT_FOUND, description=HttpSpec::STR_HTTP_NOT_FOUND)
     * @SWG\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @SWG\Tag(name="Author")
     */
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);

        $author = $this->handle(new GetBookQuery($route->getRequiredInt('id')));

        return new JsonResponse(
            $this->normalizer->normalize($author, '', ['groups' => ['book_view']]),
        );
    }
}
