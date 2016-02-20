<?php

namespace ItdBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="article_tag")
 * @ORM\HasLifecycleCallbacks()
 */

class article_teg{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(type="smallint")
     */
    public $article_id;

    /**
     * @ORM\Column(type="smallint")
     */

    public $tag_id;


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
     * Set article
     *
     * @param \ItdBundle\Entity\Article $article
     *
     * @return article_teg
     */
    public function setArticle($article)
    {
        $this->article_id = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \ItdBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set tag
     *
     * @param \ItdBundle\Entity\Tag $tag
     *
     * @return article_teg
     */
    public function setTag($tag)
    {
        $this->tag_id = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return \ItdBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }
}
