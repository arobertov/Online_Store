<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 16.9.2018 г.
 * Time: 23:18
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Promotion;
use ShopBundle\Repository\PromotionRepository;

class PromotionService implements PromotionServiceInterface {

	private $promotionRepository;

	/**
	 * PromotionService constructor.
	 *
	 * @param PromotionRepository $promotionRepository
	 */
	public function __construct(PromotionRepository $promotionRepository ) {
		$this->promotionRepository = $promotionRepository;
	}


	/**
	 * @param Promotion $promotion
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function createPromotion( Promotion $promotion ) {
		try{
			return $this->promotionRepository->insertNewPromotion($promotion);
		}  catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * @param Promotion $promotion
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function editPromotion( Promotion $promotion ) {
		try {
			return $this->promotionRepository->updatePromotion($promotion);
		} catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}

	}

	/**
	 * @param Promotion $promotion
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function removePromotion( Promotion $promotion ) {
		try {
			return $this->promotionRepository->deletePromotion($promotion);
		}  catch (\Exception $e){
			throw new \Exception($e->getMessage());
		}
	}

	public function getAllPromotion() {
		return $this->promotionRepository->findAllPromotions();
	}
}