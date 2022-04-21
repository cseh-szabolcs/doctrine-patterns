<?php

namespace CS\DoctrinePatterns\Entity;

interface IdInterface
{
    /**
     * @return int|string
     */
    public function getId();
}
