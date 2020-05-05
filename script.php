<?php declare(strict_types=1);

$checkerTask1_1 = new Checker(function (int $current) {return $current <= 20;});
$checkerTask1_2 = new Checker(
    function (int $current) {return $current % 3 === 0;},
    'pa',
    $checkerTask1_1
);
$checkerTask1_3 = new Checker(
    function (int $current) {return $current % 5 === 0;},
    'pow',
    $checkerTask1_2
);
$checkerTask1 = new Checker(
    function (int $current) {return $current % 15 === 0;},
    'papow',
    $checkerTask1_3,
    ' '
);

$checkerTask2_1 = new Checker(function (int $current) {return $current <= 15;});
$checkerTask2_2 = new Checker(
    function (int $current) {return $current <= 15 && $current % 2 === 0;},
    'hatee',
    $checkerTask2_1
);
$checkerTask2_3 = new Checker(
    function (int $current) {return $current <= 15 && $current % 7 === 0;},
    'ho',
    $checkerTask2_2
);
$checkerTask2 = new Checker(
    function (int $current) {return $current <= 15 && $current % 14 === 0;},
    'papow',
    $checkerTask2_3,
    '-'
);

$checkerTask3_1 = new Checker(function (int $current) {return $current <= 10;});
$checkerTask3_2 = new Checker(
    function (int $current) {return $current <= 10 && in_array($current, [1, 4, 9], true);},
    'joff',
    $checkerTask3_1
);
$checkerTask3_3 = new Checker(
    function (int $current) {return $current <= 10 && $current > 5;},
    'tchoff',
    $checkerTask3_2
);
$checkerTask3 = new Checker(
    function (int $current) {return $current <= 10 && $current > 5 && in_array($current, [1, 4, 9], true);},
    'jofftchoff',
    $checkerTask3_3,
    '-'
);


$acc = new Accumulator($checkerTask1, $checkerTask2, $checkerTask3);

for ($i = 1; $i <= 20; $i++) {
    $acc->chackAll($i);
}

$acc->print();

class Accumulator {
    /**
     * @var array|Checker[]
     */
    private $checkers = [];

    public function __construct(Checker ...$checkers)
    {
        $this->checkers = $checkers;
    }

    public function chackAll(int $current)
    {
        foreach ($this->checkers as $checker) {
            $checker->run($current);
        }
    }

    public function print()
    {
        foreach ($this->checkers as $checker) {
            echo $checker;
        }
    }
}

class Checker {
    private $condition;
    private $word;
    private $next;
    private $delimiter;
    private $result = [];

    public function __construct(
        callable $condition = null,
        string $word = null,
        Checker $next = null,
        string $delimiter = '')
    {
        $this->condition = $condition;
        $this->word = $word;
        $this->next = $next;
        $this->delimiter = $delimiter;
    }

    public function run(int $current)
    {
        $this->result[] = $this->check($current);
    }

    /**
     * @param int $current
     * @return int|string|null
     */
    private function check(int $current)
    {
        if (call_user_func($this->condition, $current)) {
            return $this->word ?? $current;
        }

        return $this->next ? $this->next->check($current) : null;
    }

    public function __toString()
    {
        return implode(
            $this->delimiter,
            array_filter($this->result))
            . PHP_EOL;
    }
}
