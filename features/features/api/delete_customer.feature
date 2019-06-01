Feature:
  Delete a customer own by a user

  @delete_customer_not_logged_in
  Scenario: User not logged in
    Given I am an unauthenticated user
    When I send a "DELETE" request to "/api/customer/delete/10"
    Then the response header "Content-Type" should be equal to "application/json"
    And the response code should be 403
    And the JSON node "error.message" should be equal to "Le token n'a pas été trouvé"

  @delete_customer_not_owned
  Scenario: Delete customer who is not owned by the user
    Given I am successfully logged in with username: "Orange", and password: "password"
    When I send a "DELETE" request to "/api/customer/delete/10"
    Then the response header "Content-Type" should be equal to "application/json"
    And the response code should be 403
    And the JSON node "error.message" should be equal to "L'accès a été refusé."

  @delete_customer_success
  Scenario: Delete customer who is owned by the user
    Given I am successfully logged in with username: "SFR", and password: "password"
    When I send a "DELETE" request to "/api/customer/delete/10"
    Then the response header "Content-Type" should be equal to "application/json"
    And the response code should be 200
    And the JSON node "success.message" should be equal to "l'utilisateur a été supprimé."