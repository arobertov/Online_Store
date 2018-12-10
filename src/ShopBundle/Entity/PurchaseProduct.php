<?php


namespace ShopBundle\Entity;


class PurchaseProduct {
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var Product
	 */
	private $product;

	/**
	 * @var string
	 */
	private $productTitle;

	/**
	 * @var int
	 */
	private $productQuantity;

	/**
	 * @var float
	 */
	private $productPrice;

	/**
	 * @var string
	 */
	private $imagePath;

	/**
	 * ProductCart constructor.
	 *
	 * @param Product $product
	 */
	public function __construct( Product $product ) {
		$this->product = $product;
		$this->id = $product->getId();
		$this->productTitle = $product->getTitle();
		$this->productQuantity = $product->getQuantity();
		$this->productPrice = $product->getPromotion()!=null?$product->getDiscount():$product->getPrice();
		$this->imagePath = $product->getFirstImage()!=null?$product->getFirstImage()->getPath():null;
	}


	public function getId(){
		return $this->id;
	}

	/**
	 * @return Product
	 */
	public function getProduct(): Product {
		return $this->product;
	}

	/**
	 * @param Product $product
	 */
	public function setProduct( Product $product ): void {
		$this->product = $product;
	}

	/**
	 * @return string|null
	 */
	public function getProductTitle(): ?string {
		return $this->productTitle;
	}

	/**
	 * @param string $productTitle
	 */
	public function setProductTitle( string $productTitle ): void {
		$this->productTitle = $productTitle;
	}

	/**
	 * @return int|null
	 */
	public function getProductQuantity(): ?int {
		return $this->productQuantity;
	}

	/**
	 * @param int $productQuantity
	 */
	public function setProductQuantity( int $productQuantity ): void {
		$this->productQuantity = $productQuantity;
	}

	/**
	 * @return float
	 */
	public function getProductPrice(): float {
		return $this->productPrice;
	}

	/**
	 * @param float $productPrice
	 */
	public function setProductPrice( float $productPrice ): void {
		$this->productPrice = $productPrice;
	}

	/**
	 * @return string
	 */
	public function getImagePath(): string {
		return $this->imagePath;
	}

	/**
	 * @param string $imagePath
	 */
	public function setImagePath( string $imagePath ): void {
		$this->imagePath = $imagePath;
	}


}