Feature:
  Display a customer own by a user

  @show_customer_not_logged_in
  Scenario: User not logged in
    Given I am an unauthenticated user
    When I send a "GET" request to "/api/customer/10"
    Then the response header "content-type" should be equal to "application/json"
    And the response code should be 403
    And the JSON node "error.message" should be equal to "Le token n'a pas été trouvé"

  @show_customer_wrong_user
  Scenario: Customer is not owned by the user
    Given I am successfully logged in with username: "Orange", and password: "password"
    When I send a "GET" request to "/api/customer/10"
    Then the response header "content-type" should be equal to "application/json"
    And the response code should be 403
    And the JSON node "error.message" should be equal to "L'accès a été refusé."

  @show_customer_not_found
  Scenario: Customer not found
    Given I am successfully logged in with username: "SFR", and password: "password"
    When I send a "GET" request to "/api/customer/200"
    Then the response header "content-type" should be equal to "application/json"
    And the response code should be 404
    And the JSON node "error.message" should be equal to "l'utilisateur n'existe pas."

  @show_customer_found
  Scenario: User logged in and customer found
    Given I am successfully logged in with username: "SFR", and password: "password"
    When I send a "GET" request to "/api/customer/10"
    Then the response header "content-type" should be equal to "application/json"
    And the response code should be 200
    And the JSON should be valid according to this schema:
    """
    {
      "type": "object",
      "properties": {
        "page": {
          "type": "string"
        },
        "links": {
          "type": "object",
          "properties": {
            "self": {
              "type": "string"
            },
            "list": {
              "type": "string"
            },
            "create": {
              "type": "string"
            },
            "delete": {
              "type": "string"
            }
          }
        },
        "data": {
          "type": "object",
          "properties": {
            "type": {
              "type": "string"
            },
            "id": {
              "type": "integer"
            },
            "attributes": {
              "type": "object",
              "properties": {
                "first_name": {
                  "type": "string"
                },
                "last_name": {
                  "type": "string"
                },
                "email": {
                  "type": "string"
                },
                "created_at": {
                  "type": "string"
                },
                "updated_at": {
                  "type": "string"
                }
              }
            }
          }
        }
      }
    }
    """