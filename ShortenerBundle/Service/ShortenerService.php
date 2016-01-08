<?php


namespace ShortenerBundle\Service;

use Doctrine\ORM\EntityManager;
use Hashids\Hashids;
use ShortenerBundle\Entity\Short;
use ShortenerBundle\Exception\NotFoundException;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Class ShortenerService
 *
 * Implementation based on links below
 *
 * @link http://www.flickr.com/groups/api/discuss/72157616713786392/
 * @link http://stackoverflow.com/questions/742013/how-to-code-a-url-shortener?rq=1
 */
class ShortenerService implements ShortenerServiceInterface
{

    /**
     * @var EntityManager $entityManager
     */
    protected $entityManager;
    /**
     * @var Router
     */
    private $router;
    /**
     * @var Hashids
     */
    private $hashids;

    /**
     * @param EntityManager $entityManager
     * @param Router        $router
     * @param Hashids       $hashids
     */
    public function __construct(EntityManager $entityManager, Router $router, Hashids $hashids)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->hashids = $hashids;
    }

    /**
     * {@inheritdoc}
     */
    public function decode($code)
    {
        $id = $this->grabId($code);
        $short = $this->entityManager->find("ShortenerBundle:Short", $id);
        if (!$short) {
            throw new NotFoundException();
        }
        $url = $short->getUrl();

        if(!preg_match('/^[A-Za-z]+:\/\//', $url)) {
            $url = "http://" . $url;
        }

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function encode($url, $returnUrl = true)
    {
        $shortRepository = $this->entityManager->getRepository("ShortenerBundle:Short");
        $short = $shortRepository->findOneBy(['url' => $url]);
        if (!$short) {
            $short = new Short();
            $short->setUrl($url);

            $this->entityManager->persist($short);
            $this->entityManager->flush();

            $id = $short->getId();
            $code = $this->createCode($id);
            $short->setCode($code);

            $this->entityManager->persist($short);
            $this->entityManager->flush();
        }

        if(!$returnUrl) {
            return $short->getCode();
        }

        return $this->router->generate('shortener_redirect_code', ['code' => $short->getCode()], true);
    }

    /**
     * @param int $id
     *
     * @return string
     */

    protected function createCode($id)
    {
        return $this->hashids->encode($id);
    }

    /**
     * @param string $code
     *
     * @return int
     */

    protected function grabId($code)
    {
        return $this->hashids->decode($code)[0];
    }
}