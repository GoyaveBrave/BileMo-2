<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\Json\Json;
use Behatch\Json\JsonInspector;
use Behatch\Json\JsonSchema;
use PHPUnit\Framework\Assert as Assertions;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RestApiContext implements Context
{
    private $headers = [];

    private $token;

    /**
     * @var HttpClient
     */
    protected $client;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var array
     */
    private $placeHolders = [];

    /**
     * RestApiContext constructor.
     *
     */
    public function __construct()
    {
        $this->client = HttpClient::create(['base_uri' => $_ENV['BASE_URI']]);
    }

    /**
     * Adds JWT Token to Authentication header for next request.
     *
     * @param string $username
     * @param string $password
     *
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @Given /^I am successfully logged in with username: "([^"]*)", and password: "([^"]*)"$/
     */
    public function iAmSuccessfullyLoggedInWithUsernameAndPassword($username, $password)
    {
        $response = $this->client->request('POST', '/login_check', [
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        Assertions::assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getContent(), true);

        $this->addHeader('Authorization', 'Bearer '.$responseBody['token']);
        $this->addToken($responseBody['token']);
    }

    /**
     * @Given I am an unauthenticated user
     *
     * @throws Exception
     * @throws TransportExceptionInterface
     */
    public function iAmAnUnAuthenticatedUser()
    {
        $response = $this->client->request('GET', '/api/doc');
        $responseCode = $response->getStatusCode();

        if (200 != $responseCode) {
            throw new Exception('Not able to access !');
        }

        return true;
    }

    /**
     * Sends HTTP request to specific relative URL.
     *
     * @param string $method request method
     * @param string $url    relative url
     *
     * @throws Exception
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
        $url = $this->prepareUrl($url);
        try {
            $this->response = $this->client->request($method, $url, (!empty($this->token)) ? ['auth_bearer' => $this->token] : []);
        } catch (TransportExceptionInterface $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Sends HTTP request to specific URL with raw body from PyString.
     *
     * @param string       $method request method
     * @param string       $url    relative url
     * @param PyStringNode $string request body
     *
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)" with body:$/
     *
     * @throws TransportExceptionInterface
     */
    public function iSendARequestWithBody($method, $url, PyStringNode $string)
    {
        $url = $this->prepareUrl($url);
        $string = $this->replacePlaceHolder(trim($string));

        $this->response = $this->client->request($method, $url, [
            'auth_bearer' => $this->token,
            'body' => $string,
        ]);
    }

    protected function addHeader($name, $value)
    {
        if (isset($this->headers[$name])) {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = [$this->headers[$name]];
            }

            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }
    }

    protected function addToken($value)
    {
        $this->token = $value;
    }

    private function prepareUrl($url)
    {
        return ltrim($this->replacePlaceHolder($url), '/');
    }

    /**
     * Replaces placeholders in provided text.
     *
     * @param string $string
     *
     * @return string
     */
    protected function replacePlaceHolder($string)
    {
        foreach ($this->placeHolders as $key => $val) {
            $string = str_replace($key, $val, $string);
        }

        return $string;
    }

    /**
     * Checks that response has specific status code.
     *
     * @param string $code status code
     *
     * @Then the response code should be :arg1
     *
     * @throws TransportExceptionInterface
     */
    public function theResponseCodeShouldBe($code)
    {
        $expected = intval($code);
        $actual = intval($this->response->getStatusCode());

        Assertions::assertSame($expected, $actual);
    }

    /**
     * Checks, that given JSON node is equal to given value.
     *
     * @Then the JSON node :node should be equal to :text
     *
     * @param $node
     * @param $text
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function theJsonNodeShouldBeEqualTo($node, $text)
    {
        $json = new Json($this->response->getContent(false));

        $inspector = new JsonInspector('javascript');

        $actual = $inspector->evaluate($json, $node);

        if ($actual != $text) {
            throw new Exception(
                sprintf("The node value is '%s'", json_encode($actual))
            );
        }
    }

    /**
     * @Then the response header :header should be equal to :value
     *
     * @param $header
     * @param $value
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function theResponseHeaderShouldBeEqualTo($header, $value)
    {
        $header = $this->response->getHeaders(false)[$header];
        Assertions::assertContains($value, $header);
    }

    /**
     * @Then the JSON should be valid according to this schema:
     *
     * @param PyStringNode $schema
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function theJsonShouldBeValidAccordingToThisSchema(PyStringNode $schema)
    {
        $inspector = new JsonInspector('javascript');

        $json = new Json($this->response->getContent(false));

        $inspector->validate(
            $json,
            new JsonSchema($schema)
        );
    }

    /**
     * Returns headers, that will be used to send requests.
     *
     * @return array
     */
    protected function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Prints last response body.
     *
     * @Then print response
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function printResponse()
    {
        $response = $this->response;

        echo sprintf(
            "%s %s => %d:\n%s",
            $response->getInfo('http_method'),
            $response->getInfo('url'),
            $response->getStatusCode(),
            $response->getContent(false)
        );
    }
}
