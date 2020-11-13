<?php

declare(strict_types=1);

namespace App\Controller\Rest\Book;

use App\Application\Query\Book\DTO\BookDTO;
use App\Application\Query\Book\SearchBook\SearchBookQuery;
use App\Infrastructure\Http\HttpSpec;
use App\Infrastructure\Http\ParamFetcher;
use App\Infrastructure\ValueObject\Pagination\PaginatedData;
use App\Infrastructure\ValueObject\Pagination\Pagination;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SearchBooksAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/api/{_locale}/book/search", methods={"GET"}, name="api_search_book", requirements={"_locale":"en|ru"})
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     *
     * @SWG\Parameter(
     *     name="search",
     *     in="query",
     *     type="string",
     *     description="Search phrase text"
     * )
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     default=Pagination::DEFAULT_LIMIT,
     *     description="Number of result items"
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     default=Pagination::DEFAULT_OFFSET,
     *     description="First result offset"
     * )
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description=HttpSpec::STR_HTTP_OK,
     *     @SWG\Schema(type="array", @SWG\Items(ref=@Model(type=BookDTO::class, groups={"book_list"})))
     * )
     * @SWG\Response(response=Response::HTTP_BAD_REQUEST, description=HttpSpec::STR_HTTP_BAD_REQUEST)
     * @SWG\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @SWG\Tag(name="Book")
     */
    public function __invoke(Request $request): Response
    {
        $query = ParamFetcher::fromRequestQuery($request);

        $query = new SearchBookQuery(
            Pagination::fromRequest($request),
            $request->getLocale(),
            $query->getNullableString('search'),
        );

        /** @var PaginatedData $paginatedData */
        $paginatedData = $this->handle($query);

        return new JsonResponse(
            $this->normalizer->normalize($paginatedData->getData(), '', ['groups' => 'book_list']),
            Response::HTTP_OK,
            [HttpSpec::HEADER_X_ITEMS_COUNT => $paginatedData->getCount()]
        );
    }
}
