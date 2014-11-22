<?php

namespace App\Presenters;

use Nette,
    App\Model;


// ...

class CalendarPresenter extends BasePresenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    // ...
    public function renderDefault()
    {
    $this->template->posts = $this->database->table('posts')
        ->order('created_at ASC')
        ->limit(5);
    }

}
