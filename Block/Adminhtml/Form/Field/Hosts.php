<?php
/**
 * @copyright Copyright © Novicell ApS. All rights reserved.
 * @license   proprietary
 * @link      https://www.novicell.dk/
 */

namespace Novicell\Csp\Block\Adminhtml\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\View\Helper\SecureHtmlRenderer;
use Novicell\Csp\Block\Adminhtml\System\Config\Types;

class Hosts extends AbstractFieldArray
{
    protected array $renderer = [];
    private $types;

    public function __construct(
        Context $context,
        array $data = [],
        ?SecureHtmlRenderer $secureRenderer = null
    ) {
        parent::__construct($context, $data, $secureRenderer);
    }

    protected function getRenderer($class)
    {
        if (!isset($this->renderer[$class])) {
            $this->renderer[$class] = $this->getLayout()->createBlock(
                $class,
                '',
                [
                    'data' => [
                        'value' => $this->getValue(),
                        'is_render_to_js_template' => true,
                    ]
                ]
            );
        }
        return $this->renderer[$class];
    }

    /**
     * Prepare rendering the new field by adding all the needed columns
     */
    protected function _prepareToRender()
    {
        $this->addColumn('type', [
            'label' => __('Type'),
            'class' => 'required-entry',
            'renderer' => $this->getTypes()
        ]);
        $this->addColumn('host', ['label' => __('Host'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add new host');
    }

    /**
     * @param DataObject $row
     * @return void
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $options = [];
        $type = $row->getType();
        if ($type) {
            $options['option_' . $this->getTypes()->calcOptionHash($type)]
                = 'selected="selected"';
        }

        $row->setData('option_extra_attrs', $options);
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getTypes()
    {
        if (!$this->types) {
            $this->types = $this->getLayout()->createBlock(
                Types::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
        }
        return $this->types;
    }
}
