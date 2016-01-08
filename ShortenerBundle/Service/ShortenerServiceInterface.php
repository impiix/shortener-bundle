<?php


namespace ShortenerBundle\Service;

use ShortenerBundle\Exception\NotFoundException;

interface ShortenerServiceInterface
{

    /**
     * @param string $code
     *
     * @return string
     * @throws NotFoundException
     */
    function decode($code);

    /**
     * @param string $url
     * @param bool   $returnUrl if true return full url, otherwise just code
     *
     * @return string
     */
    function encode($url, $returnUrl = true);
}