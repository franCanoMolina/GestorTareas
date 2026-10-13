<?php

// Definimos la clase TaskLine para representar un paso o subtarea dentro de una tarea
class TaskLine
{
    private $id;
    private $taskId;
    private $text;
    private $isCompleted;

    // Constructor con los datos de la subtarea
    public function __construct($id, $taskId, $text, $isCompleted = false)
    {
        $this->id = $id;
        $this->taskId = $taskId;
        $this->text = $text;
        $this->isCompleted = $isCompleted;
    }

    // Devuelve el id de la subtarea
    public function getId()
    {
        return $this->id;
    }

    // Devuelve el id de la tarea padre a la que pertenece
    public function getTaskId()
    {
        return $this->taskId;
    }

    // Devuelve el texto o descripcion de este paso
    public function getText()
    {
        return $this->text;
    }

    // Devuelve si este paso esta completado (true o false)
    public function isCompleted()
    {
        return (bool)$this->isCompleted;
    }
}