<?php

namespace IOGames\Jesker;

class PacketMatcher
{
    /**
     * @var array
     */
    protected $testRule;

    /**
     * @param array $testRule
     * @return $this
     */
    public function setRule(array $testRule)
    {
        $this->testRule = $testRule;

        return $this;
    }

    /**
     * @param array $inputData
     * @return bool|string
     */
    public function doesMatch(array $inputData)
    {
        $packetId = false;

        if (count($inputData) !== count($this->testRule)) {
            return false;
        }

        for ($i = 0; $i < count($inputData); $i++) {
            $match = false;

            if (is_array($this->testRule[$i])) {
                foreach ($this->testRule[$i] as $testSubRule) {
                    if ($inputData[$i] === $testSubRule) {
                        $match = true;
                        break;
                    }
                }
            }

            if ($inputData[$i] === $this->testRule[$i] || $this->testRule[$i] === '*') {
                $match = true;
            }

            if ($this->testRule[$i] === '$ID$') {
                $match = true;
                $packetId = $inputData[$i];
            }

            if (!$match) {
                return false;
            }
        }

        if ($packetId) {
            return $packetId;
        }

        return true;
    }
}