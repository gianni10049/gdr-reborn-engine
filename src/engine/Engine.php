<?php

namespace Engine;

interface Engine
{
    public function getData(string $input = null): array;
}