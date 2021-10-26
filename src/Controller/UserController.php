<?php

namespace App\Controller;

use App\Repository\UserRepository;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserController extends AbstractController
{


    /**
     * @Route("api/user", name="user",methods={"GET"})
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(UserRepository $userRepository, SerializerInterface $serializer): Response
    {
        $listUser = $userRepository->findAll();
        $jsonContent = $serializer->serialize($listUser, 'json', SerializationContext::create()->setGroups(['groups' => 'user']));
        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');
        $response->setMaxAge(3600);
        return $response;
    }

    /**
     * @Route ("/api/user/{id}",name="detail_user",methods={"GET"})
     * @param Request $request
     * @param UserRepository $phone
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function detail(Request $request, UserRepository $phone, SerializerInterface $serializer): Response
    {
        $showPhone = $phone->find($request->get('id'));
        $jsonContent = $serializer->serialize($showPhone, 'json', SerializationContext::create()->setGroups(['groups' => 'user:detail']));
        $response = new Response($jsonContent);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
