Feature: Tapster

  Scenario: Test
    And I save a screenshot in "screenster1.png"
    Given I tap on "ShowAlertButton"
    And I wait 2 seconds
    Then I save a screenshot in "screenster2.png"
    Given I tap on "OK"
    And I wait 2 seconds 

