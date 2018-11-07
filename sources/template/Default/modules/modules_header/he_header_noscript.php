<?php
/*
 * @copyright 2008 - https://www.clicshopping.org
 * @Brand : ClicShopping(Tm) at Inpi all right Reserved
 * @license GPL 2 & MIT

*/

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\CLICSHOPPING;

  class he_header_noscript {
    public $code;
    public $group;
    public $title;
    public $description;
    public $sort_order;
    public $enabled = false;

    public function __construct() {
      $this->code = get_class($this);
      $this->group = basename(__DIR__);
      $this->title = CLICSHOPPING::getDef('module_header_noscript_title');
      $this->description = CLICSHOPPING::getDef('module_header_noscript_description');

      if ( defined('MODULE_HEADER_NOSCRIPT_STATUS') ) {
        $this->sort_order = MODULE_HEADER_NOSCRIPT_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_NOSCRIPT_STATUS == 'True');
      }
    }

    public function execute() {
      $CLICSHOPPING_Template = Registry::get('Template');

      $CLICSHOPPING_Template->addBlock('<style>.no-script { border: 1px solid #ddd; border-width: 0 0 1px; background: #ffff90; font: 14px verdana; line-height: 1.25; text-align: center; color: #2f2f2f; } .no-script .no-script-inner { width: 950px; margin: 0 auto; padding: 5px; } .no-script p { margin: 0; }</style>', $this->group);
      $CLICSHOPPING_Template->addBlock('<noscript><div class="no-script"><div class="no-script-inner">' . HTML::outputProtected(MODULE_HEADER_NOSCRIPT_TEXT) . '</div></div></noscript>', $this->group);
    }

    public function isEnabled() {
      return $this->enabled;
    }

    public function check() {
      return defined('MODULE_HEADER_NOSCRIPT_STATUS');
    }

    public function install() {
      $CLICSHOPPING_Db = Registry::get('Db');


      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Souhaitez vous activer ce module ?',
          'configuration_key' => 'MODULE_HEADER_NOSCRIPT_STATUS',
          'configuration_value' => 'True',
          'configuration_description' => 'Souhaitez vous activer ce module ?',
          'configuration_group_id' => '6',
          'sort_order' => '1',
          'set_function' => 'clic_cfg_set_boolean_value(array(\'True\', \'False\'))',
          'date_added' => 'now()'
        ]
      );

      $CLICSHOPPING_Db->save('configuration', [
          'configuration_title' => 'Ordre de tri d\'affichage',
          'configuration_key' => 'MODULE_HEADER_NOSCRIPT_SORT_ORDER',
          'configuration_value' => '145',
          'configuration_description' => 'Ordre de tri pour l\'affichage (Le plus petit nombre est montré en premier)',
          'configuration_group_id' => '6',
          'sort_order' => '0',
          'set_function' => '',
          'date_added' => 'now()'
        ]
      );

      return $CLICSHOPPING_Db->save('configuration', ['configuration_value' => '1'],
                                                ['configuration_key' => 'WEBSITE_MODULE_INSTALLED']
                              );
    }

    public function remove() {
      return Registry::get('Db')->exec('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")');
    }

    public function keys() {
      return array('MODULE_HEADER_NOSCRIPT_STATUS',
                   'MODULE_HEADER_NOSCRIPT_SORT_ORDER'
                  );
    }
  }
