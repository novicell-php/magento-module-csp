<?php
/**
 * @copyright Copyright © Novicell ApS. All rights reserved.
 * @license   proprietary
 * @link      https://www.novicell.dk/
 */

declare(strict_types=1);

namespace Novicell\Csp\Model\Collector;

use Magento\Csp\Api\PolicyCollectorInterface;
use Magento\Framework\Config\DataInterface as ConfigReader;
use Magento\Csp\Model\Policy\FetchPolicy;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
class CspWhitelistXmlCollector implements PolicyCollectorInterface
{
    const XML_PATH_CSP = 'novicell_csp/general/enable';
    const HOSTS_XML_PATH_CSP = 'novicell_csp/csp_whitelists/hosts';
    private ConfigReader $configReader;
    private ScopeConfigInterface $scopeConfig;
    private Json $json;
    private LoggerInterface $logger;

    public function __construct(
        ConfigReader $configReader,
        ScopeConfigInterface $scopeConfig,
        Json $json,
        LoggerInterface $logger
    ) {
        $this->configReader = $configReader;
        $this->scopeConfig = $scopeConfig;
        $this->json = $json;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function collect(array $defaultPolicies = []): array
    {
        $hosts = [];
        try {
            $systemHosts = $this->json->unserialize($this->scopeConfig->getValue(self::HOSTS_XML_PATH_CSP));
            foreach ($systemHosts as $host) {
                if (!isset($hosts[$host['type']])) {
                    $hosts[$host['type']] = [];
                }
                $hosts[$host['type']][\md5($host['host'])] = $host['host'];
            }
        } catch (\Exception $exception) {
            $this->logger->error('Error while parsing hosts from system configuration', ['exception' => $exception]);
        }

        $policies = $defaultPolicies;
        $config = $this->configReader->get();
        foreach ($config as $policyId => $values) {
            $hostsValue = $values['hosts'];
            if (isset($hosts[$policyId]) && (bool) $this->scopeConfig->getValue(self::XML_PATH_CSP)) {
                $hostsValue = \array_unique(\array_merge($hosts[$policyId], $hostsValue));
            }

            $policy = new FetchPolicy(
                $policyId,
                false,
                $hostsValue,
                [],
                false,
                false,
                false,
                [],
                $values['hashes'],
                false,
                false
            );

            $policies[] = $policy;
        }

        return $policies;
    }
}
