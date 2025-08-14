<?php

namespace Source\Framework\Core;

use Source\Framework\Support\Message;

/**
 * Class Controller
 *
 * @package Source\Core
 */
class Controller
{
    /** @var Message */
    protected $message;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->message = new Message();
    }
}
