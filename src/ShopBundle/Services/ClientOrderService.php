<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 16.12.2018 г.
 * Time: 17:08
 */

namespace ShopBundle\Services;


use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use ShopBundle\Entity\ClientOrder;
use ShopBundle\Repository\ClientOrderRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class ClientOrderService implements ClientOrderServiceInterface {

	/**
	 * @var ClientOrderRepository $orderRepository
	 */
	private $orderRepository;

	/**
	 * @var UserRepository $userRepository
	 */
	private $userRepository;

	/**
	 * ClientOrderService constructor.
	 *
	 * @param ClientOrderRepository $orderRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct( ClientOrderRepository $orderRepository,UserRepository $userRepository) {
		$this->orderRepository = $orderRepository;
		$this->userRepository =  $userRepository;
	}


	/**
	 * @param User $user
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createOrder(User $user): ?string {
		$session = new Session();
		$order = new ClientOrder();
		try {
			$order->setUser($user);
			$order->setTotalPrice(floatval($session->get('total')['total-price']));
			foreach ($session->get('product_cart') as $product){
				 $order->addPurchaseProduct($product);
			}
			$this->userRepository->updateUser($user);
			$this->orderRepository->addOrder($order);

			$session->remove('product_count');
			$session->remove('product_cart');
			$session->remove('total');
			
			return 'Your order is processed and will be shipping within the given time';
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}

	}

	public function deleteOrder() {
		// TODO: Implement deleteOrder() method.
	}

	/**
	 * @return mixed
	 * @throws \Exception
	 */
	public function listAllOrders() {
		try {
			return $this->orderRepository->findAllOrders();
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}
	}

	public function orderDetail() {
		// TODO: Implement orderDetail() method.
	}
}