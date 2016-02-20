<?php

namespace ItdBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\Entity
 *  @ORM\Table(name="article")
 */

class Article
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="smallint")
     */

    public $id;

    /**
     * @var \Doctrine\Common\Collections\Collection|Tag[]
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="id")
     * @ORM\JoinTable(
     *  name="article_tag",
     *  joinColumns={
     *      @ORM\JoinColumn(name="article_id", referencedColumnName="id", onDelete="CASCADE")
     *  },
     *  inverseJoinColumns={
     *      @ORM\JoinColumn(name="tag_id", referencedColumnName="id", onDelete="CASCADE")
     *  }
     * )
     */

    /*protected $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }*/


    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     *
     * @ORM\Column(type="text")
     */
    private $text;

    /**
     *
     * @ORM\Column(type="date")
     */
    private $createData;

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
     * Set title
     *
     * @param string $title
     *
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Article
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createData
     *
     * @param \DateTime $createData
     *
     * @return Article
     */
    public function setCreateData($createData)
    {
        $this->createData = $createData;

        return $this;
    }

    /**
     * Get createData
     *
     * @return \DateTime
     */
    public function getCreateData()
    {
        return $this->createData;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    protected $nameTag;

    public function setNameTag($nameTag)
    {
        $this->nameTag = $nameTag;
    }

    public function getNameTag()
    {
        return $this->nameTag;
    }

    public function removeTags(Tag $schema){
        $this->tags->removeElement($schema);

        return $this;
    }
}
