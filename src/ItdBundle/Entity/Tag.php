<?php

namespace ItdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\AccessType;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\Entity()
 *  @ORM\Table(name="tag")
 */

class Tag
{
    /**
     * @ORM\ManyToMany(targetEntity="Article", mappedBy="tags", cascade={"persist", "remove"}")
     * @ORM\JoinTable(name="article_tag")
     */

    /**
     * @var ArrayCollection
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
     * @ORM\Id @ORM\Column(type="string", length=100)
     */
    public $name;

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
