<?php

namespace ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use ShopBundle\Validator\Constraints as Cons;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\CategoryRepository")
 */
class Category
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
	 * @Assert\NotBlank()
	 * @Assert\Length(
	 *      min = 2,
	 *      max = 50,
	 *      minMessage = "Title must be at least {{ limit }} characters long",
	 *      maxMessage = "Title must be longer than{{ limit }} characters long"
	 * )
	 * @Cons\ParentNameConstraints()
	 *
	 * @ORM\Column(name="title", type="string", length=100)
	 */
	private $title;

	/**
	 * @ORM\Column(name="code",nullable=true)
	 */
	private $code;

	/**
	 * @var string
	 *
	 * @Gedmo\Slug(fields={"code","title"})
	 * @ORM\Column(name="slug", type="string", length=100, unique=true)
	 */
	private $slug;

	/**
	 * @var int
	 * @Gedmo\TreeLeft
	 * @ORM\Column(name="lft", type="integer")
	 */
	private $lft;

	/**
	 * @var int
	 * @Gedmo\TreeLevel
	 * @ORM\Column(name="lvl", type="integer")
	 */
	private $lvl;

	/**
	 * @var int
	 * @Gedmo\TreeRight
	 * @ORM\Column(name="rgt", type="integer")
	 */
	private $rgt;


	/**
	 * @Gedmo\TreeRoot
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Category",cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="tree_root", referencedColumnName="id")
	 */
	private $root;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Category", inversedBy="children",cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Category", mappedBy="parent")
	 * @ORM\OrderBy({"lft" = "ASC"})
	 */
	private $children;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Product",mappedBy="category")
	 */
	private $products;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\Promotion",mappedBy="category")
	 */
	private $promotions;

	/**
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="ShopBundle\Entity\ProductImage",mappedBy="category")
	 */
	private $images;

	public function __toString() {
		return $this->title;
	}

	/**
	 * Category constructor.
	 */
	public function __construct() {
		$this->products = new ArrayCollection();
		$this->children = new ArrayCollection();
		$this->promotions = new ArrayCollection();
		$this->images = new ArrayCollection();
	}




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
     * @return Category
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

	/**
	 *
	 * @param $code
	 *
	 * @return Category
	 */
	public function setCode( $code ): Category {
		$this->code = $code;

		return $this;
	}

	/**
	 * @return Category
	 */
	public function getCode(){
    	return $this->code;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set lft.
     *
     * @param int $lft
     *
     * @return Category
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft.
     *
     * @return int
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl.
     *
     * @param int $lvl
     *
     * @return Category
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl.
     *
     * @return int
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt.
     *
     * @param int $rgt
     *
     * @return Category
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt.
     *
     * @return int
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root.
     *
     * @param Category|null $root
     *
     * @return Category
     */
    public function setRoot(Category $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root.
     *
     * @return Category|null
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Set parent.
     *
     * @param Category|null $parent
     *
     * @return Category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return Category|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child.
     *
     * @param Category $child
     *
     * @return Category
     */
    public function addChild(Category $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param Category $child
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeChild(Category $child)
    {
        return $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add product.
     *
     * @param Product $product
     *
     * @return Category
     */
    public function addProduct( Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct( Product $product)
    {
        return $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add promotion.
     *
     * @param Promotion $promotion
     *
     * @return Category
     */
    public function addPromotion( Promotion $promotion)
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    /**
     * Remove promotion.
     *
     * @param Promotion $promotion
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePromotion( Promotion $promotion)
    {
        return $this->promotions->removeElement($promotion);
    }

    /**
     * Get promotions.
     *
     * @return Collection
     */
    public function getPromotions()
    {
        return $this->promotions;
    }

	/**
	 * @param  $images
	 *
	 * @return Category
	 */
	public function setImages(  $images ): Category {
		$this->images = $images;

		return $this;
	}

	/**
	 * @return Collection
	 */
	public function getImages() {
		return $this->images;
	}



    /**
     * Add image.
     *
     * @param \ShopBundle\Entity\ProductImage $image
     *
     * @return Category
     */
    public function addImage(\ShopBundle\Entity\ProductImage $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image.
     *
     * @param \ShopBundle\Entity\ProductImage $image
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeImage(\ShopBundle\Entity\ProductImage $image)
    {
        return $this->images->removeElement($image);
    }
}
