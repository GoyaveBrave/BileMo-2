<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\Json\Json;
use Behatch\Json\JsonInspector;
use Behatch\Json\JsonSchema;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use PHPUnit\Framework\Assert as Assertions;

class RestApiContext implements Context
{
    /**
     * @var Request
     */
    protected $request;

    private $headers = [];

    /**
     * @var ClientInterface
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
     * @param \Behatch\HttpCall\Request $request
     */
    public function __construct(\Behatch\HttpCall\Request $request)
    {
        $this->client = new Client(['base_uri' => 'http://127.0.0.1:8000']);
        $this->request = $request;
    }

    /**
     * Adds JWT Token to Authentication header for next request.
     *
     * @param string $username
     * @param string $password
     *
     * @Given /^I am successfully logged in with username: "([^"]*)", and password: "([^"]*)"$/
     */
    public function iAmSuccessfullyLoggedInWithUsernameAndPassword($username, $password)
    {
        $response = $this->client->post('login_check', [
            'json' => [
                'username' => $username,
                'password' => $password,
            ],
        ]);

        Assertions::assertEquals(200, $response->getStatusCode());

        $responseBody = json_decode($response->getBody(), true);

        $this->addHeader('Authorization', 'Bearer '.$responseBody['token']);
    }

    /**
     * @Given I am an unauthenticated user
     *
     * @throws Exception
     */
    public function iAmAnUnAuthenticatedUser()
    {
        $response = $this->client->get('/api/doc');
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
     * @throws GuzzleException
     * @When /^(?:I )?send a "([A-Z]+)" request to "([^"]+)"$/
     */
    public function iSendARequest($method, $url)
    {
        $url = $this->prepareUrl($url);
        $this->request = new Request($method, $url, (!empty($this->headers)) ? $this->headers : []);

        $this->sendRequest();
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
     * @throws GuzzleException
     */
    public function iSendARequestWithBody($method, $url, PyStringNode $string)
    {
        $url = $this->prepareUrl($url);
        $string = $this->replacePlaceHolder(trim($string));

        $this->request = new Request($method, $url, (!empty($this->headers)) ? $this->headers : [], $string);

        $this->sendRequest();
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

    /**
     * @throws GuzzleException
     */
    private function sendRequest()
    {
        try {
            $this->response = $this->getClient()->send($this->request);
        } catch (GuzzleException $e) {
            $this->response = $e->getResponse();

            if (null === $this->response) {
                throw $e;
            }
        }
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        if (null === $this->client) {
            throw new RuntimeException('Client has not been set in WebApiContext');
        }

        return $this->client;
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
     * @throws Exception
     */
    public function theJsonNodeShouldBeEqualTo($node, $text)
    {
        $json = new Json($this->response->getBody()->getContents());

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
     */
    public function theResponseHeaderShouldBeEqualTo($header, $value)
    {
        $header = $this->response->getHeaders()[$header];
        Assertions::assertContains($value, $header);
    }

    /**
     * @Then the JSON should be valid according to this schema:
     *
     * @param PyStringNode $schema
     */
    public function theJsonShouldBeValidAccordingToThisSchema(PyStringNode $schema)
    {
        $inspector = new JsonInspector('javascript');

        $json = new Json($this->response->getBody()->getContents());

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
     */
    public function printResponse()
    {
        $request = $this->request;
        $response = $this->response;

        echo sprintf(
            "%s %s => %d:\n%s",
            $request->getMethod(),
            $request->getUri(),
            $response->getStatusCode(),
            $response->getBody()
        );
    }
}
