<?php

/*
 * Project: deep-insight-api.
 * Author: Levan Ostrowski
 * User: cod3venom
 * Date: 10.02.2022
 * Time: 10:17
*/

namespace App\Modules\LazyLoading;

use InvalidArgumentException;

abstract class LazyLoading
{
    private int $start = 0;
    private int $limit = 50;
    private string $filteringColumn = 'id';
    private string $filteringDirection = '';

    public function getStart(): int {
        return $this->start;
    }

    public function setStart(int $start): self {
        $this->start = $start;
        return $this;
    }

    public function getLimit(): int {
        return $this->limit;
    }

    public function setLimit(int $limit): self {
        $this->limit = $limit;
        return $this;
    }

    public function getFilteringColumn(): string {
        return $this->filteringColumn;
    }

    public function setFilteringColumn(string $filteringColumn): self {
        $this->filteringColumn = $filteringColumn;
        return $this;
    }

    public function getFilteringDirection(): string {
        return $this->filteringDirection;
    }

    public function setFilteringDirection(string $filteringDirection): self {
        $this->verifyDirection($filteringDirection);
        $this->filteringDirection = $filteringDirection;
        return $this;
    }

    /**
     * @param string $direction
     * @return void
     */
    private function verifyDirection(string $direction): void
    {
        $direction = strtolower($direction);
        $flag = false;
        switch ($direction) {
            case 'asc':
            case 'desc':
                $flag =  true;
                break;
            default:
                $flag = false;
        }

        if (!$flag) {
            throw new InvalidArgumentException('LazyLoading:: Wrong ordering direction');
        }
    }
}
