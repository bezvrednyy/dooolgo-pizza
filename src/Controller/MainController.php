<?php
namespace App\Controller;

use App\Repository\PizzaRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MainController extends AbstractController
{
    /**
     * @Route("/menu", name="menu")
     * @param Packages $assetManager
     * @param PizzaRepository $pizzaRepository
     * @param OrderRepository $orderRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function menu(Packages $assetManager, PizzaRepository $pizzaRepository, OrderRepository $orderRepository, UserRepository $userRepository): Response
    {
        $orders = [];
        $menu = $pizzaRepository->findBy([], ['number' => 'ASC']);
        foreach ($menu as &$pizza)
        {
            $img = $pizza->getImg();
            $pizza->setImg($assetManager->getUrl("img/${img}"));
        }

        if ( $this->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            $email = $this->getUser()->getUsername();
            $user = $userRepository->findOneBy(['email' => $email]);
            $orders = $orderRepository->findBy(['user' => $user]);
        }

        return $this->render('menu.html.twig', [
            'menu' => $menu,
            'countOrders' => count($orders)
        ]);
    }

    /**
     * @Route("/", name="default")
     */
    public function default(): Response
    {
        return $this->redirectToRoute('menu');
    }

    /**
     * @Route("/signup", name="signup")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @return Response
     */
    public function signUp(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository): Response
    {
        if ( $this->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            return $this->redirectToRoute('menu');
        }

        $email = json_decode($request->getContent(), true);
        if ($email)
        {
            $email = $email['checkUserRegistered'];
            if ( $userRepository->findOneBy(['email' => $email]) === null )
            {
                return new Response( json_encode(false) );
            }
            return new Response( json_encode(true) );
        }

        $user = new User($userRepository->nextId(),'', '', '', '', ['ROLE_USER']);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() )
        {
            $user = $form->getData();
            $user->setPassword( $passwordEncoder->encodePassword($user, $user->getPassword()) );
            $userRepository->add($user);
            return $this->redirectToRoute('menu', [
                'success' => true
            ]);
        }

        return $this->render('signup.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users", name="users")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function users(UserRepository $userRepository): Response
    {
        if ( !$this->isGranted('ROLE_ADMIN') )
        {
            throw $this->createAccessDeniedException('Access Denied');
        }
        $users = $userRepository->findAll();
        foreach ($users as &$user) {
            $roles = $user->getRoles();
            $mainRole = array_shift($roles);
            $roleForTwig = explode('_', $mainRole, 2);
            array_shift($roleForTwig);
            $user->setRoles($roleForTwig);
        }

        return $this->render('users.html.twig', [
            'users' => $users
        ]);
    }
}