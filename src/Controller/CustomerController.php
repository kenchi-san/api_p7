<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class CustomerController extends AbstractController
{
    //TODO a retravailler
    /**
     * @param TokenStorageInterface $tokenStorageInterface
     * @param JWTTokenManagerInterface $jwtManager
     */
    public function __construct(TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    /**
     * @Route ("api/customer", name="customer",methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     @Model(type=Customer::class),
     *     description="Returns the list of customers",
     *     @OA\JsonContent(
     *        ref=@Model(type=Customer::class, groups={"customer:list"})
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
     * @OA\Tag(name="customer")
     * @Security(name="Bearer")
     *
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
    public function index(Request $request, CustomerRepository $customerRepository, SerializerInterface $serializer): Response
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);
        $listCustomer = $customerRepository->findCustomerFromUser($this->getUser(), $page, $limit);
        $paginatedCollection = new PaginatedRepresentation(
            new CollectionRepresentation($listCustomer),
            "customer", // route
            [], // route parameters
            $page,       // page number
            $limit,      // limit
            ceil($listCustomer->count() / $limit),       // total pages
            'page',  // page route parameter name, optional, defaults to 'page'
            'limit', // limit route parameter name, optional, defaults to 'limit'
            false,   // generate relative URIs, optional, defaults to `false`
            $listCustomer->count()       // total collection size, optional, defaults to `null`
        );
        $jsonContent = $serializer->serialize($paginatedCollection, 'json', SerializationContext::create()->setGroups(['customer:list', 'Default']));
        $JsonResponse = new JsonResponse($jsonContent, "200", ['Content-Type' => 'application/json'], true);
        $JsonResponse->setMaxAge(3600);
        return $JsonResponse;
    }

    /**
     * @Route("api/customer/{id}",name="detail_customer",methods={"GET"})
     * @param Customer $customer
     * @param SerializerInterface $serializer
     * @return Response
     * @OA\Tag(name="customer")
     * @IsGranted("CUSTOMER_VIEW", subject="customer")
     */
    public function detail(Customer $customer, SerializerInterface $serializer): Response
    {
        $jsonContent = $serializer->serialize(
            $customer,
            'json', SerializationContext::create()->setGroups(['groups' => 'customer:detail'])
        );
        $response = new JsonResponse($jsonContent, Response::HTTP_OK, [], true);
        $response->setMaxAge(3600);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Route("api/add/customer",name="add_customer",methods={"POST"})
     * @OA\Tag(name="customer")
     * @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="surname",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phone",
     *                     oneOf={
     *                     	   @OA\Schema(type="string"),
     *                     	   @OA\Schema(type="integer"),
     *                     }
     *                 ),
     *                 @OA\Property(
     *                     property="mail",
     *                     @OA\Schema(type="string")
     *                 ),
     *                  @OA\Property(
     *                     property="address",
     *                     @OA\Schema(type="string")
     *                 ),
     *
     *                 example={"name": "hugo", "surname": "Smith", "phone": 12345678,"mail":"smith.hugo@gmail.com","address":"6 rue du test"}
     *             )
     *         )
     *     ,
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(ref="#/components/schemas/Result"),
     *                 @OA\Schema(type="boolean")
     *             }
     *         )
     *     )
     * )
     */
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $manager): Response
    {
        if (!$this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $customer = $serializer->deserialize($request->getContent(), Customer::class, 'json');
        $customer->setMembershipNumber(uniqid());
        $customer->setUser($this->getUser());
        $manager->persist($customer);
        $test = $serializer->serialize($customer, "json", SerializationContext::create()->setGroups(['groups' => 'customer:detail']));
        $manager->flush();
        return new JsonResponse($test, Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route ("api/delete/customer/{id}",name="delete_customer",methods={"DELETE"})
     * @param Request $request
     * @param CustomerRepository $customerRepository
     * @param EntityManagerInterface $manager
     * @return Response
     * @OA\Tag(name="customer")
     * @IsGranted("CUSTOMER_DELETE", subject="customer")
     */
    public function delete(Request $request, CustomerRepository $customerRepository, EntityManagerInterface $manager): Response
    {

        $customer = $customerRepository->find($request->get("id"));
        $manager->remove($customer);
        $manager->flush();
        return new Response("", Response::HTTP_FOUND);


    }

    /**
     * @Route ("api/login_check", name="login",methods={"GET"})
     *
     * @OA\Parameter(
     *     name="username",
     *     required=true,
     *     in="query",
     *     description="The user name for login",
     *     @OA\Schema(
     *         type="string"
     *     )
     *   ),
     * @OA\Parameter(
     *     name="password",
     *     in="query",
     *     @OA\Schema(
     *         type="string",
     *     ),
     *     description="The password for login in clear text",
     *   ),
     * @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(type="string"),
     *     @OA\Header(
     *       header="X-Rate-Limit",
     *       @OA\Schema(
     *           type="integer",
     *           format="int32"
     *       ),
     *       description="calls per hour allowed by the user"
     *     ),
     *     @OA\Header(
     *       header="X-Expires-After",
     *       @OA\Schema(
     *          type="string",
     *          format="date-time",
     *       ),
     *       description="date in UTC when token expires"
     *     )
     *   ),
     * @OA\Response(response=400, description="Invalid username/password supplied")
     * )
     */
    public function login(Request $request, User $user, UserAuthenticatorInterface $authenticator, UserInterface $userInterface): JsonResponse
    {
        return $this->json([$authenticator->authenticateUser($request->get("username"))
        ]);
    }
}
