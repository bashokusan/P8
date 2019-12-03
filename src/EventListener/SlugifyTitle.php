<?php


namespace App\EventListener;


use App\Entity\Task;
use App\Utils\Slugger;

class SlugifyTitle
{
    public function prePersist(Task $task)
    {
        $task->setSlug(Slugger::slugify($task->getTitle()));
    }
}
