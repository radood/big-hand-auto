Feature: live big hand automation smoke pack

  Background: Login to Dashboard Application
    Given Environment is set to client 'QA'
    And User launch Dashboard Application

  @liveSmokePack @contactUsPage
  Scenario Outline: User uses the contact us form only filling out first name
    When User clicks on contact us button
    And User enter first name as <firstName> on the Contact us Page
    And User click submit button on the Contact us Page
    Then User should see an error message on the Contact us Page

    Examples:
      | firstName    |
      | "Radu"       |