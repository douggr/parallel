<?php declare(strict_types = 1);

namespace Amp\Parallel\Worker\Internal;

use Amp\Parallel\Worker\{ Task, Worker };
use Interop\Async\Awaitable;

class PooledWorker implements Worker {
    /**
     * @var callable
     */
    private $push;

    /**
     * @var \Amp\Parallel\Worker\Worker
     */
    private $worker;

    /**
     * @param \Amp\Parallel\Worker\Worker $worker
     * @param callable $push Callable to push the worker back into the queue.
     */
    public function __construct(Worker $worker, callable $push) {
        $this->worker = $worker;
        $this->push = $push;
    }

    /**
     * Automatically pushes the worker back into the queue.
     */
    public function __destruct() {
        ($this->push)($this->worker);
    }

    /**
     * {@inheritdoc}
     */
    public function isRunning(): bool {
        return $this->worker->isRunning();
    }

    /**
     * {@inheritdoc}
     */
    public function isIdle(): bool {
        return $this->worker->isIdle();
    }

    /**
     * {@inheritdoc}
     */
    public function start() {
        $this->worker->start();
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(Task $task): Awaitable {
        return $this->worker->enqueue($task);
    }

    /**
     * {@inheritdoc}
     */
    public function shutdown(): Awaitable {
        return $this->worker->shutdown();
    }

    /**
     * {@inheritdoc}
     */
    public function kill() {
        $this->worker->kill();
    }
}