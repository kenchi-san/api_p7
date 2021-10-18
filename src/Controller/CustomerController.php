<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $listCustomer = $customerRepository->findCustomerFromUser($this->getUser(), 1);
        $jsonContent = $serializer->serialize($listCustomer, 'json', SerializationContext::create()->setGroups(['customer:list', 'Default', 'user']));

        $JsonResponse = new JsonResponse($jsonContent, "200", ['Content-Type' => 'application/json'], true);
        $JsonResponse->setMaxAge(3600);
        return $JsonResponse;
    }

    /**
     * @Route("api/customer/{id}",name="detail_customer",methods={"GET"})
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     *
     */
    public function detail(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer): Response
    {
        $showCustomer = $customerRepository->find($request->get('id'));
        $this->denyAccessUnlessGranted('CUSTOMER_VIEW', $showCustomer);
        $jsonContent = $serializer->serialize(
            $showCustomer,
            'json', SerializationContext::create()->setGroups(array('groups' => 'customer:detail'))
        );
        $response = new JsonResponse($jsonContent, 200, [], true);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("api/add/customer",name="add_customer",methods={"POST"})
     *
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json');
        $customer->setUser($this->getUser());
        $manager->persist($customer);
        $test = $serializer->serialize($customer, "json", SerializationContext::create()->setGroups(array('groups' => 'customer:detail')));
        $manager->flush();
        return new JsonResponse($test, Response::HTTP_CREATED);

    }

    /**
     * @Route ("api/delete/customer/{id}",name="delete_customer",methods={"DELETE"})
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function delete(Request $request, CustomerRepository $customerRepository, EntityManagerInterface $manager): Response
    {
        $customer = $customerRepository->find($request->get('id'));
        $this->denyAccessUnlessGranted('CUSTOMER_DELETE', $customer);
        $manager->remove($customer);
        $manager->flush();
        return new Response("", Response::HTTP_FOUND);


    }
}
