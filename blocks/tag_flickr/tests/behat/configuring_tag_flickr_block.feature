@block @block_tag_flickr
Feature: Adding and configuring Flickr block
  In order to have the Flickr block used
  As a admin
  I need to add the Flickr block to the tags site page

  @javascript
  Scenario: Adding Flickr block to the tags site page
    Given I log in as "admin"
    And I turn editing mode on
    # TODO MDL-57120 site "Tags" link not accessible without navigation block.
    And I add the "Navigation" block if not present
    And I click on "Site pages" "list_item" in the "Navigation" "block"
    And I click on "Tags" "link" in the "Navigation" "block"
    And I add the "Flickr" block
    And I configure the "Flickr" block
    Then I should see "Flickr block title"
    And I set the field "Flickr block title" to "The Flickr block header"
    And I press "Save changes"
    And "block_tag_flickr" "block" should exist
    Then "The Flickr block header" "block" should exist
