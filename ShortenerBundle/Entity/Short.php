<?php


namespace ShortenerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Short
 *
 * @ORM\Entity
 * @ORM\Table(name="shortener")
 */
class Short
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="url", type="string")
     */
    protected $url;

    /**
     * @var string
     * @ORM\Column(name="code", type="string", nullable=true)
     */
    protected $code;

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
     * Set url
     *
     * @param string $url
     *
     * @return Short
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Short
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
