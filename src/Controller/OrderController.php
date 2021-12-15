<?php
namespace App\Controller;

use App\Database\Lock;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Repository\OrderRepository;
use App\Repository\PizzaRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{
    /**
     * @Route("/orders", name="orders")
     * @param OrderRepository $orderRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function orders(OrderRepository $orderRepository, UserRepository $userRepository): Response
    {
        if ( !$this->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            return $this->redirectToRoute('signin');
        }
        $email = $this->getUser()->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);
        $orders = $orderRepository->findBy([], ['number' => 'ASC']);
        $ordersForTwig = [];
        $countUserOrders = 0;
        foreach ($orders as $order) {
            $notice = 0;
            if ($order->getUser()->getId() === $user->getId())
            {
                $notice = 1;
                $countUserOrders++;
            }
            $ordersForTwig[] = [
                'id' => $order->getId(),
                'number' => $order->getNumber(),
                'pizza' => $order->getPizza()->getName(),
                'price' => $order->getPizza()->getPrice(),
                'user' => $order->getUser()->getName(),
                'address' => $order->getUser()->getAddress(),
                'status' => $order->getStatus(),
                'notice' => $notice
            ];
        }

        return $this->render('orders.html.twig', [
            'orders' => $ordersForTwig,
            'countOrders' => $countUserOrders
        ]);
    }

    /**
     * @Route("/addOrder", name="addOrder")
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function addOrder(Request $request, OrderRepository $orderRepository, UserRepository $userRepository, PizzaRepository $pizzaRepository): Response
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new Response( json_encode(['redirectToRoute' => '/signin']) );
        }
        $pizzaId = $request->getContent();
        $pizzaId = json_decode($pizzaId);
        $pizza = $pizzaRepository->findOneBy(['id' => $pizzaId]);
        $email = $this->getUser()->getUsername();
        $user = $userRepository->findOneBy(['email' => $email]);
        $lock = new Lock('addOrder');
        $result = $lock->execute(function () use ($orderRepository, $pizza, $user) {
            $order = new Order($orderRepository->nextId(), $orderRepository->maxNumber() + 1, $pizza, $user, OrderStatus::pending);
            $orderRepository->add($order);
        });

        if ($result)
        {
            return new Response( json_encode(['addOrder' => true]) );
        }
        return new Response( json_encode(['addOrder' => false]) );
    }


    /**
     * @Route("/delOrder", name="delOrder")
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @param UserRepository $userRepository
     * @return Response
     */
    public function delOrder(Request $request, OrderRepository $orderRepository, UserRepository $userRepository): Response
    {
        $orderId = $request->getContent();
        $orderId = json_decode($orderId);
        $order = $orderRepository->find($orderId);
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUsername()]);
        $checkingUserRights = (( $order->getUser()->getId() === $user->getId() ) || $this->isGranted('ROLE_ADMIN') );
        if ( !$checkingUserRights )
        {
            throw $this->createAccessDeniedException('Access Denied');
        }
        if ( $order->getStatus() === OrderStatus::pending ) {
            $orderRepository->del($order);
            return new Response( json_encode(true) );
        }
        return new Response( json_encode(false) );
    }

    /**
     * @Route("/changeOrderStatus", name="changeOrderStatus")
     * @param Request $request
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function changeOrderStatus(Request $request, OrderRepository $orderRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $statusesToChange = json_decode( $request->getContent(), true );
        $ordersIdList = array_keys($statusesToChange);
        $orders = $orderRepository->findByMultipleId($ordersIdList);
        foreach ($orders as $order)
        {
            $orderId = $order->getId();
            $status = $statusesToChange[$orderId];
            if ($status >= OrderStatus::pending && $status <= OrderStatus::completed)
            {
                $order->setStatus($status);
            }
        }
        $entityManager->flush();
        return new Response(json_encode(true));
    }
}