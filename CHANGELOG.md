CHANGELOG for 0.x

This changelog references the relevant changes (bug and security fixes) done in beta version.

* 0.1.0 (2023-10-09)
 * feature #5 Add dynamic lighting to the map
 * feature #29 Add the ability to search for a user by their discord ID
 * feature #32 Switch completely to typescript

* 0.1.1 (2023-10-11)
 * bug #37 The volume saved for the user is not used by the music player
 * bug #38 World players are incorrectly initialized on map parameters
 * bug #39 Dynamic light is not initialized on player view
 * bug #40 Impossible to create a wall on the light layer as a GM
 * bug #42 Music PATCH requests don't call the right music player ID

* 0.1.2 (2023-10-14)
 * bug #55 Impossible to change map
 * bug #56 Unable to add assets

* 0.2.0 (2024-02-10)
 * feature #6 Add the possibility for users to change their personal information (password, etc...)
 * feature #46 Extending the character sheet with weapons/armor
 * bug #57 Changing a player's map is not taken into account locally
 * bug #63 Empty texture cache when changing map

* 0.3.0 (2024-04-13)
 * feature #15 Enable the addition of a new map from the map editor
 * feature #16 Allow new assets to be added from the map editor
 * feature #74 Create an account
 * feature #79 Upgrade to Symfony 7.0
 * feature #83 Secure Api Platform with a JWT token
 * feature #87 Add link to discord server