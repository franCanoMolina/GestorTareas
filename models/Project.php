<?php

class Project
{
    private $id;
    private $name;
    private $owner_id;

    public function __construct($id, $name, $owner_id)
    {
        $this->id = (int)$id;
        $this->name = $name;
        $this->owner_id = (int)$owner_id;
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getOwnerId() { return $this->owner_id; }
}