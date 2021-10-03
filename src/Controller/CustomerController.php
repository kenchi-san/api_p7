<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class CustomerController extends AbstractController
{

    /**
     * @Route ("api/customer", name="customer")
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(CustomerRepository $customerRepository, SerializerInterface $serializer): Response
    {
        $listCustomer = $customerRepository->findAll();

        $jsonContent = $serializer->serialize(
            $listCustomer,
            'json',["groups"=>"user"]
           );
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;
    }

    /**
     * @Route("api/customer/{id}",name="detail_customer")
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function detail(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer): Response
    {
        $showCustomer = $customerRepository->find($request->get('id'));
        $jsonContent = $serializer->serialize(
            $showCustomer,
            'json',
            ["groups"=>"user"]);
        $response = new Response($jsonContent);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("api/add/customer",name="add_customer")
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {

        $jsonContent = $serializer->deserialize($request->getContent(), Customer::class, 'json',["groups"=>"user"]);
        $manager->persist($jsonContent);
        $manager->flush();
        return new Response('', Response::HTTP_CREATED);

    }

    /**
     * @Route ("api/delete/customer/{id}",name="delete_customer")
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Request $request,SerializerInterface $serializer,CustomerRepository $customerRepository, EntityManagerInterface $manager): Response
    {
        $customer=$customerRepository->find($request->get('id'));

        $manager->remove($customer);
        return new Response("", Response::HTTP_FOUND);


    }
}
