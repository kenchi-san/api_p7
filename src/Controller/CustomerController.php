<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CustomerController extends AbstractController
{
    /**
     * @Route("api/customer", name="customer")
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(CustomerRepository $customerRepository,SerializerInterface $serializer): Response
    {
        $listCustomer = $customerRepository->findAll();
        $jsonContent = $serializer->serialize($listCustomer, 'json');
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;
    }

    /**
     * @Route("api/customer/{id}")
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function detail(Request $request,CustomerRepository $customerRepository,SerializerInterface $serializer): Response
    {
        $showCustomer = $customerRepository->find($request->get('id'));
        $jsonContent = $serializer->serialize($showCustomer, 'json');
        $response = new Response($jsonContent);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
