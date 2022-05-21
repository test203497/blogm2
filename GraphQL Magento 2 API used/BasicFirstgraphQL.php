https://www.magentoassociation.org/commerce-co-op/full-article/how-to-create-your-first-custom-graphql-query-in-magento-2-1
------------------------------------------------
File: Example\ReviewGraphQl\etc\module.xml
-----------------------------------------------
<?xml version="1.0"?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">

    <module name="Example_ReviewGraphQl">

        <sequence>

            <module name="Magento_Review"/>

            <module name="Magento_GraphQl"/>

        </sequence>

    </module>

</config>

File: Example\ReviewGraphQl\registration.php
--------------------------------------------------
<?php

\Magento\Framework\Component\ComponentRegistrar::register(

    \Magento\Framework\Component\ComponentRegistrar::MODULE,

    'Example_ReviewGraphQl',

    __DIR__

);

File: Example/ReviewGraphQl/etc/schema.graphqls
------------------------------------------------
type Query {

    last_product_review(

    id: Int! @doc(description: "Specify the id of the product.")

    ): reviewData @resolver( class: "Example\\ReviewGraphQl\\Model\\Resolver\\Reviews") @doc(description: "Get list of reviews for the given product id.")

}

type reviewData {

    review_id: String

    created_at: String

    title: String

    detail: String

    nickname:String

}

File: Example\ReviewGraphQL\Model\Resolver\Reviews.php
-------------------------------------------------------
<?php

declare(strict_types=1);

namespace Example\ReviewGraphQl\Model\Resolver;


use Magento\Framework\GraphQl\Config\Element\Field;

use Magento\Framework\GraphQl\Query\ResolverInterface;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

use Magento\Review\Model\ResourceModel\Review\CollectionFactory as ReviewCollectionFactory;

use Magento\Review\Model\Review;

use Magento\Store\Model\StoreManagerInterface;


class Reviews implements ResolverInterface

{

    protected $storeManager;

    protected $reviewCollection;


    public function __construct(

        StoreManagerInterface $storeManager,

        ReviewCollectionFactory $reviewCollection

    ) {

        $this->storeManager = $storeManager;

        $this->reviewCollection = $reviewCollection;

    }


    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)

    {

        $currentStoreId = $this->storeManager->getStore()->getId();


        $collection = $this->reviewCollection->create()

            ->addStoreFilter($currentStoreId)

            ->addStatusFilter(Review::STATUS_APPROVED)

            ->addEntityFilter('product', $args['id'])

            ->setDateOrder()

            ->getFirstItem();


        return $collection->getData();

    }

}
------------------------------------------
This query does not require you to provide any customer token. 
Therefore, you can execute it as below. (Note: I have used 2030 
as a sample product id.) The response is shown in the right hand side.

**********************************


