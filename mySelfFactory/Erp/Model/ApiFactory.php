<?php
declare(strict_types=1);

namespace Beside\Erp\Model;

use Magento\Framework\ObjectManagerInterface;

class ApiFactory
{
    /**
     * @var array
     */
    private array $apiMap;

    /**
     * @var ObjectManagerInterface
     */
    private ObjectManagerInterface $_objectManager;

    /**
     * ApiFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param array $apiMap
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        array $apiMap
    ) {
        $this->_objectManager = $objectManager;
        $this->apiMap = $apiMap;
    }

    /**
     * Create class instance with specified parameters
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data = [])
    {
        return $this->_objectManager->create($this->apiMap[$data['apiType']]);
    }
}
