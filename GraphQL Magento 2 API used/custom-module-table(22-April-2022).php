<?
//urlFetchType::-https://www.linkedin.com/pulse/how-create-graphql-schema-magento-2-custom-module-table-haseem /
//GitUrl ::- https://github.com/larsroettig-dev/module-graphqlstorepickup
?>

app/code/LarsRoettig/GraphQLStorePickup/registration.php
<?php

declare(strict_types=1);

Magento\Framework\Component\ComponentRegistrar::register(
    Magento\Framework\Component\ComponentRegistrar::MODULE,
    'LarsRoettig_GraphQLStorePickup',
    __DIR__
);
GraphQLStorePickup/etc/module.xml
<?xml version="1.0" ?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
	<module name="LarsRoettig_GraphQLStorePickup" setup_version="1.0.0">
		<sequence>
			<module name="Magento_GraphQl"/>
		</sequence>
	</module>
</config>
GraphQLStorePickup/etc/db_schema.xml
<?xml version="1.0"?>
<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">
    <table name="pickup_stores" resource="default" engine="innodb" comment="Pick Up Stores">
        <column xsi:type="int" name="entity_id" padding="10" unsigned="true" nullable="false" identity="true"
                comment="Entity ID"/>
        <column xsi:type="varchar" name="name" nullable="true" length="64"/>
        <column xsi:type="varchar" name="street" nullable="true" length="64"/>
        <column xsi:type="int" name="street_num" nullable="true"/>
        <column xsi:type="varchar" name="city" nullable="true" length="64"/>
        <column xsi:type="varchar" name="postcode" nullable="true" length="10"/>
        <column xsi:type="decimal" name="latitude"  default="0" scale="4" precision="20" />
        <column xsi:type="decimal" name="longitude"  default="0" scale="4" precision="20" />
        <constraint xsi:type="primary" referenceId="PRIMARY">
            <column name="entity_id"/>
        </constraint>
    </table>
</schema>
GraphQLStorePickup/Api/Data/StoreInterface.php
<?php

declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Api\Data;

/**
 * Represents a store and properties
 *
 * @api
 */
interface StoreInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const NAME = 'name';
    const STREET = 'street';
    const STREET_NUM = 'street_num';
    const CITY = 'city';
    const POSTCODE = 'postcode';
    const LATITUDE = 'latitude';
    const LONGITUDE = 'longitude';

    /**#@-*/

    public function getName(): ?string;

    public function setName(?string $name): void;

    public function getStreet(): ?string;

    public function setStreet(?string $street): void;

    public function getStreetNum(): ?int;

    public function setStreetNum(?int $streetNum): void;

    public function getCity(): ?string;

    public function setCity(?string $city): void;

    public function getPostCode(): ?int;

    public function setPostcode(?int $postCode): void;

    public function getLatitude(): ?float;

    public function setLatitude(?float $latitude): void;

    public function getLongitude(): ?float;

    public function setLongitude(?float $longitude): void;
}

GraphQLStorePickup/Model/Store.php
<?php

declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Model;

use LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterface;
use LarsRoettig\GraphQLStorePickup\Model\ResourceModel\Store as StoreResourceModel;
use Magento\Framework\Model\AbstractExtensibleModel;

class Store extends AbstractExtensibleModel implements StoreInterface
{

    protected function _construct()
    {
        $this->_init(StoreResourceModel::class);
    }

    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    public function setName(?string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    public function getStreet(): ?string
    {
        return $this->getData(self::STREET);
    }

    public function setStreet(?string $street): void
    {
        $this->setData(self::STREET, $street);
    }

    public function getStreetNum(): ?int
    {
        return $this->getData(self::STREET_NUM);
    }

    public function setStreetNum(?int $streetNum): void
    {
        $this->setData(self::STREET_NUM, $streetNum);
    }

    public function getCity(): ?string
    {
        return $this->getData(self::CITY);
    }

    public function setCity(?string $city): void
    {
        $this->setData(self::CITY, $city);
    }

    public function getPostCode(): ?int
    {
        return $this->getData(self::POSTCODE);
    }

    public function setPostcode(?int $postCode): void
    {
        $this->setData(self::POSTCODE, $postCode);
    }

    public function getLatitude(): ?float
    {
        return $this->getData(self::LATITUDE);
    }

    public function setLatitude(?float $latitude): void
    {
        $this->setData(self::LATITUDE, $latitude);
    }

    public function getLongitude(): ?float
    {
        return $this->getData(self::LONGITUDE);
    }

    public function setLongitude(?float $longitude): void
    {
        $this->setData(self::LONGITUDE, $longitude);
    }
}
GraphQLStorePickup/Model/ResourceModel/Store.php
<?php
declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\PredefinedId;

class Store extends AbstractDb
{
    /**
     * Provides possibility of saving entity with predefined/pre-generated id
     */
    use PredefinedId;

    /**#@+
     * Constants related to specific db layer
     */
    private const TABLE_NAME_STOCK = 'pickup_stores';
    /**#@-*/

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME_STOCK, 'entity_id');
    }
}
GraphQLStorePickup/Model/ResourceModel/StoreCollection.php
<?php
declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Model\ResourceModel;

use LarsRoettig\GraphQLStorePickup\Model\ResourceModel\Store as StoreResourceModel;
use LarsRoettig\GraphQLStorePickup\Model\Store as StoreModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class StoreCollection extends AbstractCollection
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(StoreModel::class, StoreResourceModel::class);
    }
}
GraphQLStorePickup/Api/StoreRepositoryInterface.php
<?php
declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Api;

use LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface StoreRepositoryInterface
{
    /**
     * Save the Store data.
     *
     * @param \Magento\InventoryApi\Api\Data\SourceInterface $source
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(StoreInterface $store): void;

    /**
     * Find Stores by given SearchCriteria
     * SearchCriteria is not required because load all stores is useful case
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface;
}
GraphQLStorePickup/Model/StoreRepository.php
<?php
declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Model;

use LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterface;
use LarsRoettig\GraphQLStorePickup\Api\StoreRepositoryInterface;
use LarsRoettig\GraphQLStorePickup\Model\ResourceModel\Store as StoreResourceModel;
use LarsRoettig\GraphQLStorePickup\Model\ResourceModel\StoreCollection;
use LarsRoettig\GraphQLStorePickup\Model\ResourceModel\StoreCollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;

class StoreRepository implements StoreRepositoryInterface
{
    /**
     * @var StoreCollectionFactory
     */
    private $storeCollectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SearchResultsInterfaceFactory
     */
    private $storeSearchResultsInterfaceFactory;
    /**
     * @var StoreResourceModel
     */
    private $storeResourceModel;

    public function __construct(
        StoreCollectionFactory $storeCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SearchResultsInterfaceFactory $storeSearchResultsInterfaceFactory,
        StoreResourceModel $storeResourceModel
    ) {
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeSearchResultsInterfaceFactory = $storeSearchResultsInterfaceFactory;
        $this->storeResourceModel = $storeResourceModel;
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): SearchResultsInterface
    {
        /** @var StoreCollection $storeCollection */
        $storeCollection = $this->storeCollectionFactory->create();
        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $storeCollection);
        }
        /** @var SearchResultsInterface $searchResult */
        $searchResult = $this->storeSearchResultsInterfaceFactory->create();
        $searchResult->setItems($storeCollection->getItems());
        $searchResult->setTotalCount($storeCollection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }

    /**
     * @inheritDoc
     */
    public function save(StoreInterface $store): void
    {
        try {
            $this->storeResourceModel->save($store);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save Source'), $e);
        }
    }
}

 GraphQLStorePickup/etc/di.xml

<?xml version="1.0" encoding="UTF-8"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <preference for="LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterface" type="LarsRoettig\GraphQLStorePickup\Model\Store"/>
    <preference for="LarsRoettig\GraphQLStorePickup\Api\StoreRepositoryInterface" type="\LarsRoettig\GraphQLStorePickup\Model\StoreRepository"/>
</config>

GraphQLStorePickup/Setup/Patch/Data/InitializePickUpStores.php
<?php

declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Setup\Patch\Data;

use LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterface;
use LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterfaceFactory;
use LarsRoettig\GraphQLStorePickup\Api\StoreRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class InitializePickUpStores implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var StoreInterfaceFactory
     */
    private $storeInterfaceFactory;
    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * EnableSegmentation constructor.
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        StoreInterfaceFactory $storeInterfaceFactory,
        StoreRepositoryInterface $storeRepository,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->storeInterfaceFactory = $storeInterfaceFactory;
        $this->storeRepository = $storeRepository;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     * @throws Exception
     */
    public function apply()
    {
        $this->moduleDataSetup->startSetup();
        $maxStore = 50;

        $citys = ['Rosenheim', 'Kolbermoor', 'München', 'Erfurt', 'Berlin'];

        for ($i = 1; $i <= $maxStore; $i++) {

            $storeData = [
                StoreInterface::NAME => 'Brick and Mortar ' . $i,
                StoreInterface::STREET => 'Test Street' . $i,
                StoreInterface::STREET_NUM => $i * random_int(1, 100),
                StoreInterface::CITY => $citys[random_int(0, 4)],
                StoreInterface::POSTCODE => $i * random_int(1000, 9999),
                StoreInterface::LATITUDE => random_int(4757549, 5041053) / 100000,
                StoreInterface::LONGITUDE => random_int(1157549, 1341053) / 100000,
            ];
            /** @var StoreInterface $store */
            $store = $this->storeInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray($store, $storeData, StoreInterface::class);
            $this->storeRepository->save($store);
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}
2. How to add Magento 2 GraphQL specific implementation

GraphQLStorePickup/etc/schema.graphqls
type Query {
  pickUpStores(
    filter: PickUpStoresFilterInput @doc(description: "")
    pageSize: Int = 5
      @doc(description: "How many items should show on the page")
    currentPage: Int = 1
      @doc(description: "Allows to ussing paging it start with 1")
  ): pickUpStoresOutput
    @resolver(
      class: "\\LarsRoettig\\GraphQLStorePickup\\Model\\Resolver\\PickUpStores"
    )
    @doc(description: "The Impelemention to resolve PickUp stores")
}

input PickUpStoresFilterInput {
  name: FilterTypeInput @doc(description: "")
  postcode: FilterTypeInput @doc(description: "")
  latitude: FilterTypeInput @doc(description: "")
  longitude: FilterTypeInput @doc(description: "")
  or: PickUpStoresFilterInput
}

type pickUpStoresOutput {
  total_count: Int @doc(description: "")
  items: [PickUpStore] @doc(description: "")
}

type PickUpStore {
  name: String @doc(description: "")
  street: String @doc(description: "")
  street_num: Int @doc(description: "")
  city: String @doc(description: "")
  postcode: String @doc(description: "")
  latitude: Float @doc(description: "")
  longitude: Float @doc(description: "")
}
GraphQLStorePickup/Model/Resolver/PickUpStores.php
<?php

declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Model\Resolver;

use LarsRoettig\GraphQLStorePickup\Api\StoreRepositoryInterface;
use LarsRoettig\GraphQLStorePickup\Model\Store\GetList;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class PickUpStores implements ResolverInterface
{

    /**
     * @var GetListInterface
     */
    private $storeRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * PickUpStoresList constructor.
     * @param GetList $storeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(StoreRepositoryInterface $storeRepository, SearchCriteriaBuilder $searchCriteriaBuilder)
    {
        $this->storeRepository = $storeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {

        $this->vaildateArgs($args);

        $searchCriteria = $this->searchCriteriaBuilder->build('pickup_stores', $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);
        $searchResult = $this->storeRepository->getList($searchCriteria);

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items' => $searchResult->getItems(),
        ];
    }

    /**
     * @param array $args
     * @throws GraphQlInputException
     */
    private function vaildateArgs(array $args): void
    {
        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
    }
}
GraphQLStorePickup/etc/di.xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">
    <preference for="LarsRoettig\GraphQLStorePickup\Api\Data\StoreInterface" type="LarsRoettig\GraphQLStorePickup\Model\Store"/>
    <preference for="LarsRoettig\GraphQLStorePickup\Api\StoreRepositoryInterface" type="\LarsRoettig\GraphQLStorePickup\Model\StoreRepository"/>
    <type name="Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesPool">
        <arguments>
            <argument name="attributesInstances" xsi:type="array">
                <item name="pickup_stores" xsi:type="object">
                    \LarsRoettig\GraphQLStorePickup\Model\Resolver\FilterArgument
                </item>
            </argument>
        </arguments>
    </type>
</config>

3.Code for Magento 2.3

GraphQLStorePickup/Model/Resolver/FilterArgument.php
<?php
declare(strict_types=1);

namespace LarsRoettig\GraphQLStorePickup\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

class FilterArgument implements FieldEntityAttributesInterface
{
    /** @var ConfigInterface */
    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function getEntityAttributes(): array
    {
        $fields = [];
        /** @var Field $field */
        foreach ($this->config->getConfigElement('PickUpStore')->getFields() as $field) {
            $fields[$field->getName()] = '';
        }

        return array_keys($fields);
    }
}
4.Code for Magento 2.4
<?php

declare(strict_types=1);

namespace LarsRoettig\StorePickupGraphQL\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\ConfigInterface;
use Magento\Framework\GraphQl\Query\Resolver\Argument\FieldEntityAttributesInterface;

class FilterArgument implements FieldEntityAttributesInterface
{
    /** @var ConfigInterface */
    private $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    public function getEntityAttributes(): array
    {
        $fields = [];
        /** @var Field $field */
        foreach ($this->config->getConfigElement('PickUpStore')->getFields() as $field) {
            $fields[$field->getName()] = [
                'type' => 'String',
                'fieldName' => $field->getName(),
            ];
        }
        return $fields;
    }
}

Simple GraphQL-Query without an filter:
{
  pickUpStores {
    total_count
    items {
      name
      street
      street_num
      postcode
    }
  }
}
** GraphQL-Query with a filter:**
{
  pickUpStores(
    filter: { name: { like: "Brick and Mortar 1%" } }
    pageSize: 2
    currentPage: 1
  ) {
    total_count
    items {
      name
      street
      postcode
    }
  }
}
Complex GraphQL-Query with a longitude filter:
{
  pickUpStores(
    filter: { longitude: { gt: "11.66" } }
    pageSize: 2
    currentPage: 1
  ) {
    total_count

    items {
      name
      street
      postcode
      latitude
      longitude
    }
  }
}

6.Installation:-
bin/magento module:enable LarsRoettig_GraphQLStorePickup
bin/magento setup:db-declaration:generate-whitelist --module-name=LarsRoettig_GraphQLStorePickup
bin/magento setup:upgrade
