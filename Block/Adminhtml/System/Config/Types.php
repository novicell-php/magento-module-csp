<?php
/**
 * @copyright Copyright © Novicell ApS. All rights reserved.
 * @license   proprietary
 * @link      https://www.novicell.dk/
 */

namespace Novicell\Csp\Block\Adminhtml\System\Config;

use Magento\Framework\View\Element\Html\Select;

class Types extends Select
{
    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->getOptions()) {
            $options = [];

            foreach (\Magento\Csp\Model\Policy\FetchPolicy::POLICIES as $policy) {
                $options[] = [
                    'label' => $policy,
                    'value' => $policy
                ];
            }

            $this->setOptions($options);
        }
        return parent::_toHtml();
    }

    /**
     * Sets name for input element
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}
