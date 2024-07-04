<?php

namespace App\Http\State;

use App\Http\State\Mutations\ProductIndexMutations;
use Exception;

class StateManager
{
    protected $modules = [
        'ProductIndex' => ProductIndexModule::class
    ];

    public function getState(string $module): array
    {
        return session($module);
    }

    public function updateState(string $module, array $data, ?ProductIndexMutations $mutation = null): static
    {
        if (! in_array($module, array_keys($this->modules))) {
            throw new Exception('invalid module in StateManager@updateState');
        }

        $currentState = array_replace(session($module) ?? [], $data);
        $newState     = (new $this->modules[$module])->updateState($currentState, $mutation);

        session([$module => $newState]);

        return $this;
    }

    public function resetState(string $module): void
    {
        session([$module => (new $this->modules[$module])->getDefaultState()]);
    }

    public function resetStateForAllModules(): void
    {
        foreach ($this->modules as $module => $class)
        {
            $this->resetState($module);
        }
    }
}
