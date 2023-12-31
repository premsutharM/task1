<?php

namespace Drupal\redhen_connection\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Access\AccessResultNeutral;
use Drupal\Core\Entity\EntityInterface;
use Drupal\redhen_contact\Entity\Contact;
use Drupal\redhen_org\Entity\Org;

/**
 * Base class for Connection permission plugins.
 */
class ConnectionPermissionBase extends PluginBase implements ConnectionPermissionInterface {


  /**
   * Connection Service.
   *
   * @var \Drupal\redhen_connection\ConnectionService
   */
  public $redhenConnectionConnections;

  /**
   * {@inheritDoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->redhenConnectionConnections = \Drupal::service('redhen_connection.connections');
  }

  /**
   * {@inheritDoc}
   */
  public function getPermissionKey() {
    return $this->get('subject_entity_type') . ($this->get('subject_entity_bundle') ? ".{$this->get('subject_entity_bundle')}" : "");
  }

  /**
   * {@inheritDoc}
   */
  public function getInfluencers(EntityInterface $subject_entity) {}

  /**
   * {@inheritDoc}
   */
  public function hasRolePermissions(EntityInterface $subject_entity, $operation, Contact $contact) {}

  /**
   * {@inheritdoc}
   */
  public function hasCreatePermissions(Org $org, Contact $contact) {
    $access = $this->redhenConnectionConnections->checkConnectionPermission($contact, $org, 'create', $this->getPermissionKey());
    if ($access->isAllowed()) {
      return $access;
    }
    return new AccessResultNeutral();
  }

  /**
   * Get values from plugin definition.
   *
   * @param string $value
   *   The plugin definition key to check for.
   *
   * @return string
   *   The definition.
   */
  public function get($value) {
    return $this->pluginDefinition[$value];
  }

}
