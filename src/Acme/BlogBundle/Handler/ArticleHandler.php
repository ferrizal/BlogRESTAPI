<?php

class ArticleHandler implements ArticleHandlerInterface
{
    // ..
    public function __construct(ObjectManager $om, $entityClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    // ...
    public function get($id)
    {
        return $this->repository->find($id);
    }
}