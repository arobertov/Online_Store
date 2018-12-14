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
	 * @var string
	 */
	private $realPrice;

	/**
	 * @var string
	 */
	private $productPrice;

	/**
	 * @var string
	 */
	private $productDiscount;

	/**
	 * @var string
	 */
	private $imagePath;

	/**
	 * @var string
	 */
	private $subtotal;

	/**
	 * ProductCart constructor.
	 *
	 * @param Product $product
	 */
	public function __construct( Product $product ) {
		$this->product = $product;
		$this->id = $product->getId();
		$this->productTitle = $product->getTitle();
		$this->imagePath = $product->getFirstImage()!=null?$product->getFirstImage()->getPath():null;
		$this->productPrice = $product->getPrice();
		$this->productDiscount = $product->getPrice()*$product->getPromotion()->getDiscount();
		$this->realPrice = $product->getPrice();
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
	 * @return string
	 */
	public function getRealPrice(): string {
		return sprintf ("%.2f",$this->realPrice*$this->getProductQuantity());
	}

	/**
	 * @param string $realPrice
	 *
	 * @return PurchaseProduct
	 */
	public function setRealPrice( string $realPrice ): PurchaseProduct {

		$this->realPrice = $realPrice;
		return $this;
	}


	/**
	 * @return string
	 */
	public function getProductPrice(): string {
		return sprintf ("%.2f", $this->productPrice);
	}

	/**
	 * @param string $productPrice
	 */
	public function setProductPrice( string $productPrice ): void {
		$this->productPrice = $productPrice;
	}

	/**
	 * @return string
	 */
	public function getProductDiscount(): ?string {
			return sprintf ("%.2f",$this->productDiscount * $this->getProductQuantity());
	}

	/**
	 * @param string $productDiscount
	 *
	 * @return PurchaseProduct
	 */
	public function setProductDiscount( string $productDiscount ): PurchaseProduct {
		$this->productDiscount = $productDiscount;
		return $this;
	}



	/**
	 * @return string|null
	 */
	public function getImagePath(): ?string {
		return $this->imagePath;
	}

	/**
	 * @param string $imagePath
	 */
	public function setImagePath( string $imagePath ): void {
		$this->imagePath = $imagePath;
	}

	/**
	 * @return string
	 */
	public function getSubtotal(): string {
		return sprintf ("%.2f",($this->getRealPrice() - $this->getProductDiscount()));
	}

	private function setSubtotal( ): void {
		$this->subtotal = ($this->getRealPrice() - $this->getProductDiscount());
	}


}