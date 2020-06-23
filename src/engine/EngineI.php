<?php

namespace Engine;

use Traits\EngineTrait;

abstract class EngineI
{
    use EngineTrait;

    private $maxskill;
    private $minskill;
    private $maxstat = 6;
    private $minstat = 1;
    private $maxdice = 6;

   abstract public function LaunchDice(string $input = null): array;

   abstract public function RollDice(int $int): int;
}