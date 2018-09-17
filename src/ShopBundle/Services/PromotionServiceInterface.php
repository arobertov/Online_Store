<?php
/**
 * Created by PhpStorm.
 * User: Angel
 * Date: 16.9.2018 г.
 * Time: 23:16
 */

namespace ShopBundle\Services;


use ShopBundle\Entity\Promotion;

interface PromotionServiceInterface {

	public function createPromotion(Promotion $promotion);

	public function editPromotion(Promotion $promotion);

	public function removePromotion(Promotion $promotion);

	public function getAllPromotion();

}