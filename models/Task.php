<?php

class Task
{
    private $id;
    private $title;
    private $description;
    private $is_completed;
    private $user_id;

    private $taskLine = [];

    public function __construct($id, $title, $description, $is_completed, $user_id, $taskLine)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->is_completed = $is_completed;
        $this->user_id = $user_id;
        $this->taskLine = TaskLineRepository::getTaskLinesByOrderId($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getIs_completed()
    {
        return $this->is_completed;
    }

    public function getUser_id()
    {
        return $this->user_id;
    }

}