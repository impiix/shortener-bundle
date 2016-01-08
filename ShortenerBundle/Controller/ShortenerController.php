<?php

namespace ShortenerBundle\Controller;

use ShortenerBundle\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShortenerController extends JSONController
{

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function encodeAction(Request $request)
    {
        $path = ltrim($request->query->get("path"), "/");

        if (!$path) {
            return $this->errorResponse(Response::HTTP_BAD_REQUEST, "Missing 'path' parameter");
        }
        $finalPath = sprintf("http://%s/%s", $_SERVER['HTTP_HOST'], $path);
        if(filter_var($finalPath, FILTER_VALIDATE_URL) === false) {
            return $this->errorResponse(Response::HTTP_BAD_REQUEST, "Incorrect path.");
        }
        $result = $this->get("shortener.service")->encode($path);

        return $this->dataResponse(['code' => $result]);
    }

    /**
     * @param string $code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function decodeAndRedirectAction($code)
    {

        try {
            $path = $this->get("shortener.service")->decode($code);
        } catch(NotFoundException $e) {
            return $this->errorResponse(Response::HTTP_NOT_FOUND, "Not found.");
        }

        return $this->redirect($path);
    }

    /**
     * @param string $code
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function decodeAction($code)
    {
        try {
            $result = $this->get("shortener.service")->decode($code);
        } catch(NotFoundException $e) {
            return $this->errorResponse(Response::HTTP_NOT_FOUND, "Not found.");
        }

        return $this->dataResponse(['path' => $result]);
    }
}
