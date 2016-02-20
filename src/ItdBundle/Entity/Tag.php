<?php

namespace ItdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\Entity()
 *  @ORM\Table(name="tag")
 */

class Tag
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="smallint", name="id")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection|Article[]
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags")
     */

    protected $articles;

    /**
     * Default constructor, initializes collections
     */
    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    /**
     *
     * @ORM\Column(type="string", length=100)
     */
    public $name;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add article
     *
     * @param \ItdBundle\Entity\Article $article
     *
     * @return Tag
     */
    public function addArticle(\ItdBundle\Entity\Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove article
     *
     * @param \ItdBundle\Entity\Article $article
     */
    public function removeArticle(\ItdBundle\Entity\Article $article)
    {
        $this->articles->removeElement($article);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getArticles()
    {
        return $this->articles;
    }



    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
