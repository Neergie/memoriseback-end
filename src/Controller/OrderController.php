<?php

namespace App\Controller;

use App\Entity\BookOrder;
use App\Entity\DeliveryState;
use App\Entity\Order;
use App\Entity\PaymentStatus;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\Model\Dto\BasketDto;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class OrderController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/payment', name: 'payment_order', methods: ['POST'])]
    public function payment(
        #[MapRequestPayload] BasketDto $basketDto,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $total_price = 0;
        $order = new Order();
        foreach ($basketDto->basket as $bookOrderDto) {
            $book = $entityManager->getRepository(Book::class)->find($bookOrderDto["book_id"]);
            if (!$book) {
                return new JsonResponse($bookOrderDto, Response::HTTP_NOT_FOUND);
            }
            if ($book->getStock() <= 0 || $bookOrderDto["quantity"] > $book->getStock()) { 
                return new JsonResponse($bookOrderDto, Response::HTTP_FORBIDDEN);
            }
            $book->setStock($book->getStock() - $bookOrderDto["quantity"]);
            $entityManager->persist($book);

            $book_order = new BookOrder();
            $book_order->setBook($book);
            $book_order->setPrice($book->getPrice());
            $book_order->setQuantity($bookOrderDto["quantity"]);

            $order->addBookOrder($book_order);
            $entityManager->persist($book_order);
            $total_price += $book->getPrice() * $bookOrderDto["quantity"];
        }
        $order->setDeliveryState(DeliveryState::IN_TREATMENT);
        $order->setTotalPrice($total_price);
        $order->setUserOrder($this->getUser());
        $order->setOrderDate(new \DateTime());

        $transaction = new Transaction();
        $transaction->setPaymentMethod("CB");
        $transaction->setPaymentStatus(PaymentStatus::PAID);
        $entityManager->persist($transaction);

        $order->setTransaction($transaction);
        $entityManager->persist($order);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_OK);
    }
    #[IsGranted('ROLE_USER')]
    #[Route('/orders', name: 'list_orders', methods: ['GET'])]
    public function historicOrders(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $orders = $entityManager->getRepository(Order::class)->findBy(['userOrder' => $this->getUser()], ['orderDate' => 'DESC']);

        return JsonResponse::fromJsonString($serializer->serialize($orders, 'json'));
    }
}
