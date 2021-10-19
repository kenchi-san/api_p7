<?php

namespace App\Controller;

use App\Repository\ProductPhoneRepository;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PhoneController extends AbstractController
{
    /**
     * @Route("api/phone", name="phone",methods={"GET"})
     * @param ProductPhoneRepository $phoneRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, ProductPhoneRepository $phoneRepository, SerializerInterface $serializer): Response
    {
        $page = $request->get('page', 1);
        $limit  = $request->get('limit', 1);


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


        $jsonContent = $serializer->serialize($paginatedCollection, 'json');
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;

    }

    /**
     * @Route ("/api/phone/{id}",name="detail_phone",methods={"GET"})
     * @param Request $request
     * @param ProductPhoneRepository $phone
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function detail(Request $request, ProductPhoneRepository $phone, SerializerInterface $serializer): Response
    {
        $showPhone = $phone->find($request->get('id'));
        $jsonContent = $serializer->serialize($showPhone, 'json');
        $response = new Response($jsonContent);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


}
