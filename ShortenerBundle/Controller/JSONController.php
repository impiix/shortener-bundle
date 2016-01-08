<?php
/**
 * Date: 1/8/16
 * Time: 6:06 PM
 */
namespace ShortenerBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class JSONController
 * @package ShortenerBundle\Controller
 */
class JSONController
{
    /**
     * @param $data
     *
     * @return Response
     */
    public function dataResponse($data)
    {
        $json = $this->get("jms_serializer")->serialize($data, 'json');

        $response = new Response($json);
        $response->headers->set("Content-Type", "application/json");
        return new Response($json);
    }

    /**
     * @param $code
     * @param $message
     *
     * @return Response
     */
    public function errorResponse($code, $message)
    {
        $json = $this->get("jms_serializer")->serialize(['message' => $message], 'json');

        $response = new Response($json);
        $response->headers->set("Content-Type", "application/json");
        $response->setStatusCode($code);

        return new Response($json);
    }
}
