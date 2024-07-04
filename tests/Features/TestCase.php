<?php declare(strict_types=1);

namespace Tests\Features;

use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public $baseUrl = 'http://localhost';

    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        if (isset($parameters['hx-request'])) {
            $server = array_replace($server, $this->transformHeadersToServerVars(['hx-request' => $parameters['hx-request']]));
            unset($parameters['hx-request']);
        }

        if (isset($parameters['hx-trigger-name'])) {
            $server = array_replace($server, $this->transformHeadersToServerVars(['hx-trigger-name' => $parameters['hx-trigger-name']]));
            unset($parameters['hx-trigger-name']);
        }

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    protected function submitHxFormById(string $id): static
    {
        $form       = $this->crawler()->filter($id)->form(null, 'post');
        $parameters = array_replace(
            ['hx-request' => 'true', 'hx-trigger-name' => $form->getName()],
            $this->extractParametersFromForm($form)
        );
        return $this->makeRequest($form->getMethod(), $form->getUri(), $parameters);
    }
}
