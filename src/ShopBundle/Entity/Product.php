<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, unique=true)
     */
    private $title;

	/**
	 * @var string
	 *
	 * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(name="slug", length=255)
	 */
    private $slug;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="quantity", type="integer")
	 */
    private $quantity;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string|null
     *
     * @ORM\Column(name="features", type="text", nullable=true)
     */
    private $features;

    /**
     * @var string|null
     *
     * @ORM\Column(name="information", type="text", nullable=true)
     */
    private $information;

    /**
     * @var int|null
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating;

    /**
     * @var \DateTime|null
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="dateCreated", type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var \DateTime|null
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="dateEdit", type="datetime", nullable=true)
     */
    private $dateEdit;

	/**
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Category",inversedBy="products",cascade={"persist"})
	 * @ORM\JoinColumn(name="categoryId",referencedColumnName="id")
	 */
	private $category;

	/**
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Promotion",inversedBy="products")
	 * @ORM\JoinColumn(name="promotionId",referencedColumnName="id")
	 */
	private $promotion;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Product
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

	/**
	 * @param string $slug
	 *
	 * @return Product
	 */
	public function setSlug( string $slug ): Product {
		$this->slug = $slug;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSlug(){
		return $this->slug;
	}

	/**
	 * @return int
	 */
	public function getQuantity() {
		return $this->quantity;
	}

	/**
	 * @param int $quantity
	 */
	public function setQuantity( int $quantity ): void {
		$this->quantity = $quantity;
	}

    /**
     * Set description.
     *
     * @param string|null $description
     *
     * @return Product
     */
    public function setDescription($description = null)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set features.
     *
     * @param string|null $features
     *
     * @return Product
     */
    public function setFeatures($features = null)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get features.
     *
     * @return string|null
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set information.
     *
     * @param string|null $information
     *
     * @return Product
     */
    public function setInformation($information = null)
    {
        $this->information = $information;

        return $this;
    }

    /**
     * Get information.
     *
     * @return string|null
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Set rating.
     *
     * @param int|null $rating
     *
     * @return Product
     */
    public function setRating($rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating.
     *
     * @return int|null
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set dateCreated.
     *
     * @param \DateTime|null $dateCreated
     *
     * @return Product
     */
    public function setDateCreated($dateCreated = null)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime|null
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateEdit.
     *
     * @param \DateTime|null $dateEdit
     *
     * @return Product
     */
    public function setDateEdit($dateEdit = null)
    {
        $this->dateEdit = $dateEdit;

        return $this;
    }

    /**
     * Get dateEdit.
     *
     * @return \DateTime|null
     */
    public function getDateEdit()
    {
        return $this->dateEdit;
    }



    /**
     * Set category.
     *
     * @param Category|null $category
     *
     * @return Product
     */
    public function setCategory( Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set promotion.
     *
     * @param Promotion|null $promotion
     *
     * @return Product
     */
    public function setPromotion( Promotion $promotion = null)
    {
        $this->promotion = $promotion;

        return $this;
    }

    /**
     * Get promotion.
     *
     * @return Promotion|null
     */
    public function getPromotion()
    {
        return $this->promotion;
    }

}
