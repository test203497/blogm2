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
public function save(\Magelearn\Customform\Api\Data\CustomformInterface $customform);
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
public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
/**
* Delete Customform
* @param \Magelearn\Customform\Api\Data\CustomformInterface $customform
* @return bool true on success
* @throws \Magento\Framework\Exception\LocalizedException
*/
public function delete(\Magelearn\Customform\Api\Data\CustomformInterface $customform);
/**
* Delete Customform by ID
* @param string $customformId
* @return bool true on success
* @throws \Magento\Framework\Exception\NoSuchEntityException
* @throws \Magento\Framework\Exception\LocalizedException
*/
public function deleteById($customformId);
}
//!--**********************************----->

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
//*************************************************//
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
