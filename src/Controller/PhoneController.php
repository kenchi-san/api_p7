<?php

namespace App\Controller;

use App\Repository\ProductPhoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class PhoneController extends AbstractController
{
    /**
     * @Route("api/phone", name="phone")
     * @param ProductPhoneRepository $phoneRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(ProductPhoneRepository $phoneRepository, SerializerInterface $serializer): Response
    {

        $listPhone = $phoneRepository->findAll();
        $jsonContent = $serializer->serialize($listPhone, 'json');
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;

    }

    /**
     * @Route ("/api/phone/{id}",name="detail_phone")
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
