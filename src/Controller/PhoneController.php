<?php

namespace App\Controller;

use App\Entity\ProductPhone;
use App\Repository\ProductPhoneRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PhoneController extends AbstractController
{
    /**
     * @Route("api/phone", name="phone",methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     @Model(type=ProductPhone::class),
     *     description="Returns the list of phones",
     *     @OA\JsonContent(
     *        ref=@Model(type=ProductPhone::class, groups={"phone:list"})
     *     )
     * )
     *
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="limit in page",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="products")
     * @Security(name="Bearer")
     * @param Request $request
     * @param ProductPhoneRepository $phoneRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, ProductPhoneRepository $phoneRepository, SerializerInterface $serializer): Response
    {
        $page = $request->get('page', 1);
        $limit  = $request->get('limit', 10);


        $paginator = $phoneRepository->findAllPaginated($page,$limit);


        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($paginator),
            "phone", // route
            [], // route parameters
            $page,       // page number
            $limit,      // limit
            ceil($paginator->count() / $limit),       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false,   // generate relative URIs, optional, defaults to `false`
            $paginator->count()       // total collection size, optional, defaults to `null`
        );

        $jsonContent = $serializer->serialize($paginatedCollection,'json',SerializationContext::create()->setGroups(['phone:list','Default']));
        $response = new JsonResponse($jsonContent, 200, [], true);
        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;

    }

    /**
     * @Route ("/api/phone/{id}",name="detail_phone",methods={"GET"})
     * @OA\Tag(name="products")
     * @param ProductPhone $phone
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function detail(ProductPhone $phone, SerializerInterface $serializer): Response
    {
        $jsonContent = $serializer->serialize($phone, 'json', SerializationContext::create()->setGroups(['phone:detail']));

        $response = new JsonResponse($jsonContent, 200, [], true);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
