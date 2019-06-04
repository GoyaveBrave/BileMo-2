Feature:
  Display the customer list of a user

  @display_customers_not_logged_in
  Scenario: User not logged in
    Given I am an unauthenticated user
    When I send a "GET" request to "/api/customer/list"
    Then the response header "content-type" should be equal to "application/json"
    And the response code should be 403
    And the JSON node "error.message" should be equal to "Le token n'a pas été trouvé"

  @display_customers_logged_in
  Scenario: User logged in
    Given I am successfully logged in with username: "SFR", and password: "password"
    When I send a "GET" request to "/api/customer/list"
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
            "create": {
              "type": "string"
            }
          }
        },
        "data": {
          "type": "array",
          "one": {
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
    }
    """
