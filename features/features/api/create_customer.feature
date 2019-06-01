Feature:
  Create a customer own by a user

  @create_customer_not_logged_in
  Scenario: User not logged in
    Given I am an unauthenticated user
    When I send a "GET" request to "/api/customer/creation"
    Then the response header "Content-Type" should be equal to "application/json"
    And the response code should be 403
    And the JSON node "error.message" should be equal to "Le token n'a pas été trouvé"

  @create_customer_logged_in
  Scenario: User is logged in and post valid body
    Given I am successfully logged in with username: "SFR", and password: "password"
    When I send a "POST" request to "/api/customer/creation" with body:
    """
    {
      "firstname": "Romain",
      "lastname": "Ollier",
      "email": "romain.ollier@email.com"
    }
    """
    Then the response code should be 201
    And the response header "Content-Type" should be equal to "application/json"
    And the JSON node "success.message" should be equal to "l'utilisateur a été ajouté."

  @create_customer_invalid_body
  Scenario: User is logged in and post invalid body
    Given I am successfully logged in with username: "SFR", and password: "password"
    When I send a "POST" request to "/api/customer/creation" with body:
    """
    {
      "firstname": 123,
      "lastname": "Ollier",
      "email": "romain@email.com"
    }
    """
    Then the response code should be 400
    And the response header "Content-Type" should be equal to "application/json"
    And print response

