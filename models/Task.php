<?php

// Definimos la clase Task para representar cada tarea de la base de datos
class Task
{
    private $id;
    private $title;
    private $description;
    private $isCompleted;
    private $userId;
    private $categoryName;

    // Constructor con los campos exactos de la tabla tasks
     public function __construct($id, $title, $description, $isCompleted, $userId, $categoryName = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->isCompleted = $isCompleted;
        $this->userId = $userId;
        $this->categoryName = $categoryName;
    }

    // Devuelve el id de la tarea
    public function getId()
    {
        return $this->id;
    }

    // Devuelve el titulo
    public function getTitle()
    {
        return $this->title;
    }

    // Devuelve la descripcion
    public function getDescription()
    {
        return $this->description;
    }

    // Devuelve si esta completada (true o false / 1 o 0)
    public function isCompleted()
    {
        return (bool)$this->isCompleted;
    }

    // Devuelve el id del usuario dueno de la tarea
    public function getUserId()
    {
        return $this->userId;
    }

    // Devuelve el nombre de la categoría
    public function getCategoryName()
    {
        return $this->categoryName;
    }
}