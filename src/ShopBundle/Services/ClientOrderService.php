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
use mysql_xdevapi\Exception;
use ShopBundle\Entity\ClientOrder;
use ShopBundle\Repository\ClientOrderRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Security;

class ClientOrderService implements ClientOrderServiceInterface {

	/**
	 * @var ClientOrderRepository $orderRepository
	 */
	private $orderRepository;

	/**
	 * @var UserRepository $userRepository
	 */
	private $userRepository;

	private $user;

	/**
	 * ClientOrderService constructor.
	 *
	 * @param ClientOrderRepository $or
	 * @param UserRepository $ur
	 * @param Security $security
	 */
	public function __construct( ClientOrderRepository $or,UserRepository $ur,Security $security) {
		$this->orderRepository = $or;
		$this->userRepository =  $ur;
		$this->user = $security->getUser();
	}


	/**
	 * @throws \Exception
	 */
	public function createOrder() {
		$session = new Session();
		$username = $this->user->getUsername();
		$order = new ClientOrder();
		try {
			$user = $this->userRepository->getCurrentUser( $username );
			$order->setUser($user);
			$order->setTotalPrice(floatval($session->get('total')['total-price']));
			dump($order);
		} catch ( \Exception $e ) {
			throw new \Exception($e->getMessage());
		}

	}

	public function deleteOrder() {
		// TODO: Implement deleteOrder() method.
	}

	public function ordersList() {
		// TODO: Implement ordersList() method.
	}

	public function orderDetail() {
		// TODO: Implement orderDetail() method.
	}
}