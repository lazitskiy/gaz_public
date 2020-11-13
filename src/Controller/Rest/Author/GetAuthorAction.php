<?php

declare(strict_types=1);

namespace App\Controller\Rest\Author;

use App\Application\Query\Author\DTO\AuthorDTO;
use App\Application\Query\Author\GetAuthor\GetAuthorQuery;
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

final class GetAuthorAction
{
    use HandleTrait;

    private NormalizerInterface $normalizer;

    public function __construct(MessageBusInterface $queryBus, NormalizerInterface $normalizer)
    {
        $this->messageBus = $queryBus;
        $this->normalizer = $normalizer;
    }

    /**
     * @Route("/api/author/{id}", methods={"GET"}, requirements={"id": "\d+"}, name="api_get_author")
     *
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Response(
     *     response=Response::HTTP_OK,
     *     description=HttpSpec::STR_HTTP_OK,
     *     @SWG\Schema(ref=@Model(type=AuthorDTO::class, groups={"author_view"}))
     * )
     * @SWG\Response(response=Response::HTTP_NOT_FOUND, description=HttpSpec::STR_HTTP_NOT_FOUND)
     * @SWG\Response(response=Response::HTTP_UNAUTHORIZED, description=HttpSpec::STR_HTTP_UNAUTHORIZED)
     *
     * @SWG\Tag(name="Author")
     */
    public function __invoke(Request $request): Response
    {
        $route = ParamFetcher::fromRequestAttributes($request);

        $author = $this->handle(new GetAuthorQuery($route->getRequiredInt('id')));

        return new JsonResponse(
            $this->normalizer->normalize($author, '', ['groups' => ['author_view']]),
        );
    }
}
