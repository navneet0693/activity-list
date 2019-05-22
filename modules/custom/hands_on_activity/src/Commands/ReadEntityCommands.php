<?php

namespace Drupal\hands_on_activity\Commands;

use Drush\Commands\DrushCommands;
use Drupal\hands_on_activity\Controller\ReadEntityController;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 */
class ReadEntityCommands extends DrushCommands {
  /**
   * Echos back input with the argument provided.
   *
   * @param string $entity
   *   Argument provided to the drush command.
   *
   * @command hands_on_activity:read
   * @aliases hands_on_activity
   * @options arr An option that takes multiple values.
   * @options msg Whether or not an extra message should be displayed to the user.
   * @usage hands_on_activity:read Entity
   *   read 'Entity' and a message.
   */
  public function ReadEntity($entity, $options = ['entity' => FALSE]) {
    if ($entity == 'read') {
      $response = ReadEntityController::getData($entity);
      //echo "match found";
    }
  }

}
