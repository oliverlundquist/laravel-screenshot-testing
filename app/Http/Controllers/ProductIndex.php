<?php

namespace App\Http\Controllers;

use App\Http\State\Mutations\ProductIndexMutations as Mutations;
use App\Http\State\StateManager;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Factory;

class ProductIndex extends Controller
{
    private $stateModule = 'ProductIndex';
    private $viewName    = 'espresso-machine-shop.index';

    public function get(Request $request)
    {
        $data  = app(Factory::class)->make($request->all(), $this->parameterValidationRules())->validated();
        $state = (new StateManager)->updateState($this->stateModule, $data)->getState($this->stateModule);

        return response()->view($this->viewName, $state);
    }

    public function post(Request $request)
    {
        $hxRequest = $request->header('hx-request') === 'true';
        $mutation  = Mutations::tryFrom($request->header('hx-trigger-name'));

        if (! $hxRequest) {
            abort(403, 'Only htmx requests accepted');
        }
        if (! in_array($mutation, Mutations::cases())) {
            abort(403, 'Not a valid request type');
        }

        $data  = app(Factory::class)->make($request->all(), $this->mutationValidationRules($mutation))->validated();
        $state = (new StateManager)->updateState($this->stateModule, $data, $mutation)->getState($this->stateModule);

        $view     = view($this->viewName, $state)->fragment('app-contents');
        $response = response($view)->header('HX-Retarget', '#app-contents');

        if ($mutation === Mutations::SortProducts) {
            $response->header('HX-Replace-Url', '/?sort=' . $state['sort']);
        }
        return $response;
    }

    private function parameterValidationRules()
    {
        return [
            'sort' => ['in:asc,desc'],
            'take' => ['in:10,20,30,40,50'],
            'skip' => ['numeric']
        ];
    }

    private function mutationValidationRules(Mutations $mutation)
    {
        return match ($mutation) {
            Mutations::SortProducts => [
                'sort' => ['required', 'in:asc,desc']
            ],
            Mutations::AddToCart => [
                'add-product-id' => ['required', 'numeric', 'exists:products,id']
            ],
            Mutations::IncrementCartItem => [
                'increment-product-id' => ['required', 'numeric']
            ],
            Mutations::DeleteCartItem => [
                'remove-product-id' => ['required', 'numeric']
            ],
            Mutations::HideNotification => [
                'remove-notification-index' => ['required', 'numeric']
            ]
        };
    }
}
