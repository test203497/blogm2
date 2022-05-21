https://www.sparsh-technologies.com/blog/how-to-develop-crud-module-in-magento-2 {Custom Frontend Logic..}
2.app/code/Magelearn/Customform/registration.php

<?php
use Magento\Framework\Component\ComponentRegistrar;
ComponentRegistrar::register(ComponentRegistrar::MODULE, 'Magelearn_Customform', __DIR__);

3.app/code/Magelearn/Customform/composer.json

{

    "name": "magelearn/module-customform",

    "description": "Create Custom Form and Data Management from Backend",

    "type": "magento2-module",

    "license": "proprietary",

    "authors": [

        {

            "name": "Mage2Gen",

            "email": "info@mage2gen.com"

        },

        {

            "name": "techysagar",

            "email": "techysagar@gmail.com"

        }

    ],

    "minimum-stability": "dev",

    "require": {},

    "autoload": {

        "files": [

            "registration.php"

        ],

        "psr-4": {

            "Magelearn\\Customform\\": ""

        }

    }

}

4.app/code/Magelearn/Customform/etc/module.xml



<?xml version="1.0" ?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">

    <module name="Magelearn_Customform" setup_version="1.0.0">

        <sequence>

           <module name="Magento_Backend"/>

           <module name="Magento_Ui"/>

        </sequence>

    </module>

</config>



Note:- Magelearn_Customform version save database on Setup_Module table and enable app/etc/config.php file and <sequence></sequence>

overide same module.



5.app/code/Magelearn/Customform/etc/db_schema.xml



 <?xml version="1.0" ?>

<schema xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Setup/Declaration/Schema/etc/schema.xsd">

    <table name="magelearn_customform" resource="default" engine="innodb" comment="Magelearn Customform Table">

        <column xsi:type="smallint" name="id" padding="6" unsigned="true" nullable="false" identity="true" comment="ID"/>

        <constraint xsi:type="primary" referenceId="PRIMARY">

            <column name="id"/>

        </constraint>

        <column name="first_name" nullable="false" xsi:type="text" comment="First Name"/>

        <column name="last_name" nullable="false" xsi:type="text" comment="Last Name"/>

        <column name="email" nullable="false" xsi:type="text" comment="Email"/>

        <column name="phone" nullable="false" xsi:type="int" padding="10" default="0" identity="false" comment="Phone"/>

        <column name="message" nullable="true" xsi:type="text" comment="Message"/>

        <column name="status" padding="11" unsigned="false" nullable="false" xsi:type="int" default="1" identity="false" comment="Customform Status"/>

        <column name="image" nullable="true" xsi:type="text" comment="Image"/>

        <column name="created_at" nullable="false" xsi:type="datetime" comment="Created Date" default="CURRENT_TIMESTAMP"/>

        <index referenceId="MAGELEARN_CUSTOMFORM_FIRST_NAME" indexType="fulltext">

            <column name="first_name"/>

        </index>

        <index referenceId="MAGELEARN_CUSTOMFORM_LAST_NAME" indexType="fulltext">

            <column name="last_name"/>

        </index>

        <index referenceId="MAGELEARN_CUSTOMFORM_EMAIL" indexType="fulltext">

            <column name="email"/>

        </index>

        <index referenceId="MAGELEARN_CUSTOMFORM_MESSAGE" indexType="fulltext">

            <column name="message"/>

        </index>

        <index referenceId="MAGELEARN_CUSTOMFORM_IMAGE" indexType="fulltext">

            <column name="image"/>

        </index>

    </table>

</schema>



Note :- db_schema.xml is second method create table and table field on database.It is introduced magento 2.3.3 version.

There are some command after excute table : -

php bin/magento setup:db-declaration:generate-whitelist --module-name=Magelearn_Customform

php bin/magento setup:upgrade

php bin/magento cache:flush

After automatic create file on db_schema_whitelist.json  etc folder.



6.app/code/Magelearn/Customform/etc/di.xml



<?xml version="1.0" ?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:ObjectManager/etc/config.xsd">

    <preference for="Magelearn\Customform\Api\CustomformRepositoryInterface" type="Magelearn\Customform\Model\CustomformRepository"/>

    <preference for="Magelearn\Customform\Api\Data\CustomformInterface" type="Magelearn\Customform\Model\Data\Customform"/>

    <preference for="Magelearn\Customform\Api\Data\CustomformSearchResultsInterface" type="Magento\Framework\Api\SearchResults"/>

    <virtualType name="Magelearn\Customform\Model\ResourceModel\Customform\Grid\Collection" type="Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult">

        <arguments>

            <argument name="mainTable" xsi:type="string">magelearn_customform</argument>

            <argument name="resourceModel" xsi:type="string">Magelearn\Customform\Model\ResourceModel\Customform\Collection</argument>

        </arguments>

    </virtualType>

    <type name="Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory">

        <arguments>

            <argument name="collections" xsi:type="array">

                <item name="magelearn_customform_listing_data_source" xsi:type="string">Magelearn\Customform\Model\ResourceModel\Customform\Grid\Collection</item>

            </argument>

        </arguments>

    </type>

    <virtualType name="Magelearn\Customform\CustomformImageUpload" type="Magelearn\Customform\Model\ImageUploader">

        <arguments>

                <argument name="baseTmpPath" xsi:type="string">magelearn/customform/tmp</argument>

                <argument name="basePath" xsi:type="string">magelearn/customform</argument>

                <argument name="allowedExtensions" xsi:type="array">

                    <item name="jpg" xsi:type="string">jpg</item>

                    <item name="jpeg" xsi:type="string">jpeg</item>

                    <item name="gif" xsi:type="string">gif</item>

                    <item name="png" xsi:type="string">png</item>

                </argument>

        </arguments>

    </virtualType>

    <type name="Magelearn\Customform\Controller\Adminhtml\Customform\FileUploader\Save">

        <arguments>

                <argument name="imageUploader" xsi:type="object">Magelearn\Customform\CustomformImageUpload</argument>

        </arguments>

    </type>

</config>

7.app/code/Magelearn/Customform/view/frontend/layout/customform_index_index.xml



<?xml version="1.0"?>

<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    <head>

        <css src="Magelearn_Customform::css/custom_customform.css" />

        <script src="Magelearn_Customform::js/custom_customform.js"/>

    </head>

    <body>

        <referenceContainer name="content">

            <block class="Magelearn\Customform\Block\Customform" name="customform.form" template="customform.phtml">

            </block>

        </referenceContainer>

    </body>

</page>



Note:- We have layout design process i.e customform_index_index as frontName/Controller/action.



8.app/code/Magelearn/Customform/view/frontend/templates/customform.phtml



<form class="form customform"

      action="<?php echo $block->getBaseUrl().'customform/index/save'; ?>"

      id="magelearn-customform-form"

      method="post"

      data-hasrequired="<?= $block->escapeHtmlAttr(__('* Required Fields')) ?>"

      enctype="multipart/form-data"

      data-mage-init='{"validation":{}}'>

    <fieldset class="fieldset">

        <legend class="legend"><span><?= $block->escapeHtml(__('Customer inquiry Form ')) ?><a href="<?php echo $block->getBaseUrl().'customform/index/list'; ?>">(Visit List Page)</a></span></legend>



        <div class="field note no-label"><?= $block->escapeHtml(__('Fillup your inquiry form.')) ?></div>

        <div class="field first_name required">

            <label class="label" for="first_name"><span><?= $block->escapeHtml(__('First name:')) ?></span></label>

            <div class="control">

                <input name="first_name"

                       id="first_name"

                       title="<?= $block->escapeHtmlAttr(__('First Name')) ?>"

                       class="input-text"

                       type="text"

                       data-validate="{required:true}"/>

            </div>

        </div>

        <div class="field last_name required">

            <label class="label" for="last_name"><span><?= $block->escapeHtml(__('Last name:')) ?></span></label>

            <div class="control">

                <input name="last_name"

                       id="last_name"

                       title="<?= $block->escapeHtmlAttr(__('Last Name')) ?>"

                       class="input-text"

                       type="text"

                       data-validate="{required:true}"/>

            </div>

        </div>

        <div class="field email required">

            <label class="label" for="email"><span><?= $block->escapeHtml(__('Email')) ?></span></label>

            <div class="control">

                <input name="email"

                       id="email"

                       title="<?= $block->escapeHtmlAttr(__('Email')) ?>"

                       class="input-text"

                       type="email"

                       data-validate="{required:true, 'validate-email':true}"/>

            </div>

        </div>

        <div class="field phone required">

            <label class="label" for="phone"><span><?= $block->escapeHtml(__('Phone:')) ?></span></label>

            <div class="control">

                <input name="phone" id="phone" title="<?= $block->escapeHtmlAttr(__('Phone')) ?>" class="input-text" type="text" data-validate="{required:true, 'validate-number':true}">

            </div>

        </div>

        <div class="field message required">

            <label class="label" for="message"><span><?= $block->escapeHtml(__('Message:')) ?></span></label>

            <div class="control">

                <textarea name="message" id="message" title="<?= $block->escapeHtml(__('Message')) ?>" class="input-text" cols="5" rows="3" data-validate="{required:true}"></textarea>

            </div>

        </div>

        <div class="field image">

            <label class="label" for="image"><span><?= $block->escapeHtml(__('Image:')) ?></span></label>

            <div class="control">

                <input name="image"

                       id="image"

                       accept="image/*"

                       accept=".jpg,.jpeg,.png,.gif"

                       title="<?= $block->escapeHtmlAttr(__('Image')) ?>"

                       class="input-text"

                       type="file" />

            </div>

        </div>

    </fieldset>

    <div class="actions-toolbar">

        <div class="primary">

            <button type="submit" title="<?= $block->escapeHtmlAttr(__('Submit')) ?>" class="action submit primary">

                <span><?= $block->escapeHtml(__('Submit')) ?></span>

            </button>

        </div>

    </div>

</form>



9.app/code/Magelearn/Customform/Block/Customform.php



<?php



namespace Magelearn\Customform\Block;



/**

 * Customform content block

 */

class Customform extends \Magento\Framework\View\Element\Template

{

    /**

     * Index constructor.

     * @param \Magento\Framework\View\Element\Template\Context $context

     * @param array $data

     */

    public function __construct(

        \Magento\Framework\View\Element\Template\Context $context,

        array $data = []

    ) {

        parent::__construct($context, $data);

    }



    public function _prepareLayout()

    {

        $this->pageConfig->getTitle()->set(__('Customform About Customer Inquiry'));



        return parent::_prepareLayout();

    }

}



10.app/code/Magelearn/Customform/Controller/Index/Index.php



<?php



namespace Magelearn\Customform\Controller\Index;



/**

 * Class Index

 * @package Magelearn\Customform\Controller\Index

 */

class Index extends \Magento\Framework\App\Action\Action

{

    /**

     * @var \Magento\Framework\View\Result\PageFactory

     */

    protected $resultPageFactory;



    /**

     * Constructor

     * @param \Magento\Framework\App\Action\Context $context

     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

     */

    public function __construct(

        \Magento\Framework\App\Action\Context $context,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ) {

        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context);

    }



    /**

     * Execute view action

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        return $this->resultPageFactory->create();

    }

}



11.app/code/Magelearn/Customform/Controller/Index/Save.php



<?php



namespace Magelearn\Customform\Controller\Index;



use Magento\Framework\App\Action\Context;

use Magelearn\Customform\Model\CustomformFactory;

use Magento\Framework\App\Filesystem\DirectoryList;

use Magento\MediaStorage\Model\File\UploaderFactory;

use Magento\Framework\Image\AdapterFactory;

use Magento\Framework\Filesystem;



class Save extends \Magento\Framework\App\Action\Action

{

    /**

     * @var Customform

     */

    protected $_customform;

    protected $uploaderFactory;

    protected $adapterFactory;

    protected $filesystem;



    public function __construct(

        Context $context,

        CustomformFactory $customform,

        UploaderFactory $uploaderFactory,

        AdapterFactory $adapterFactory,

        Filesystem $filesystem

    ) {

        $this->_customform = $customform;

        $this->uploaderFactory = $uploaderFactory;

        $this->adapterFactory = $adapterFactory;

        $this->filesystem = $filesystem;

        parent::__construct($context);

    }

    public function execute()

    {

        if (!$this->getRequest()->isPost()) {

            return $this->resultRedirectFactory->create()->setPath('*/*/');

        }

        //$data = $this->getRequest()->getParams();

        $data = $this->validatedParams();

        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {

            try{

                $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'image']);

                $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);

                $imageAdapter = $this->adapterFactory->create();

                $uploaderFactory->addValidateCallback('custom_image_upload',$imageAdapter,'validateUploadFile');

                $uploaderFactory->setAllowRenameFiles(true);

                $uploaderFactory->setFilesDispersion(true);

                $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);

                $destinationPath = $mediaDirectory->getAbsolutePath('magelearn/customform');

                $result = $uploaderFactory->save($destinationPath);

                if (!$result) {

                    throw new LocalizedEsxception(

                        __('File cannot be saved to path: $1', $destinationPath)

                    );

                }



                $imagePath = 'magelearn/customform'.$result['file'];

                $data['image'] = $imagePath;

            } catch (\Exception $e) {

            }

        }

        $customform = $this->_customform->create();

        $customform->setData($data);

        if($customform->save()){

            $this->messageManager->addSuccessMessage(__('You saved the data.'));

        }else{

            $this->messageManager->addErrorMessage(__('Data was not saved.'));

        }

        $resultRedirect = $this->resultRedirectFactory->create();

        $resultRedirect->setPath('customform');

        return $resultRedirect;

    }

/**

     * @return array

     * @throws \Exception

     */

    private function validatedParams()

    {

        $request = $this->getRequest();

        if (trim($request->getParam('first_name')) === '') {

            throw new LocalizedException(__('Enter the First Name and try again.'));

        }

        if (trim($request->getParam('last_name')) === '') {

            throw new LocalizedException(__('Enter the Last Name and try again.'));

        }

        if (false === \strpos($request->getParam('email'), '@')) {

            throw new LocalizedException(__('The email address is invalid. Verify the email address and try again.'));

        }

        if (trim($request->getParam('phone')) === '') {

            throw new LocalizedException(__('Enter the Phone Number and try again.'));

        }

        if (trim($request->getParam('message')) === '') {

            throw new LocalizedException(__('Enter your message and try again.'));

        }

        return $request->getParams();

    }

}



12.app/code/Magelearn/Customform/Api/CustomformRepositoryInterface.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Api;



use Magento\Framework\Api\SearchCriteriaInterface;



interface CustomformRepositoryInterface

{



    /**

     * Save Customform

     * @param \Magelearn\Customform\Api\Data\CustomformInterface $customform

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function save(

        \Magelearn\Customform\Api\Data\CustomformInterface $customform

    );



    /**

     * Retrieve Customform

     * @param string $customformId

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function get($customformId);



    /**

     * Retrieve Customform matching the specified criteria.

     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria

     * @return \Magelearn\Customform\Api\Data\CustomformSearchResultsInterface

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function getList(

        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria

    );



    /**

     * Delete Customform

     * @param \Magelearn\Customform\Api\Data\CustomformInterface $customform

     * @return bool true on success

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function delete(

        \Magelearn\Customform\Api\Data\CustomformInterface $customform

    );



    /**

     * Delete Customform by ID

     * @param string $customformId

     * @return bool true on success

     * @throws \Magento\Framework\Exception\NoSuchEntityException

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function deleteById($customformId);

}



13.app/code/Magelearn/Customform/Api/Data/CustomformInterface.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Api\Data;



interface CustomformInterface extends \Magento\Framework\Api\ExtensibleDataInterface

{

    const ID = 'id';

    const MESSAGE = 'message';

    const FIRST_NAME = 'first_name';

    const LAST_NAME = 'last_name';

    const CREATED_AT = 'created_at';

    const EMAIL = 'email';

    const PHONE = 'phone';

    const IMAGE = 'image';

    const STATUS = 'status';



    /**

     * Get id

     * @return string|null

     */

    public function getId();



    /**

     * Set id

     * @param string $id

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setId($id);



    /**

     * Retrieve existing extension attributes object or create a new one.

     * @return \Magelearn\Customform\Api\Data\CustomformExtensionInterface|null

     */

    public function getExtensionAttributes();



    /**

     * Set an extension attributes object.

     * @param \Magelearn\Customform\Api\Data\CustomformExtensionInterface $extensionAttributes

     * @return $this

     */

    public function setExtensionAttributes(

        \Magelearn\Customform\Api\Data\CustomformExtensionInterface $extensionAttributes

    );



    /**

     * Get first_name

     * @return string|null

     */

    public function getFirstName();



    /**

     * Set first_name

     * @param string $firstName

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setFirstName($firstName);



    /**

     * Get last_name

     * @return string|null

     */

    public function getLastName();



    /**

     * Set last_name

     * @param string $lastName

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setLastName($lastName);



    /**

     * Get email

     * @return string|null

     */

    public function getEmail();



    /**

     * Set email

     * @param string $email

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setEmail($email);



    /**

     * Get phone

     * @return string|null

     */

    public function getPhone();



    /**

     * Set phone

     * @param string $phone

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setPhone($phone);



    /**

     * Get message

     * @return string|null

     */

    public function getMessage();



    /**

     * Set message

     * @param string $message

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setMessage($message);



    /**

     * Get image

     * @return string|null

     */

    public function getImage();



    /**

     * Set image

     * @param string $image

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setImage($image);



    /**

     * Get status

     * @return string|null

     */

    public function getStatus();



    /**

     * Set status

     * @param string $status

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setStatus($status);



    /**

     * Get created_at

     * @return string|null

     */

    public function getCreatedAt();



    /**

     * Set created_at

     * @param string $createdAt

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setCreatedAt($createdAt);

}



14.app/code/Magelearn/Customform/Api/Data/CustomformSearchResultsInterface.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Api\Data;



interface CustomformSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface

{



    /**

     * Get Customform list.

     * @return \Magelearn\Customform\Api\Data\CustomformInterface[]

     */

    public function getItems();



    /**

     * Set id list.

     * @param \Magelearn\Customform\Api\Data\CustomformInterface[] $items

     * @return $this

     */

    public function setItems(array $items);

}



15.app/code/Magelearn/Customform/Model/CustomformRepository.php



<?php
declare(strict_types=1);
namespace Magelearn\Customform\Model;
use Magelearn\Customform\Api\CustomformRepositoryInterface;

use Magelearn\Customform\Api\Data\CustomformInterfaceFactory;

use Magelearn\Customform\Api\Data\CustomformSearchResultsInterfaceFactory;

use Magelearn\Customform\Model\ResourceModel\Customform as ResourceCustomform;

use Magelearn\Customform\Model\ResourceModel\Customform\CollectionFactory as CustomformCollectionFactory;

use Magento\Framework\Api\DataObjectHelper;

use Magento\Framework\Api\ExtensibleDataObjectConverter;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

use Magento\Framework\Exception\CouldNotDeleteException;

use Magento\Framework\Exception\CouldNotSaveException;

use Magento\Framework\Exception\NoSuchEntityException;

use Magento\Framework\Reflection\DataObjectProcessor;

use Magento\Store\Model\StoreManagerInterface;



class CustomformRepository implements CustomformRepositoryInterface
{
 private $collectionProcessor;
 protected $dataObjectHelper;
 protected $extensionAttributesJoinProcessor;
 protected $customformCollectionFactory;
 protected $customformFactory;
 protected $searchResultsFactory;
 protected $dataObjectProcessor;
 protected $extensibleDataObjectConverter;
 protected $resource;
 protected $dataCustomformFactory;
 private $storeManager;

/**

     * @param ResourceCustomform $resource

     * @param CustomformFactory $customformFactory

     * @param CustomformInterfaceFactory $dataCustomformFactory

     * @param CustomformCollectionFactory $customformCollectionFactory

     * @param CustomformSearchResultsInterfaceFactory $searchResultsFactory

     * @param DataObjectHelper $dataObjectHelper

     * @param DataObjectProcessor $dataObjectProcessor

     * @param StoreManagerInterface $storeManager

     * @param CollectionProcessorInterface $collectionProcessor

     * @param JoinProcessorInterface $extensionAttributesJoinProcessor

     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter

     */

    public function __construct(

        ResourceCustomform $resource,

        CustomformFactory $customformFactory,

        CustomformInterfaceFactory $dataCustomformFactory,

        CustomformCollectionFactory $customformCollectionFactory,

        CustomformSearchResultsInterfaceFactory $searchResultsFactory,

        DataObjectHelper $dataObjectHelper,

        DataObjectProcessor $dataObjectProcessor,

        StoreManagerInterface $storeManager,

        CollectionProcessorInterface $collectionProcessor,

        JoinProcessorInterface $extensionAttributesJoinProcessor,

        ExtensibleDataObjectConverter $extensibleDataObjectConverter

    ) {

        $this->resource = $resource;

        $this->customformFactory = $customformFactory;

        $this->customformCollectionFactory = $customformCollectionFactory;

        $this->searchResultsFactory = $searchResultsFactory;

        $this->dataObjectHelper = $dataObjectHelper;

        $this->dataCustomformFactory = $dataCustomformFactory;

        $this->dataObjectProcessor = $dataObjectProcessor;

        $this->storeManager = $storeManager;

        $this->collectionProcessor = $collectionProcessor;

        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;

        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;

    }



    /**

     * {@inheritdoc}

     */

    public function save(

        \Magelearn\Customform\Api\Data\CustomformInterface $customform

    ) {

        /* if (empty($customform->getStoreId())) {

            $storeId = $this->storeManager->getStore()->getId();

            $customform->setStoreId($storeId);

        } */



        $customformData = $this->extensibleDataObjectConverter->toNestedArray(

            $customform,

            [],

            \Magelearn\Customform\Api\Data\CustomformInterface::class

        );



        $customformModel = $this->customformFactory->create()->setData($customformData);



        try {

            $this->resource->save($customformModel);

        } catch (\Exception $exception) {

            throw new CouldNotSaveException(__(

                'Could not save the customform: %1',

                $exception->getMessage()

            ));

        }

        return $customformModel->getDataModel();

    }



    /**

     * {@inheritdoc}

     */

    public function get($customformId)

    {

        $customform = $this->customformFactory->create();

        $this->resource->load($customform, $customformId);

        if (!$customform->getId()) {

            throw new NoSuchEntityException(__('Customform with id "%1" does not exist.', $customformId));

        }

        return $customform->getDataModel();

    }



    /**

     * {@inheritdoc}

     */

    public function getList(

        \Magento\Framework\Api\SearchCriteriaInterface $criteria

    ) {

        $collection = $this->customformCollectionFactory->create();



        $this->extensionAttributesJoinProcessor->process(

            $collection,

            \Magelearn\Customform\Api\Data\CustomformInterface::class

        );



        $this->collectionProcessor->process($criteria, $collection);



        $searchResults = $this->searchResultsFactory->create();

        $searchResults->setSearchCriteria($criteria);



        $items = [];

        foreach ($collection as $model) {

            $items[] = $model->getDataModel();

        }



        $searchResults->setItems($items);

        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;

    }



    /**

     * {@inheritdoc}

     */

    public function delete(

        \Magelearn\Customform\Api\Data\CustomformInterface $customform

    ) {

        try {

            $customformModel = $this->customformFactory->create();

            $this->resource->load($customformModel, $customform->getCustomformId());

            $this->resource->delete($customformModel);

        } catch (\Exception $exception) {

            throw new CouldNotDeleteException(__(

                'Could not delete the Customform: %1',

                $exception->getMessage()

            ));

        }

        return true;

    }



    /**

     * {@inheritdoc}

     */

    public function deleteById($customformId)

    {

        return $this->delete($this->get($customformId));

    }

}



16.app/code/Magelearn/Customform/Model/Data/Customform.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Model\Data;



use Magelearn\Customform\Api\Data\CustomformInterface;



class Customform extends \Magento\Framework\Api\AbstractExtensibleObject implements CustomformInterface

{

    /**

     * Get id

     * @return string|null

     */

    public function getId()

    {

        return $this->_get(self::ID);

    }



    /**

     * Set id

     * @param string $id

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setId($id)

    {

        return $this->setData(self::ID, $id);

    }



    /**

     * Retrieve existing extension attributes object or create a new one.

     * @return \Magelearn\Customform\Api\Data\CustomformExtensionInterface|null

     */

    public function getExtensionAttributes()

    {

        return $this->_getExtensionAttributes();

    }



    /**

     * Set an extension attributes object.

     * @param \Magelearn\Customform\Api\Data\CustomformExtensionInterface $extensionAttributes

     * @return $this

     */

    public function setExtensionAttributes(

        \Magelearn\Customform\Api\Data\CustomformExtensionInterface $extensionAttributes

    ) {

        return $this->_setExtensionAttributes($extensionAttributes);

    }



    /**

     * Get first_name

     * @return string|null

     */

    public function getFirstName()

    {

        return $this->_get(self::FIRST_NAME);

    }



    /**

     * Set first_name

     * @param string $firstName

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setFirstName($firstName)

    {

        return $this->setData(self::FIRST_NAME, $firstName);

    }



    /**

     * Get last_name

     * @return string|null

     */

    public function getLastName()

    {

        return $this->_get(self::LAST_NAME);

    }



    /**

     * Set last_name

     * @param string $lastName

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setLastName($lastName)

    {

        return $this->setData(self::LAST_NAME, $lastName);

    }



    /**

     * Get email

     * @return string|null

     */

    public function getEmail()

    {

        return $this->_get(self::EMAIL);

    }



    /**

     * Set email

     * @param string $email

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setEmail($email)

    {

        return $this->setData(self::EMAIL, $email);

    }



    /**

     * Get message

     * @return string|null

     */

    public function getMessage()

    {

        return $this->_get(self::MESSAGE);

    }



    /**

     * Set message

     * @param string $message

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setMessage($message)

    {

        return $this->setData(self::MESSAGE, $message);

    }



    /**

     * Get status

     * @return string|null

     */

    public function getStatus()

    {

        return $this->_get(self::STATUS);

    }



    /**

     * Set status

     * @param string $status

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setStatus($status)

    {

        return $this->setData(self::STATUS, $status);

    }



    /**

     * Get image

     * @return string|null

     */

    public function getImage()

    {

        return $this->_get(self::IMAGE);

    }



    /**

     * Set image

     * @param string $image

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setImage($image)

    {

        return $this->setData(self::IMAGE, $image);

    }



    /**

     * Get created_at

     * @return string|null

     */

    public function getCreatedAt()

    {

        return $this->_get(self::CREATED_AT);

    }



    /**

     * Set created_at

     * @param string $createdAt

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setCreatedAt($createdAt)

    {

        return $this->setData(self::CREATED_AT, $createdAt);

    }



    /**

     * Get phone

     * @return string|null

     */

    public function getPhone()

    {

        return $this->_get(self::PHONE);

    }



    /**

     * Set phone

     * @param string $phone

     * @return \Magelearn\Customform\Api\Data\CustomformInterface

     */

    public function setPhone($phone)

    {

        return $this->setData(self::PHONE, $phone);

    }

}



17.app/code/Magelearn/Customform/Model/Customform.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Model;



use Magelearn\Customform\Api\Data\CustomformInterface;

use Magelearn\Customform\Api\Data\CustomformInterfaceFactory;

use Magento\Framework\Api\DataObjectHelper;



class Customform extends \Magento\Framework\Model\AbstractModel

{



    protected $_eventPrefix = 'magelearn_customform';

    protected $dataObjectHelper;



    protected $customformDataFactory;





    /**

     * @param \Magento\Framework\Model\Context $context

     * @param \Magento\Framework\Registry $registry

     * @param CustomformInterfaceFactory $customformDataFactory

     * @param DataObjectHelper $dataObjectHelper

     * @param \Magelearn\Customform\Model\ResourceModel\Customform $resource

     * @param \Magelearn\Customform\Model\ResourceModel\Customform\Collection $resourceCollection

     * @param array $data

     */

    public function __construct(

        \Magento\Framework\Model\Context $context,

        \Magento\Framework\Registry $registry,

        CustomformInterfaceFactory $customformDataFactory,

        DataObjectHelper $dataObjectHelper,

        \Magelearn\Customform\Model\ResourceModel\Customform $resource,

        \Magelearn\Customform\Model\ResourceModel\Customform\Collection $resourceCollection,

        array $data = []

    ) {

        $this->customformDataFactory = $customformDataFactory;

        $this->dataObjectHelper = $dataObjectHelper;

        parent::__construct($context, $registry, $resource, $resourceCollection, $data);

    }



    /**

     * Retrieve customform model with customform data

     * @return CustomformInterface

     */

    public function getDataModel()

    {

        $customformData = $this->getData();



        $customformDataObject = $this->customformDataFactory->create();

        $this->dataObjectHelper->populateWithArray(

            $customformDataObject,

            $customformData,

            CustomformInterface::class

        );



        return $customformDataObject;

    }

}



18.app/code/Magelearn/Customform/Model/ResourceModel/Customform.php



<?php



namespace Magelearn\Customform\Model\ResourceModel;



class Customform extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb

{



    /**

     * Define resource model

     *

     * @return void

     */

    protected function _construct()

    {

        $this->_init('magelearn_customform', 'id');   //here "magelearn_customform" is table name and "id" is the primary key of custom table

    }

}



19.app/code/Magelearn/Customform/Model/ResourceModel/Customform/Collection.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Model\ResourceModel\Customform;



class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection

{



    /**

     * @var string

     */

    protected $_idFieldName = 'id';

    protected $_previewFlag;

    /**

     * Define resource model

     *

     * @return void

     */

    protected function _construct()

    {

        $this->_init(

            \Magelearn\Customform\Model\Customform::class,

            \Magelearn\Customform\Model\ResourceModel\Customform::class

        );

        $this->_map['fields']['id'] = 'main_table.id';

    }

}



20.app/code/Magelearn/Customform/etc/adminhtml/menu.xml



<?xml version="1.0" ?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Backend:etc/menu.xsd">

    <menu>

        <add id="Magelearn::top_level" title="Magelearn" module="Magelearn_Customform" sortOrder="9999" resource="Magento_Backend::content"/>

        <add id="Magelearn_Customform::manage_mlcustomform" title="Manage Customform" module="Magelearn_Customform" sortOrder="9999" resource="Magelearn_Customform::manage_mlcustomform" parent="Magelearn::top_level" action="mladmincustomform/customform"/>

    </menu>

</config>



21.app/code/Magelearn/Customform/etc/adminhtml/routes.xml



<?xml version="1.0" ?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">

    <router id="admin">

        <route frontName="mladmincustomform" id="mladmincustomform">

            <module before="Magento_Backend" name="Magelearn_Customform"/>

        </route>

    </router>

</config>



22.app/code/Magelearn/Customform/etc/acl.xml



<?xml version="1.0" ?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Acl/etc/acl.xsd">

    <acl>

        <resources>

            <resource id="Magento_Backend::admin">

                <resource id="Magelearn_Customform::Customform" title="Customform" sortOrder="10">

                    <resource id="Magelearn_Customform::Customform_save" title="Save Customform" sortOrder="10"/>

                    <resource id="Magelearn_Customform::Customform_delete" title="Delete Customform" sortOrder="20"/>

                    <resource id="Magelearn_Customform::Customform_update" title="Update Customform" sortOrder="30"/>

                    <resource id="Magelearn_Customform::Customform_view" title="View Customform" sortOrder="40"/>

                </resource>

            </resource>

        </resources>

    </acl>

</config>



23.app/code/Magelearn/Customform/view/adminhtml/layout/mladmincustomform_customform_index.xml



<?xml version="1.0" ?>

<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    <update handle="styles"/>

    <body>

        <referenceContainer name="content">

            <uiComponent name="mladmincustomform_customform_listing"/>

        </referenceContainer>

    </body>

</page>



24.app/code/Magelearn/Customform/Controller/Adminhtml/Customform/Index.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



class Index extends \Magento\Backend\App\Action

{



    protected $resultPageFactory;



    /**

     * Constructor

     *

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ) {

        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context);

    }



    /**

     * Index action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        $resultPage = $this->resultPageFactory->create();

        $resultPage->setActiveMenu('Magelearn::top_level');

        $resultPage->addBreadcrumb(__('Customform'), __('Customform'));

        $resultPage->addBreadcrumb(__('Manage Customform'), __('Manage Customform'));

            $resultPage->getConfig()->getTitle()->prepend(__("Manage Customform"));

            return $resultPage;

    }

}



25.app/code/Magelearn/Customform/view/adminhtml/ui_component/mladmincustomform_customform_listing.xml



<?xml version="1.0" ?>

<listing xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Ui:etc/ui_configuration.xsd">

    <argument name="data" xsi:type="array">

        <item name="js_config" xsi:type="array">

            <item name="provider" xsi:type="string">mladmincustomform_customform_listing.magelearn_customform_listing_data_source</item>

        </item>

    </argument>

    <settings>

        <spinner>magelearn_customform_columns</spinner>

        <deps>

            <dep>mladmincustomform_customform_listing.magelearn_customform_listing_data_source</dep>

        </deps>

        <buttons>

            <button name="add">

                <url path="*/*/new"/>

                <class>primary</class>

                <label translate="true">Add new Customform</label>

            </button>

        </buttons>

    </settings>

    <dataSource name="magelearn_customform_listing_data_source" component="Magento_Ui/js/grid/provider">

        <settings>

            <updateUrl path="mui/index/render"/>

        </settings>

        <aclResource>Magelearn_Customform::Customform</aclResource>

        <dataProvider name="magelearn_customform_listing_data_source" class="Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider">

            <settings>

                <requestFieldName>id</requestFieldName>

                <primaryFieldName>id</primaryFieldName>

            </settings>

        </dataProvider>

    </dataSource>

    <listingToolbar name="listing_top">

        <settings>

            <sticky>true</sticky>

        </settings>

        <bookmark name="bookmarks"/>

        <columnsControls name="columns_controls"/>

        <massaction name="listing_massaction">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="selectProvider" xsi:type="string">mladmincustomform_customform_listing.mladmincustomform_customform_listing.magelearn_customform_columns.ids</item>

                    <item name="indexField" xsi:type="string">id</item>

                </item>

            </argument>

            <action name="delete">

                <argument name="data" xsi:type="array">

                    <item name="config" xsi:type="array">

                        <item name="type" xsi:type="string">delete</item>

                        <item name="label" xsi:type="string" translate="true">Delete</item>

                        <item name="url" xsi:type="url" path="mladmincustomform/Customform/massDelete"/>

                        <item name="confirm" xsi:type="array">

                            <item name="name" xsi:type="string" translate="true">Delete items</item>

                            <item name="message" xsi:type="string" translate="true">Are you sure you wan't to delete selected items?</item>

                        </item>

                    </item>

                </argument>

            </action>

            <action name="edit">

                <argument name="data" xsi:type="array">

                    <item name="config" xsi:type="array">

                        <item name="type" xsi:type="string">edit</item>

                        <item name="label" xsi:type="string" translate="true">Edit</item>

                        <item name="callback" xsi:type="array">

                            <item name="provider" xsi:type="string">mladmincustomform_customform_listing.mladmincustomform_customform_listing.magelearn_customform_columns_editor</item>

                            <item name="target" xsi:type="string">editSelected</item>

                        </item>

                    </item>

                </argument>

            </action>

            <action name="disable">

                <settings>

                    <url path="mladmincustomform/Customform/massDisable"/>

                    <type>disable</type>

                    <label translate="true">Disable</label>

                </settings>

            </action>

            <action name="enable">

                <settings>

                    <url path="mladmincustomform/Customform/massEnable"/>

                    <type>enable</type>

                    <label translate="true">Enable</label>

                </settings>

            </action>

        </massaction>

        <filters name="listing_filters"/>

        <paging name="listing_paging"/>

        <filterSearch name="fulltext">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="provider" xsi:type="string">mladmincustomform_customform_listing.magelearn_customform_listing_data_source</item>

                    <item name="chipsProvider" xsi:type="string">mladmincustomform_customform_listing.mladmincustomform_customform_listing.listing_top.listing_filters_chips</item>

                    <item name="storageConfig" xsi:type="array">

                        <item name="provider" xsi:type="string">mladmincustomform_customform_listing.mladmincustomform_customform_listing.listing_top.bookmarks</item>

                        <item name="namespace" xsi:type="string">current.search</item>

                    </item>

                </item>

            </argument>

        </filterSearch>

    </listingToolbar>

    <columns name="magelearn_customform_columns">

        <settings>

            <editorConfig>

                <param name="selectProvider" xsi:type="string">mladmincustomform_customform_listing.mladmincustomform_customform_listing.magelearn_customform_columns.ids</param>

                <param name="enabled" xsi:type="boolean">true</param>

                <param name="indexField" xsi:type="string">id</param>

                <param name="clientConfig" xsi:type="array">

                    <item name="saveUrl" xsi:type="url" path="mladmincustomform/Customform/inlineEdit"/>

                    <item name="validateBeforeSave" xsi:type="boolean">false</item>

                </param>

            </editorConfig>

            <childDefaults>

                <param name="fieldAction" xsi:type="array">

                    <item name="provider" xsi:type="string">mladmincustomform_customform_listing.mladmincustomform_customform_listing.magelearn_customform_columns_editor</item>

                    <item name="target" xsi:type="string">startEdit</item>

                    <item name="params" xsi:type="array">

                        <item name="0" xsi:type="string">${ $.$data.rowIndex }</item>

                        <item name="1" xsi:type="boolean">true</item>

                    </item>

                </param>

            </childDefaults>

        </settings>

        <selectionsColumn name="ids">

            <settings>

                <indexField>id</indexField>

            </settings>

        </selectionsColumn>

        <column name="id">

            <settings>

                <filter>text</filter>

                <sorting>asc</sorting>

                <label translate="true">ID</label>

            </settings>

        </column>

        <column name="first_name">

            <settings>

                <filter>text</filter>

                <label translate="true">first_name</label>

                <editor>

                    <editorType>text</editorType>

                    <validation>

                        <rule name="required-entry" xsi:type="boolean">false</rule>

                    </validation>

                </editor>

            </settings>

        </column>

        <column name="last_name">

            <settings>

                <filter>text</filter>

                <label translate="true">last_name</label>

                <editor>

                    <editorType>text</editorType>

                    <validation>

                        <rule name="required-entry" xsi:type="boolean">false</rule>

                    </validation>

                </editor>

            </settings>

        </column>

        <column name="email">

            <settings>

                <filter>text</filter>

                <label translate="true">email</label>

                <editor>

                    <editorType>text</editorType>

                    <validation>

                        <rule name="required-entry" xsi:type="boolean">false</rule>

                    </validation>

                </editor>

            </settings>

        </column>

        <column name="message">

            <settings>

                <filter>text</filter>

                <label translate="true">message</label>

                <editor>

                    <editorType>text</editorType>

                    <validation>

                        <rule name="required-entry" xsi:type="boolean">false</rule>

                    </validation>

                </editor>

            </settings>

        </column>

        <column name="image" class="Magelearn\Customform\Ui\Component\Listing\Column\Thumbnail">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="component" xsi:type="string">Magento_Ui/js/grid/columns/thumbnail</item>

                    <item name="sortable" xsi:type="boolean">false</item>

                    <item name="has_preview" xsi:type="string">1</item>

                    <item name="label" xsi:type="string" translate="true">Image</item>

                </item>

            </argument>

        </column>

        <column name="status">

            <argument name="data" xsi:type="array">

                <item name="options" xsi:type="object">Magelearn\Customform\Model\Source\Status</item>

                <item name="config" xsi:type="array">

                    <item name="editor" xsi:type="array">

                        <item name="editorType" xsi:type="string">select</item>

                        <item name="validation" xsi:type="array">

                            <item name="required-entry" xsi:type="boolean">true</item>

                        </item>

                    </item>

                    <item name="filter" xsi:type="string">select</item>

                    <item name="component" xsi:type="string">Magento_Ui/js/grid/columns/select</item>

                    <item name="label" xsi:type="string" translate="true">Status</item>

                    <item name="dataType" xsi:type="string">select</item>

                    <item name="sortOrder" xsi:type="number">30</item>

                    <item name="bodyTmpl" xsi:type="string">ui/grid/cells/html</item>

                </item>

            </argument>

        </column>

        <column name="created_at" class="Magento\Ui\Component\Listing\Columns\Date" component="Magento_Ui/js/grid/columns/date">

            <settings>

                <filter>dateRange</filter>

                <dataType>date</dataType>

                <label translate="true">created_at</label>

                <editor>

                    <editorType>date</editorType>

                    <validation>

                        <rule name="required-entry" xsi:type="boolean">false</rule>

                    </validation>

                </editor>

            </settings>

        </column>

        <column name="phone">

            <settings>

                <filter>text</filter>

                <label translate="true">phone</label>

                <editor>

                    <editorType>text</editorType>

                    <validation>

                        <rule name="required-entry" xsi:type="boolean">false</rule>

                    </validation>

                </editor>

            </settings>

        </column>

        <actionsColumn name="actions" class="Magelearn\Customform\Ui\Component\Listing\Column\CustomformActions">

            <settings>

                <indexField>id</indexField>

                <resizeEnabled>false</resizeEnabled>

                <resizeDefaultWidth>107</resizeDefaultWidth>

            </settings>

        </actionsColumn>

    </columns>

</listing>



26.app/code/Magelearn/Customform/Model/Source/Status.php



<?php



namespace Magelearn\Customform\Model\Source;



use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

use Magento\Eav\Model\Entity\Attribute\Source\SourceInterface;

use Magento\Framework\Data\OptionSourceInterface;



/**

 * Item status functionality model

 */

class Status extends AbstractSource implements SourceInterface, OptionSourceInterface

{

    /**#@+

     * Item Status values

     */

    const STATUS_ENABLED = 1;



    const STATUS_DISABLED = 0;



    /**#@-*/



    /**

     * Retrieve option array

     *

     * @return string[]

     */

    public static function getOptionArray()

    {

        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];

    }



    /**

     * Retrieve option array with empty value

     *

     * @return string[]

     */

    public function getAllOptions()

    {

        $result = [];



        foreach (self::getOptionArray() as $index => $value) {

            $result[] = ['value' => $index, 'label' => $value];

        }



        return $result;

    }

}



27.app/code/Magelearn/Customform/Ui/Component/Listing/Column/Thumbnail.php



<?php



namespace Magelearn\Customform\Ui\Component\Listing\Column;



use Magento\Framework\View\Element\UiComponentFactory;

use Magento\Framework\View\Element\UiComponent\ContextInterface;

use Magento\Store\Model\StoreManagerInterface;



class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column

{

    const NAME = 'image';

    const ALT_FIELD = 'name';

    protected $storeManager;



    /**

     * @param ContextInterface $context

     * @param UiComponentFactory $uiComponentFactory

     * @param \Magento\Catalog\Helper\Image $imageHelper

     * @param \Magento\Framework\UrlInterface $urlBuilder

     * @param array $components

     * @param array $data

     */

    public function __construct(

        ContextInterface $context,

        UiComponentFactory $uiComponentFactory,

        StoreManagerInterface $storeManager,

        array $components = [],

        array $data = []

    ) {

        parent::__construct($context, $uiComponentFactory, $components, $data);

        $this->storeManager = $storeManager;

    }



    /**

     * Prepare Data Source

     *

     * @param array $dataSource

     * @return array

     */

    public function prepareDataSource(array $dataSource)

    {

        if (isset($dataSource['data']['items'])) {

            $fieldName = $this->getData('name');

            $path = $this->storeManager->getStore()->getBaseUrl(

                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA

                    );

            foreach ($dataSource['data']['items'] as & $item) {

                if ($item['image']) {

                    $item[$fieldName . '_src'] = $path.'magelearn/customform'.$item['image'];

                    $item[$fieldName . '_alt'] = $item['first_name'].' '.$item['last_name'];

                    $item[$fieldName . '_orig_src'] = $path.'magelearn/customform'.$item['image'];

                }else{

                    // please place your placeholder image at pub/media/magelearn/customform/placeholder/placeholder.jpg

                    $item[$fieldName . '_src'] = $path.'magelearn/customform/placeholder/placeholder.jpg';

                    $item[$fieldName . '_alt'] = 'Place Holder';

                    $item[$fieldName . '_orig_src'] = $path.'magelearn/customform/placeholder/placeholder.jpg';

                }

            }

        }



        return $dataSource;

    }

}



28.app/code/Magelearn/Customform/Controller/Adminhtml/Customform/NewAction.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



class NewAction extends \Magelearn\Customform\Controller\Adminhtml\Customform

{



    protected $resultForwardFactory;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\Registry $coreRegistry

     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\Registry $coreRegistry,

        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory

    ) {

        $this->resultForwardFactory = $resultForwardFactory;

        parent::__construct($context, $coreRegistry);

    }



    /**

     * New action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        /** @var \Magento\Framework\Controller\Result\Forward $resultForward */

        $resultForward = $this->resultForwardFactory->create();

        return $resultForward->forward('edit');

    }

}



29.app/code/Magelearn/Customform/Controller/Adminhtml/Customform.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml;



abstract class Customform extends \Magento\Backend\App\Action

{



    const ADMIN_RESOURCE = 'Magelearn_Customform::top_level';

    protected $_coreRegistry;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\Registry $coreRegistry

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\Registry $coreRegistry

    ) {

        $this->_coreRegistry = $coreRegistry;

        parent::__construct($context);

    }



    /**

     * Init page

     *

     * @param \Magento\Backend\Model\View\Result\Page $resultPage

     * @return \Magento\Backend\Model\View\Result\Page

     */

    public function initPage($resultPage)

    {

        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)

            ->addBreadcrumb(__('Magelearn'), __('Magelearn'))

            ->addBreadcrumb(__('Customform'), __('Customform'));

        return $resultPage;

    }

}



30.app/code/Magelearn/Customform/Controller/Adminhtml/Customform/Edit.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



class Edit extends \Magelearn\Customform\Controller\Adminhtml\Customform

{



    protected $resultPageFactory;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\Registry $coreRegistry

     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\Registry $coreRegistry,

        \Magento\Framework\View\Result\PageFactory $resultPageFactory

    ) {

        $this->resultPageFactory = $resultPageFactory;

        parent::__construct($context, $coreRegistry);

    }



    /**

     * Edit action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        // 1. Get ID and create model

        $id = $this->getRequest()->getParam('id');

        $model = $this->_objectManager->create(\Magelearn\Customform\Model\Customform::class);



        // 2. Initial checking

        if ($id) {

            $model->load($id);

            if (!$model->getId()) {

                $this->messageManager->addErrorMessage(__('This Customform no longer exists.'));

                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

                $resultRedirect = $this->resultRedirectFactory->create();

                return $resultRedirect->setPath('*/*/');

            }

        }

        $this->_coreRegistry->register('magelearn_customform', $model);



        // 3. Build edit form

        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */

        $resultPage = $this->resultPageFactory->create();

        $this->initPage($resultPage)->addBreadcrumb(

            $id ? __('Edit Customform') : __('New Customform'),

            $id ? __('Edit Customform') : __('New Customform')

        );

        $resultPage->getConfig()->getTitle()->prepend(__('Customforms'));

        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Customform %1', $model->getId()) : __('New Customform'));

        return $resultPage;

    }

}



31.app/code/Magelearn/Customform/view/adminhtml/layout/mladmincustomform_customform_new.xml



<?xml version="1.0" ?>

<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    <update handle="mladmincustomform_customform_edit"/>

</page>



32.app/code/Magelearn/Customform/view/adminhtml/layout/mladmincustomform_customform_edit.xml



<pre class="brush: xml;"><?xml version="1.0" ?>

<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    <update handle="styles"/>

    <body>

        <referenceContainer name="content">

            <uiComponent name="mladmincustomform_customform_form"/>

        </referenceContainer>

    </body>

</page></pre>



33.app/code/Magelearn/Customform/view/adminhtml/ui_component/mladmincustomform_customform_form.xml



<?xml version="1.0" ?>

<form xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Ui:etc/ui_configuration.xsd">

    <argument name="data" xsi:type="array">

        <item name="js_config" xsi:type="array">

            <item name="provider" xsi:type="string">mladmincustomform_customform_form.customform_form_data_source</item>

        </item>

        <item name="label" xsi:type="string" translate="true">General Information</item>

        <item name="template" xsi:type="string">templates/form/collapsible</item>

    </argument>

    <settings>

        <buttons>

            <button name="back" class="Magelearn\Customform\Block\Adminhtml\Customform\Edit\BackButton"/>

            <button name="delete" class="Magelearn\Customform\Block\Adminhtml\Customform\Edit\DeleteButton"/>

            <button name="reset" class="Magelearn\Customform\Block\Adminhtml\Customform\Edit\ResetButton"/>

            <button name="save" class="Magelearn\Customform\Block\Adminhtml\Customform\Edit\SaveButton"/>

            <button name="save_and_continue" class="Magelearn\Customform\Block\Adminhtml\Customform\Edit\SaveAndContinueButton"/>

        </buttons>

        <namespace>mladmincustomform_customform_form</namespace>

        <dataScope>data</dataScope>

        <deps>

            <dep>mladmincustomform_customform_form.customform_form_data_source</dep>

        </deps>

    </settings>

    <dataSource name="customform_form_data_source">

        <argument name="data" xsi:type="array">

            <item name="js_config" xsi:type="array">

                <item name="component" xsi:type="string">Magento_Ui/js/form/provider</item>

            </item>

        </argument>

        <settings>

            <submitUrl path="*/*/save"/>

        </settings>

        <dataProvider name="customform_form_data_source" class="Magelearn\Customform\Model\Customform\DataProvider">

            <settings>

                <requestFieldName>id</requestFieldName>

                <primaryFieldName>id</primaryFieldName>

            </settings>

        </dataProvider>

    </dataSource>

    <fieldset name="general">

        <settings>

            <label>General</label>

        </settings>

        <field name="first_name" formElement="textarea" sortOrder="10">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="source" xsi:type="string">Customform</item>

                </item>

            </argument>

            <settings>

                <dataType>text</dataType>

                <label translate="true">First Name</label>

                <dataScope>first_name</dataScope>

                <validation>

                    <rule name="required-entry" xsi:type="boolean">true</rule>

                </validation>

            </settings>

        </field>

        <field name="last_name" formElement="textarea" sortOrder="20">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="source" xsi:type="string">Customform</item>

                </item>

            </argument>

            <settings>

                <dataType>text</dataType>

                <label translate="true">Last Name</label>

                <dataScope>last_name</dataScope>

                <validation>

                    <rule name="required-entry" xsi:type="boolean">true</rule>

                </validation>

            </settings>

        </field>

        <field name="email" formElement="textarea" sortOrder="30">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="source" xsi:type="string">Customform</item>

                </item>

            </argument>

            <settings>

                <dataType>text</dataType>

                <label translate="true">Email</label>

                <dataScope>email</dataScope>

                <validation>

                    <rule name="required-entry" xsi:type="boolean">true</rule>

                </validation>

            </settings>

        </field>

        <field name="phone" formElement="input" sortOrder="40">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="source" xsi:type="string">Customform</item>

                </item>

            </argument>

            <settings>

                <dataType>text</dataType>

                <label translate="true">Phone</label>

                <dataScope>phone</dataScope>

                <validation>

                    <rule name="required-entry" xsi:type="boolean">true</rule>

                </validation>

            </settings>

        </field>

        <field name="message" formElement="textarea" sortOrder="50">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="source" xsi:type="string">Customform</item>

                </item>

            </argument>

            <settings>

                <dataType>text</dataType>

                <label translate="true">Message</label>

                <dataScope>message</dataScope>

                <validation>

                    <rule name="required-entry" xsi:type="boolean">true</rule>

                </validation>

            </settings>

        </field>

        <field name="image">

                    <argument name="data" xsi:type="array">

                        <item name="config" xsi:type="array">

                            <item name="dataType" xsi:type="string">string</item>

                            <item name="source" xsi:type="string">image</item>

                            <item name="label" xsi:type="string" translate="true">Image</item>

                            <item name="visible" xsi:type="boolean">true</item>

                            <item name="formElement" xsi:type="string">fileUploader</item>

                            <item name="previewTmpl" xsi:type="string">Magelearn_Customform/image-preview</item>

                            <item name="elementTmpl" xsi:type="string">ui/form/element/uploader/uploader</item>

                            <item name="required" xsi:type="boolean">false</item>

                            <item name="uploaderConfig" xsi:type="array">

                                <item name="url" xsi:type="url" path="mladmincustomform/customform_fileUploader/save"/>

                            </item>

                        </item>

                    </argument>

          </field>

        <field name="status" formElement="checkbox">

            <argument name="data" xsi:type="array">

                <item name="config" xsi:type="array">

                    <item name="source" xsi:type="string">Customform</item>

                    <item name="default" xsi:type="number">1</item>

                </item>

            </argument>

            <settings>

                <validation>

                    <rule name="required-entry" xsi:type="boolean">true</rule>

                </validation>

                <dataType>boolean</dataType>

                <label translate="true">Enable</label>

            </settings>

            <formElements>

                <checkbox>

                    <settings>

                        <valueMap>

                            <map name="false" xsi:type="number">0</map>

                            <map name="true" xsi:type="number">1</map>

                        </valueMap>

                        <prefer>toggle</prefer>

                    </settings>

                </checkbox>

            </formElements>

        </field>

    </fieldset>

</form>



34.app/code/Magelearn/Customform/Controller/Adminhtml/Customform/FileUploader/Save.php



<?php



namespace Magelearn\Customform\Controller\Adminhtml\Customform\FileUploader;



use Magento\Framework\Controller\ResultFactory;



class Save extends \Magento\Backend\App\Action {



    /**

     * Image uploader

     *

     * @var \Igorludgero\Brandlist\Model\ImageUploader

     */

    protected $imageUploader;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Igorludgero\Brandlist\Model\ImageUploader $imageUploader

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magelearn\Customform\Model\ImageUploader $imageUploader

    ) {

        parent::__construct($context);

        $this->imageUploader = $imageUploader;

    }



    /**

     * Check admin permissions for this controller

     *

     * @return boolean

     */

    protected function _isAllowed()

    {

        return $this->_authorization->isAllowed('Magelearn_Customform::Customform');

    }



    public function execute()

    {

        try {

            $result = $this->imageUploader->saveFileToTmpDir('image');



            $result['cookie'] = [

                'name' => $this->_getSession()->getName(),

                'value' => $this->_getSession()->getSessionId(),

                'lifetime' => $this->_getSession()->getCookieLifetime(),

                'path' => $this->_getSession()->getCookiePath(),

                'domain' => $this->_getSession()->getCookieDomain(),

            ];

        } catch (\Exception $e) {

            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];

        }

        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);

    }



}



35.app/code/Magelearn/Customform/Model/ImageUploader.php



<?php



namespace Magelearn\Customform\Model;



use Magento\Framework\Image\AdapterFactory;



class ImageUploader

{

    /**

     * Core file storage database

     *

     * @var \Magento\MediaStorage\Helper\File\Storage\Database

     */

    protected $coreFileStorageDatabase;



    /**

     * Media directory object (writable).

     *

     * @var \Magento\Framework\Filesystem\Directory\WriteInterface

     */

    protected $mediaDirectory;



    /**

     * Uploader factory

     *

     * @var \Magento\MediaStorage\Model\File\UploaderFactory

     */

    private $uploaderFactory;

    protected $filesystem;

    protected $adapterFactory;



    /**

     * Store manager

     *

     * @var \Magento\Store\Model\StoreManagerInterface

     */

    protected $storeManager;



    /**

     * @var \Psr\Log\LoggerInterface

     */

    protected $logger;



    /**

     * Base tmp path

     *

     * @var string

     */

    protected $baseTmpPath;



    /**

     * Base path

     *

     * @var string

     */

    protected $basePath;



    /**

     * Allowed extensions

     *

     * @var string

     */

    protected $allowedExtensions;



    const DEFAULT_BASETMPATH = "magelearn/customform/tmp";



    const DEFAULT_BASEPATH = "magelearn/customform";



    /**

     * ImageUploader constructor

     *

     * @param \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase

     * @param \Magento\Framework\Filesystem $filesystem

     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory

     * @param \Magento\Store\Model\StoreManagerInterface $storeManager

     * @param \Psr\Log\LoggerInterface $logger

     * @param string $baseTmpPath

     * @param string $basePath

     * @param string[] $allowedExtensions

     */

    public function __construct(

        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,

        \Magento\Framework\Filesystem $filesystem,

        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,

        AdapterFactory $adapterFactory,

        \Magento\Store\Model\StoreManagerInterface $storeManager,

        \Psr\Log\LoggerInterface $logger,

        $baseTmpPath = null,

        $basePath = null,

        $allowedExtensions = null

    ) {

        $this->coreFileStorageDatabase = $coreFileStorageDatabase;

        $this->filesystem = $filesystem;

        $this->mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        $this->uploaderFactory = $uploaderFactory;

        $this->adapterFactory = $adapterFactory;

        $this->storeManager = $storeManager;

        $this->logger = $logger;

        $this->baseTmpPath = ($baseTmpPath != null ) ? $baseTmpPath : self::DEFAULT_BASETMPATH;

        $this->basePath = ($basePath != null) ? $basePath : self::DEFAULT_BASEPATH;

        $this->allowedExtensions = ($allowedExtensions != null) ? $allowedExtensions : ['jpg', 'jpeg', 'gif', 'png'];

    }



    /**

     * Set base tmp path

     *

     * @param string $baseTmpPath

     *

     * @return void

     */

    public function setBaseTmpPath($baseTmpPath)

    {

        $this->baseTmpPath = $baseTmpPath;

    }



    /**

     * Set base path

     *

     * @param string $basePath

     *

     * @return void

     */

    public function setBasePath($basePath)

    {

        $this->basePath = $basePath;

    }



    /**

     * Set allowed extensions

     *

     * @param string[] $allowedExtensions

     *

     * @return void

     */

    public function setAllowedExtensions($allowedExtensions)

    {

        $this->allowedExtensions = $allowedExtensions;

    }



    /**

     * Retrieve base tmp path

     *

     * @return string

     */

    public function getBaseTmpPath()

    {

        return $this->baseTmpPath;

    }



    /**

     * Retrieve base path

     *

     * @return string

     */

    public function getBasePath()

    {

        return $this->basePath;

    }



    /**

     * Retrieve base path

     *

     * @return string[]

     */

    public function getAllowedExtensions()

    {

        return $this->allowedExtensions;

    }



    /**

     * Retrieve path

     *

     * @param string $path

     * @param string $imageName

     *

     * @return string

     */

    public function getFilePath($path, $imageName)

    {

        return rtrim($path, '/') . '/' . ltrim($imageName, '/');

    }



    /**

     * Checking file for moving and move it

     *

     * @param string $imageName

     *

     * @return string

     *

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function moveFileFromTmp($imageName)

    {



        $baseTmpPath = $this->getBaseTmpPath();

        $basePath = $this->getBasePath();



        $baseImagePath = $this->getFilePath($basePath, $imageName);

        $baseTmpImagePath = $this->getFilePath($baseTmpPath, $imageName);



        try {

            $this->coreFileStorageDatabase->copyFile(

                $baseTmpImagePath,

                $baseImagePath

            );

            $this->mediaDirectory->renameFile(

                $baseTmpImagePath,

                $baseImagePath

            );

        } catch (\Exception $e) {

            throw new \Magento\Framework\Exception\LocalizedException(

                __('Something went wrong while saving the file(s).')

            );

        }



        return $imageName;

    }



    /**

     * Checking file for save and save it to tmp dir

     *

     * @param string $fileId

     *

     * @return string[]

     *

     * @throws \Magento\Framework\Exception\LocalizedException

     */

    public function saveFileToTmpDir($fileId)

    {

        $baseTmpPath = $this->getBaseTmpPath();



        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);

        $uploader->setAllowedExtensions($this->getAllowedExtensions());

        $uploader->setAllowRenameFiles(true);

        $uploader->setFilesDispersion(true);



        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));

        if (!$result) {

            throw new \Magento\Framework\Exception\LocalizedException(

                __('File can not be saved to the destination folder.')

            );

        }



        /**

         * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS

         */

        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);

        $result['path'] = str_replace('\\', '/', $result['path']);

        $result['url'] = $this->storeManager

                ->getStore()

                ->getBaseUrl(

                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA

                ) . $this->getFilePath($baseTmpPath, $result['file']);

        $result['name'] = $result['file'];



        if (isset($result['file'])) {

            try {

                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');

                $this->coreFileStorageDatabase->saveFile($relativePath);

            } catch (\Exception $e) {

                $this->logger->critical($e);

                throw new \Magento\Framework\Exception\LocalizedException(

                    __('Something went wrong while saving the file(s).')

                );

            }

        }



        return $result;

    }

}



36.app/code/Magelearn/Customform/view/adminhtml/web/template/image-preview.html



<div class="file-uploader-summary">

    <div class="file-uploader-preview image-uploader-preview">

        <a class="image-uploader-preview-link" attr="href: $parent.getFilePreview($file)" target="_blank">

            <div class="file-uploader-spinner image-uploader-spinner" />

            <img

                class="preview-image"

                tabindex="0"

                event="load: $parent.onPreviewLoad.bind($parent)"

                attr="

                    src: $parent.getFilePreview($file),

                    alt: $file.name,

                    title: $file.name">

        </a>



        <div class="actions">

            <button

                type="button"

                class="action-remove"

                data-role="delete-button"

                attr="title: $t('Delete image')"

                disable="$parent.disabled"

                click="$parent.removeFile.bind($parent, $file)">

                <span translate="'Delete image'"/>

            </button>

        </div>

    </div>



    <div class="file-uploader-filename" text="$file.name"/>

    <div class="file-uploader-meta">

        <text args="$file.previewWidth"/>x<text args="$file.previewHeight"/>

    </div>

</div>



37.app/code/Magelearn/Customform/Model/Customform/DataProvider.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Model\Customform;



use Magelearn\Customform\Model\ResourceModel\Customform\CollectionFactory;

use Magento\Framework\App\Request\DataPersistorInterface;

use Magento\Framework\Filesystem;

use Magento\Framework\App\Filesystem\DirectoryList;



class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider

{



    protected $loadedData;

    protected $collection;

    protected $filesystem;

    protected $dataPersistor;

    /**

     * Store manager

     *

     * @var \Magento\Store\Model\StoreManagerInterface

     */

    protected $storeManager;



    /**

     * Constructor

     *

     * @param string $name

     * @param string $primaryFieldName

     * @param string $requestFieldName

     * @param CollectionFactory $collectionFactory

     * @param DataPersistorInterface $dataPersistor

     * @param array $meta

     * @param array $data

     */

    public function __construct(

        $name,

        $primaryFieldName,

        $requestFieldName,

        CollectionFactory $collectionFactory,

        DataPersistorInterface $dataPersistor,

        \Magento\Store\Model\StoreManagerInterface $storeManager,

        Filesystem $filesystem,

        array $meta = [],

        array $data = []

    ) {

        $this->collection = $collectionFactory->create();

        $this->dataPersistor = $dataPersistor;

        $this->filesystem = $filesystem;

        $this->storeManager = $storeManager;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);

    }



    /**

     * Get data

     *

     * @return array

     */

    public function getData()

    {

        if (isset($this->loadedData)) {

            return $this->loadedData;

        }

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);

        $destinationPath = $mediaDirectory->getAbsolutePath('magelearn/customform');

        $items = $this->collection->getItems();

        foreach ($items as $model) {

            $itemData = $model->getData();

            if ($model->getImage()) {

            $imageName = $itemData['image']; // Your database field

            //unset($itemData['image']);

            $itemData['image'] = array(

                array(

                    'name'  =>  $imageName,

                    'url'   =>  $this->storeManager

                ->getStore()

                ->getBaseUrl(

                    \Magento\Framework\UrlInterface::URL_TYPE_MEDIA

                ).'magelearn/customform'.$itemData['image'] // Should return a URL to view the image. For example, http://domain.com/pub/media/../../imagename.jpeg

                )

            );

            }

            $this->loadedData[$model->getId()] = $itemData;

        }

        $data = $this->dataPersistor->get('mladmincustomform');



        if (!empty($data)) {

            $model = $this->collection->getNewEmptyItem();

            $model->setData($data);

            $this->loadedData[$model->getId()] = $model->getData();

            $this->dataPersistor->clear('mladmincustomform');

        }



        return $this->loadedData;

    }



}



38.app/code/Magelearn/Customform/Controller/Adminhtml/Customform/Save.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



use Magento\Framework\Exception\LocalizedException;



class Save extends \Magento\Backend\App\Action

{



    protected $dataPersistor;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor

    ) {

        $this->dataPersistor = $dataPersistor;

        parent::__construct($context);

    }



    /**

     * Save action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();

        if ($data) {

            $id = $this->getRequest()->getParam('id');



            $model = $this->_objectManager->create(\Magelearn\Customform\Model\Customform::class)->load($id);

            if (!$model->getId() && $id) {

                $this->messageManager->addErrorMessage(__('This Customform no longer exists.'));

                return $resultRedirect->setPath('*/*/');

            }



            if (isset($data['image'][0]['name']) && isset($data['image'][0]['tmp_name'])) {

                $data['image'] =$data['image'][0]['name'];

                $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get(

                'Magelearn\Customform\CustomformImageUpload'

            );

                $this->imageUploader->moveFileFromTmp($data['image']);

            } elseif (isset($data['image'][0]['name']) && !isset($data['image'][0]['tmp_name'])) {

                $data['image'] = $data['image'][0]['name'];

            } else {

                $data['image'] = null;

            }

            $model->setData($data);



            try {

                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the Customform.'));

                $this->dataPersistor->clear('mladmincustomform');



                if ($this->getRequest()->getParam('back')) {

                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);

                }

                return $resultRedirect->setPath('*/*/');

            } catch (LocalizedException $e) {

                $this->messageManager->addErrorMessage($e->getMessage());

            } catch (\Exception $e) {

                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Customform.'));

            }



            $this->dataPersistor->set('mladmincustomform', $data);

            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);

        }

        return $resultRedirect->setPath('*/*/');

    }

}



39.app/code/Magelearn/Customform/Block/Adminhtml/Customform/Edit/BackButton.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Block\Adminhtml\Customform\Edit;



use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;



class BackButton extends GenericButton implements ButtonProviderInterface

{



    /**

     * @return array

     */

    public function getButtonData()

    {

        return [

            'label' => __('Back'),

            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),

            'class' => 'back',

            'sort_order' => 10

        ];

    }



    /**

     * Get URL for back (reset) button

     *

     * @return string

     */

    public function getBackUrl()

    {

        return $this->getUrl('*/*/');

    }

}



40.app/code/Magelearn/Customform/Block/Adminhtml/Customform/Edit/GenericButton.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Block\Adminhtml\Customform\Edit;



use Magento\Backend\Block\Widget\Context;



abstract class GenericButton

{



    protected $context;



    /**

     * @param \Magento\Backend\Block\Widget\Context $context

     */

    public function __construct(Context $context)

    {

        $this->context = $context;

    }



    /**

     * Return model ID

     *

     * @return int|null

     */

    public function getModelId()

    {

        return $this->context->getRequest()->getParam('id');

    }



    /**

     * Generate url by route and parameters

     *

     * @param   string $route

     * @param   array $params

     * @return  string

     */

    public function getUrl($route = '', $params = [])

    {

        return $this->context->getUrlBuilder()->getUrl($route, $params);

    }

}

41.app/code/Magelearn/Customform/Block/Adminhtml/Customform/Edit/DeleteButton.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Block\Adminhtml\Customform\Edit;



use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;



class DeleteButton extends GenericButton implements ButtonProviderInterface

{



    /**

     * @return array

     */

    public function getButtonData()

    {

        $data = [];

        if ($this->getModelId()) {

            $data = [

                'label' => __('Delete Customform'),

                'class' => 'delete',

                'on_click' => 'deleteConfirm(\'' . __(

                    'Are you sure you want to do this?'

                ) . '\', \'' . $this->getDeleteUrl() . '\')',

                'sort_order' => 20,

            ];

        }

        return $data;

    }



    /**

     * Get URL for delete button

     *

     * @return string

     */

    public function getDeleteUrl()

    {

        return $this->getUrl('*/*/delete', ['id' => $this->getModelId()]);

    }

}



42.app/code/Magelearn/Customform/Block/Adminhtml/Customform/Edit/ResetButton.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Block\Adminhtml\Customform\Edit;



use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;



class ResetButton extends GenericButton implements ButtonProviderInterface

{



    /**

     * @return array

     */

    public function getButtonData()

    {

        return [

            'label' => __('Reset'),

            'class' => 'reset',

            'on_click' => 'location.reload();',

            'sort_order' => 30

        ];

    }

}



43.app/code/Magelearn/Customform/Block/Adminhtml/Customform/Edit/SaveButton.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Block\Adminhtml\Customform\Edit;



use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;



class SaveButton extends GenericButton implements ButtonProviderInterface

{



    /**

     * @return array

     */

    public function getButtonData()

    {

        return [

            'label' => __('Save Customform'),

            'class' => 'save primary',

            'data_attribute' => [

                'mage-init' => ['button' => ['event' => 'save']],

                'form-role' => 'save',

            ],

            'sort_order' => 90,

        ];

    }

}



44.app/code/Magelearn/Block/Adminhtml/Customform/Edit/SaveAndContinueButton.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Block\Adminhtml\Customform\Edit;



use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;



class SaveAndContinueButton extends GenericButton implements ButtonProviderInterface

{



    /**

     * @return array

     */

    public function getButtonData()

    {

        return [

            'label' => __('Save and Continue Edit'),

            'class' => 'save',

            'data_attribute' => [

                'mage-init' => [

                    'button' => ['event' => 'saveAndContinueEdit'],

                ],

            ],

            'sort_order' => 80,

        ];

    }

}



45.app/code/Magelearn/Ui/Component/Listing/Column/CustomformActions.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Ui\Component\Listing\Column;



class CustomformActions extends \Magento\Ui\Component\Listing\Columns\Column

{



    const URL_PATH_EDIT = 'mladmincustomform/customform/edit';

    const URL_PATH_DELETE = 'mladmincustomform/customform/delete';

    protected $urlBuilder;

    const URL_PATH_DETAILS = 'mladmincustomform/customform/details';



    /**

     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context

     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory

     * @param \Magento\Framework\UrlInterface $urlBuilder

     * @param array $components

     * @param array $data

     */

    public function __construct(

        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,

        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,

        \Magento\Framework\UrlInterface $urlBuilder,

        array $components = [],

        array $data = []

    ) {

        $this->urlBuilder = $urlBuilder;

        parent::__construct($context, $uiComponentFactory, $components, $data);

    }



    /**

     * Prepare Data Source

     *

     * @param array $dataSource

     * @return array

     */

    public function prepareDataSource(array $dataSource)

    {

        if (isset($dataSource['data']['items'])) {

            foreach ($dataSource['data']['items'] as & $item) {

                if (isset($item['id'])) {

                    $item[$this->getData('name')] = [

                        'edit' => [

                            'href' => $this->urlBuilder->getUrl(

                                static::URL_PATH_EDIT,

                                [

                                    'id' => $item['id']

                                ]

                            ),

                            'label' => __('Edit')

                        ],

                        'delete' => [

                            'href' => $this->urlBuilder->getUrl(

                                static::URL_PATH_DELETE,

                                [

                                    'id' => $item['id']

                                ]

                            ),

                            'label' => __('Delete'),

                            'confirm' => [

                                'title' => __('Delete %1',$item['id']),

                                'message' => __('Are you sure you wan\'t to delete a %1 record ?',$item['id'])

                            ]

                        ]

                    ];

                }

            }

        }



        return $dataSource;

    }

}



46.app/code/Magelearn/Controller/Adminhtml/Customform/Delete.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



class Delete extends \Magelearn\Customform\Controller\Adminhtml\Customform

{



    /**

     * Delete action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $resultRedirect = $this->resultRedirectFactory->create();

        // check if we know what should be deleted

        $id = $this->getRequest()->getParam('id');

        if ($id) {

            try {

                // init model and delete

                $model = $this->_objectManager->create(\Magelearn\Customform\Model\Customform::class);

                $model->load($id);

                $model->delete();

                // display success message

                $this->messageManager->addSuccessMessage(__('You deleted the Customform.'));

                // go to grid

                return $resultRedirect->setPath('*/*/');

            } catch (\Exception $e) {

                // display error message

                $this->messageManager->addErrorMessage($e->getMessage());

                // go back to edit form

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);

            }

        }

        // display error message

        $this->messageManager->addErrorMessage(__('We can\'t find a Customform to delete.'));

        // go to grid

        return $resultRedirect->setPath('*/*/');

    }

}



47.app/code/Magelearn/Controller/Adminhtml/Customform/InlineEdit.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



class InlineEdit extends \Magento\Backend\App\Action

{



    protected $jsonFactory;



    /**

     * @param \Magento\Backend\App\Action\Context $context

     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory

     */

    public function __construct(

        \Magento\Backend\App\Action\Context $context,

        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory

    ) {

        parent::__construct($context);

        $this->jsonFactory = $jsonFactory;

    }



    /**

     * Inline edit action

     *

     * @return \Magento\Framework\Controller\ResultInterface

     */

    public function execute()

    {

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */

        $resultJson = $this->jsonFactory->create();

        $error = false;

        $messages = [];



        if ($this->getRequest()->getParam('isAjax')) {

            $postItems = $this->getRequest()->getParam('items', []);

            if (!count($postItems)) {

                $messages[] = __('Please correct the data sent.');

                $error = true;

            } else {

                foreach (array_keys($postItems) as $modelid) {

                    /** @var \Magelearn\Customform\Model\Customform $model */

                    $model = $this->_objectManager->create(\Magelearn\Customform\Model\Customform::class)->load($modelid);

                    try {

                        $model->setData(array_merge($model->getData(), $postItems[$modelid]));

                        $model->save();

                    } catch (\Exception $e) {

                        $messages[] = "[Customform ID: {$modelid}]  {$e->getMessage()}";

                        $error = true;

                    }

                }

            }

        }



        return $resultJson->setData([

            'messages' => $messages,

            'error' => $error

        ]);

    }

}



48.app/code/Magelearn/Controller/Adminhtml/Customform/MassDelete.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



use Magento\Framework\Controller\ResultFactory;

use Magento\Backend\App\Action\Context;

use Magento\Ui\Component\MassAction\Filter;

use Magelearn\Customform\Model\ResourceModel\Customform\CollectionFactory;



/**

 * Class MassDelete

 * @package Magelearn\Customform\Controller\Adminhtml\Customform

 */



class MassDelete extends \Magento\Backend\App\Action

{

    /**

     * @var Filter

     */

    protected $filter;

    /**

     * @var CollectionFactory

     */

    protected $collectionFactory;



    /**

     * @param Context $context

     * @param Filter $filter

     * @param CollectionFactory $collectionFactory

     */

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)

    {

        $this->filter = $filter;

        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);

    }

    /**

     * Execute action

     *

     * @return \Magento\Backend\Model\View\Result\Redirect

     * @throws \Magento\Framework\Exception\LocalizedException|\Exception

     */

    public function execute()

    {

        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $collectionSize = $collection->getSize();



        foreach ($collection as $page) {

            $page->delete();

        }

        $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $collectionSize));

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');

    }

}



49.app/code/Magelearn/Controller/Adminhtml/Customform/MassDisable.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



use Magento\Framework\App\Action\HttpPostActionInterface;

use Magento\Framework\Controller\ResultFactory;

use Magento\Backend\App\Action\Context;

use Magento\Ui\Component\MassAction\Filter;

use Magelearn\Customform\Model\ResourceModel\Customform\CollectionFactory;



/**

 * Class MassDisable

 */

class MassDisable extends \Magento\Backend\App\Action implements HttpPostActionInterface

{

    /**

     * Authorization level of a basic admin session

     *

     * @see _isAllowed()

     */

    const ADMIN_RESOURCE = 'Magelearn_Customform::Customform_save';



    /**

     * @var Filter

     */

    protected $filter;



    /**

     * @var CollectionFactory

     */

    protected $collectionFactory;



    /**

     * @param Context $context

     * @param Filter $filter

     * @param CollectionFactory $collectionFactory

     */

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)

    {

        $this->filter = $filter;

        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);

    }



    /**

     * Execute action

     *

     * @return \Magento\Backend\Model\View\Result\Redirect

     * @throws \Magento\Framework\Exception\LocalizedException|\Exception

     */

    public function execute()

    {

        $collection = $this->filter->getCollection($this->collectionFactory->create());



        foreach ($collection as $item) {

            $item->setStatus(false);

            $item->save();

        }



        $this->messageManager->addSuccessMessage(

            __('A total of %1 record(s) have been disabled.', $collection->getSize())

        );



        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');

    }

}



50.app/code/Magelearn/Controller/Adminhtml/Customform/MassEnable.php



<?php

declare(strict_types=1);



namespace Magelearn\Customform\Controller\Adminhtml\Customform;



use Magento\Framework\App\Action\HttpPostActionInterface;

use Magento\Framework\Controller\ResultFactory;

use Magento\Backend\App\Action\Context;

use Magento\Ui\Component\MassAction\Filter;

use Magelearn\Customform\Model\ResourceModel\Customform\CollectionFactory;



/**

 * Class MassEnable

 */

class MassEnable extends \Magento\Backend\App\Action implements HttpPostActionInterface

{

    /**

     * Authorization level of a basic admin session

     *

     * @see _isAllowed()

     */

    const ADMIN_RESOURCE = 'Magelearn_Customform::Customform_save';



    /**

     * @var Filter

     */

    protected $filter;



    /**

     * @var CollectionFactory

     */

    protected $collectionFactory;



    /**

     * @param Context $context

     * @param Filter $filter

     * @param CollectionFactory $collectionFactory

     */

    public function __construct(Context $context, Filter $filter, CollectionFactory $collectionFactory)

    {

        $this->filter = $filter;

        $this->collectionFactory = $collectionFactory;

        parent::__construct($context);

    }



    /**

     * Execute action

     *

     * @return \Magento\Backend\Model\View\Result\Redirect

     * @throws \Magento\Framework\Exception\LocalizedException|\Exception

     */

    public function execute()

    {

        $collection = $this->filter->getCollection($this->collectionFactory->create());



        foreach ($collection as $item) {

            $item->setStatus(true);

            $item->save();

        }



        $this->messageManager->addSuccessMessage(

            __('A total of %1 record(s) have been enabled.', $collection->getSize())

        );



        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        return $resultRedirect->setPath('*/*/');

    }

}



51.app/code/Magelearn/view/frontend/layout/customform_index_list.xml



<?xml version="1.0"?>

<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    <body>

        <referenceContainer name="content">

            <block class="Magelearn\Customform\Block\CustomformListData" name="customform.listdata" template="Magelearn_Customform::list.phtml" cacheable="false">

            </block>

        </referenceContainer>

    </body>

</page>



52.app/code/Magelearn/Controller/Index/ListAction.php



<?php



namespace Magelearn\Customform\Controller\Index;



class ListAction extends \Magento\Framework\App\Action\Action

{

    public function execute()

    {

        $this->_view->loadLayout();

        $this->_view->getLayout()->initMessages();

        $this->_view->renderLayout();

    }

}



53.app/code/Magelearn/Block/CustomformListData.php



<?php



namespace Magelearn\Customform\Block;



use Magento\Framework\View\Element\Template\Context;

use Magelearn\Customform\Model\CustomformFactory;

/**

 * Customform List block

 */

class CustomformListData extends \Magento\Framework\View\Element\Template

{

    /**

     * @var Customform

     */

    protected $_customform;

    public function __construct(

        Context $context,

        CustomformFactory $customform

    ) {

        $this->_customform = $customform;

        parent::__construct($context);

    }



    public function _prepareLayout()

    {

        $this->pageConfig->getTitle()->set(__('Magelearn Customform Module List Page'));



        if ($this->getCustomformCollection()) {

            $pager = $this->getLayout()->createBlock(

                'Magento\Theme\Block\Html\Pager',

                'magelearn.customform.pager'

            )->setAvailableLimit(array(5=>5,10=>10,15=>15))->setShowPerPage(true)->setCollection(

                $this->getCustomformCollection()

            );

            $this->setChild('pager', $pager);

            $this->getCustomformCollection()->load();

        }

        return parent::_prepareLayout();

    }



    public function getCustomformCollection()

    {

        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;

        $pageSize = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : 5;



        $customform = $this->_customform->create();

        $collection = $customform->getCollection();

        $collection->addFieldToFilter('status','1');

        //$customform->setOrder('id','ASC');

        $collection->setPageSize($pageSize);

        $collection->setCurPage($page);



        return $collection;

    }



    public function getPagerHtml()

    {

        return $this->getChildHtml('pager');

    }

}



54.app/code/Magelearn/view/frontend/templates/list.phtml



<?php $collection = $block->getCustomformCollection(); ?>

<?php if($collection->count() > 0): ?>

    <?php if ($block->getPagerHtml()): ?>

        <div class="order-products-toolbar toolbar bottom"><?php echo $block->getPagerHtml(); ?></div>

    <?php endif ?>

    <table class="data table" id="customform-data-table">

        <caption class="table-caption">Magelearn Customform Data</caption>

        <thead>

            <tr>

                <th scope="col" class="col first_name">First Name</th>

                <th scope="col" class="col last_name">Last Name</th>

                <th scope="col" class="col email">Email</th>

                <th scope="col" class="col image">Image</th>

                <th scope="col" class="col phone">Phone</th>

                <th scope="col" class="col message">Message</th>

                <th scope="col" class="col status">Status</th>

                <th scope="col" class="col created_at">Created At</th>

                <th scope="col" class="col actions">Action</th>

            </tr>

        </thead>

        <tbody>

            <?php foreach ($collection as $key => $data): ?>

                <tr>

                    <td data-th="first_name" class="col first_name"><?php echo $data->getFirstName(); ?></td>

                    <td data-th="last_name" class="col last_name"><?php echo $data->getLastName(); ?></td>

                    <td data-th="Email" class="col email"><?php echo $data->getEmail(); ?></td>

                    <td data-th="Image" class="col image">

                        <?php if($data->getImage()): ?>

                            <img width="70" height="70" src="<?php echo $block->getBaseUrl().'pub/media/magelearn/customform/'.$data->getImage(); ?>">

                        <?php else: ?>

                            <p>No Image</p>

                        <?php endif; ?>

                    </td>

                    <td data-th="Phone" class="col phone"><?php echo $data->getPhone(); ?></td>

                    <td data-th="Message" class="col message"><?php echo $data->getMessage(); ?></td>

                    <td data-th="Status" class="col status">

                        <?php if($data->getStatus() == 1): ?>

                            <p>Enable</p>

                        <?php else: ?>

                            <p>Disable</p>

                        <?php endif; ?>

                    </td>

                    <td data-th="Date" class="col created_at"><?php echo $data->getCreatedAt(); ?></td>

                    <td data-th="Actions" class="col actions">

                        <?php $id = ($data->getId()) ? $data->getId() : $data['id']; ?>

                        <!-- "$data->getTesting13Id()" was not working when I generated Vky_Testing13 module. That's why I had to place above line. -->

                        <a href="<?php echo $block->getBaseUrl().'customform/index/view/id/'.$id; ?>" class="action view">

                            <span>View</span>

                        </a>

                    </td>

                </tr>

            <?php endforeach; ?>

        </tbody>

    </table>

    <?php if ($block->getPagerHtml()): ?>

        <div class="order-products-toolbar toolbar bottom"><?php echo $block->getPagerHtml(); ?></div>

    <?php endif ?>

<?php else: ?>

    <h2>We couldn't find any records</h2>

<?php endif; ?>



55.app/code/Magelearn/view/frontend/layout/customform_index_view.xml



<?xml version="1.0"?>

<page xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" layout="1column" xsi:noNamespaceSchemaLocation="urn:magento:framework:View/Layout/etc/page_configuration.xsd">

    <body>

        <referenceContainer name="content">

            <block class="Magelearn\Customform\Block\CustomformView" name="customform.view" template="Magelearn_Customform::view.phtml" cacheable="false">

            </block>

        </referenceContainer>

    </body>

</page>



56.app/code/Magelearn/Controller/Index/View.php



<?php



namespace Magelearn\Customform\Controller\Index;



use Magento\Framework\App\Action\Context;

use Magento\Framework\Exception\NotFoundException;

use Magelearn\Customform\Block\CustomformView;



class View extends \Magento\Framework\App\Action\Action

{

    protected $_customformview;



    public function __construct(

        Context $context,

        CustomformView $customformview

    ) {

        $this->_customformview = $customformview;

        parent::__construct($context);

    }



    public function execute()

    {

        if(!$this->_customformview->getSingleData()){

            throw new NotFoundException(__('Parameter is incorrect.'));

        }



        $this->_view->loadLayout();

        $this->_view->getLayout()->initMessages();

        $this->_view->renderLayout();

    }

}



57.app/code/Magelearn/Block/CustomformView.php

<?php



namespace Magelearn\Customform\Block;



use Magento\Framework\View\Element\Template\Context;

use Magelearn\Customform\Model\CustomformFactory;

use Magento\Cms\Model\Template\FilterProvider;

/**

 * Customform View block

 */

class CustomformView extends \Magento\Framework\View\Element\Template

{

    /**

     * @var Customform

     */

    protected $_customform;

    public function __construct(

        Context $context,

        CustomformFactory $customform,

        FilterProvider $filterProvider

    ) {

        $this->_customform = $customform;

        $this->_filterProvider = $filterProvider;

        parent::__construct($context);

    }



    public function _prepareLayout()

    {

        $this->pageConfig->getTitle()->set(__('Magelearn Customform Module View Page'));



        return parent::_prepareLayout();

    }



    public function getSingleData()

    {

        $id = $this->getRequest()->getParam('id');

        $customform = $this->_customform->create();

        $singleData = $customform->load($id);

        if($singleData->getId() || $singleData['id'] && $singleData->getStatus() == 1){

            return $singleData;

        }else{

            return false;

        }

    }

}

58.app/code/Magelearn/view/frontend/templates/view.phtml



<?php

    $singleData = $block->getSingleData();

?>

<div>

    <h3>First Name</h3>

    <p><?php echo $singleData->getFirstName(); ?></p>

</div>

<div>

    <h3>Last Name</h3>

    <p><?php echo $singleData->getLastName(); ?></p>

</div>

<div>

    <h3>Email</h3>

    <p><?php echo $singleData->getEmail(); ?></p>

</div>

<div>

    <h3>Message</h3>

    <p><?php echo $this->_filterProvider->getPageFilter()->filter($singleData->getMessage()); ?></p>

</div>

<div>

    <h3>Image</h3>

    <?php if($singleData->getImage()): ?>

        <img width="400" height="300" src="<?php echo $block->getBaseUrl().'pub/media/magelearn/customform'.$singleData->getImage(); ?>">

    <?php else: ?>

        <p>No Image</p>

    <?php endif; ?>

</div>

<div>

    <h3>Phone</h3>

    <p><?php echo $singleData->getPhone(); ?></p>

</div>

<div>

    <h3>Date</h3>

    <p><?php echo $singleData->getCreatedAt(); ?></p>

</div>



59.app/code/Magelearn/etc/frontend/routes.xml



<?xml version="1.0" encoding="UTF-8"?>

<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:App/etc/routes.xsd">

    <router id="standard">

        <route id="customform" frontName="customform">

            <module name="Magelearn_Customform" />

        </route>

    </router>

</config>



Note:- routes.xml created frontend url like frontName/controllerName/action

***********************
Text type,Varchar type,int type,float type db_schema Detail..
