<?php
namespace ProjectRena\Task\WebSockets;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Ratchet\Wamp\Topic;

class echoWebsocket implements MessageComponentInterface {
    /**
     * @var \SplObjectStorage
     */
    protected $clients;

    /**
     *
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
    }

    /**
     * When a new connection is opened it will be passed to this method
     * @param  ConnectionInterface $conn The socket/connection that just connected to your application
     * @throws \Exception
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    /**
     * This is called before or after a socket is closed (depends on how it's closed).  SendMessage to $conn will not result in an error if it has already been closed.
     * @param  ConnectionInterface $conn The socket/connection that is closing/closed
     * @throws \Exception
     */
    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $conn->close();
    }

    /**
     * If there is an error with one of the sockets, or somewhere in the application where an Exception is thrown,
     * the Exception is sent back down the stack, handled by the Server and bubbled back up the application through this method
     * @param  ConnectionInterface $conn
     * @param  \Exception $e
     * @throws \Exception
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
        $this->clients->detach($conn);
        $conn->close();
    }

    function onMessage(ConnectionInterface $conn, $msg)
    {
        $conn->send($msg);
    }
}