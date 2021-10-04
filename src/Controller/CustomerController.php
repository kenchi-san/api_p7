<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class CustomerController extends AbstractController
{

    /**
     * @Route ("api/customer", name="customer",methods={"GET"})
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(CustomerRepository $customerRepository, SerializerInterface $serializer): Response
    {
        $listCustomer = $customerRepository->findAll();

        $jsonContent = $serializer->serialize(
            $listCustomer,
            'json',["groups"=>"customer:list"]
           );
        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;
    }

    /**
     * @Route("api/customer/{id}",name="detail_customer",methods={"GET"})
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function detail(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer): Response
    {

        return $this->json($customerRepository->find($request->get('id')),200,[],["groups"=>"customer:detail"]);

        //TODO reponse vide ?
//        $showCustomer = $customerRepository->find($request->get('id'));
//        $jsonContent = $serializer->serialize(
//            $showCustomer,
//            'json',["groups"=>"customer:detail"]
//        );
//        $response = new JsonResponse($jsonContent,200,[],true);
//        $response->setMaxAge(3600);
//        $response->headers->set('Content-Type', 'application/json');
//        return $response;
    }

    /**
     * @Route("api/add/customer",name="add_customer",methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        //TODO corriger
        $objContent = $serializer->deserialize($request->getContent(), Customer::class, 'json',["groups"=>"customer:add"]);
        $manager->persist($objContent);
        $manager->flush();
        return new Response('', Response::HTTP_CREATED);

    }

    /**
     * @Route ("api/delete/customer/{id}",name="delete_customer",methods={"DELETE"})
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Request $request,SerializerInterface $serializer,CustomerRepository $customerRepository, EntityManagerInterface $manager): Response
    {
        //TODO a faire
        $customer=$customerRepository->find($request->get('id'));

        $manager->remove($customer);
        return new Response("", Response::HTTP_FOUND);


    }
}
