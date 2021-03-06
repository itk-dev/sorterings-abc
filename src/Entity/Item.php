<?php

/*
 * This file is part of Sorterings-ABC.
 *
 * (c) 2018–2019 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Blameable\Traits\BlameableEntity;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *     collectionOperations={"get"},
 *     itemOperations={"get"},
 *     normalizationContext={"groups"={"item_read"}},
 *     attributes={"order"={"name"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"name": "partial"})
 * @ApiFilter(OrderFilter::class, properties={"name": "ASC"})
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    use TimestampableEntity;
    use BlameableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Groups({"item_read", "item_description_read", "item_category_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"item_read", "item_description_read", "item_category_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"item_read", "item_description_read", "item_category_read"})
     */
    private $shortDescription;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ItemDescription", inversedBy="items")
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"item_read"})
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ItemCategory", inversedBy="items")
     *
     * @Groups({"item_read"})
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name ?? __CLASS__;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getDescription(): ?ItemDescription
    {
        return $this->description;
    }

    public function setDescription(?ItemDescription $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|ItemCategory[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(ItemCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(ItemCategory $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}
