<?php


namespace SilverStripe\Headless\Admins;

use SilverStripe\Admin\ModelAdmin;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldImportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\Headless\GridField\PublishButton;
use SilverStripe\Headless\Model\PublishQueueItem;
use SilverStripe\Versioned\Versioned;

class AwaitingPublicationAdmin extends ModelAdmin
{
    /**
     * @var string
     */
    private static $menu_title = 'Awaiting Publication';

    /**
     * @var string
     */
    private static $url_segment = 'awaiting-publication';

    private static $menu_icon_class = 'font-icon-rocket';

    /**
     * @var array
     */
    private static $managed_models = [
        PublishQueueItem::class,
    ];


    public function getGridField(): GridField
    {
        $grid = parent::getGridField();
        $grid->getConfig()->removeComponentsByType(GridFieldImportButton::class);
        $grid->getConfig()->removeComponentsByType(GridFieldPrintButton::class);
        $grid->getConfig()->removeComponentsByType(GridFieldExportButton::class);

        return $grid;
    }

    public function getList()
    {
        $list = parent::getList();

        return $list->filter([
            'Stage' => Versioned::LIVE,
            'PublishEventID' => 0,
        ]);
    }

    protected function getGridFieldConfig(): GridFieldConfig
    {
        $config = parent::getGridFieldConfig();
        $config->addComponent(PublishButton::create());

        return $config;
    }

    public function getManagedModels()
    {
        $tabs = parent::getManagedModels();
        $tabs[PublishQueueItem::class]['title'] = _t(__CLASS__ . '.AWAITINGPUBLICATION', 'Awaiting publication');

        return $tabs;
    }

}
