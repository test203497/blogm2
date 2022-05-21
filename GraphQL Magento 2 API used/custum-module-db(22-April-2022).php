<?//urlType::- https://www.linkedin.com/pulse/how-create-graphql-schema-magento-2-custom-module-table-haseem ?>

module.xml::-
------------
<?xml version="1.0"?>
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="../../../../../lib/internal/Magento/Framework/Module/etc/module.xsd">
    <module name="Ayakil_Faq" setup_version="2.0.0">
        <sequence>
            <module name="Magento_Backend"/>
            <module name="Magento_GraphQl"/>
        </sequence>
	</module>
</config>
app/etc/schema.graphqls

type Query {
    faqs : [Faqs] @resolver( class: "Ayakil\\Faq\\Model\\Resolver\\Faq") @doc(description: "Get list of active answered FAQS")
}

type Faqs {
    question : String  @doc(description: "Question")
    answer : String  @doc(description: "Answer")
    faq_image : String  @doc(description: "Image if available")
}

app/code/Ayakil/Faq/Model/Resolver/Faq.php

<?php
namespace Ayakil\Faq\Model\Resolver;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class Faq implements ResolverInterface
{
    private $faqDataProvider;
    /**
     * @param DataProvider\Faq $faqRepository
     */
    public function __construct(
        \Ayakil\Faq\Model\Resolver\DataProvider\Faq $faqDataProvider
    ) {
        $this->faqDataProvider = $faqDataProvider;
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $faqData = $this->faqDataProvider->getFaq();
        return $faqData;
    }
}

app/code/Ayakil/Faq/Model/Resolver/DataProvider/Faq.php

<?php
namespace Ayakil\Faq\Model\Resolver\DataProvider;

class Faq
{
    protected $_faqFactory;

    public function __construct(
        \Ayakil\Faq\Model\FaqFactory $faqFactory
        )
    {
        $this->_faqFactory  = $faqFactory;
    }
    /**
     * @params int $id
     * this function return all the word of the day by id
     **/
    public function getFaq( )
    {
        try {
            $collection = $this->_faqFactory->create()->getCollection();
            $collection->addFieldToFilter('faq_active',1);
            $collection->setOrder('short_order', 'ASC');
            $faqData = $collection->getData();

        } catch (NoSuchEntityException $e) {
            throw new GraphQlNoSuchEntityException(__($e->getMessage()), $e);
        }
        return $faqData;
    }
}
