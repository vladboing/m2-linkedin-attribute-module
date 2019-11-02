<?php declare(strict_types=1);

namespace Elogic\Linkedin\Model\Observer;

use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ConfigChange implements ObserverInterface
{
    private $request;
    private $attributeRepository;
    private const VISIBLE = 1;
    private const NOT_VISIBLE = 0;
    private const REQUIRED = 1;
    private const NOT_REQUIRED = 0;
    private const IS_HIDDEN = 0;
    private const IS_OPTIONAL = 1;
    private const IS_REQUIRED = 2;

    /**
     * ConfigChange constructor.
     * @param RequestInterface $request
     * @param AttributeRepository $attributeRepository
     */
    public function __construct(
        RequestInterface $request,
        AttributeRepository $attributeRepository
    ) {
        $this->request = $request;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * Observer execute() method.
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function execute(Observer $observer)
    {
        $linkedinProfileAttribute = $this->attributeRepository->get('customer', 'linkedin_profile');
        $linkedinParamValue = $this->request->getParam('groups')['account_information']['fields']['linkedin_profile']['value'];
        if ($linkedinParamValue == self::IS_HIDDEN) {
            $linkedinProfileAttribute->setIsVisible(self::NOT_VISIBLE)
                ->setIsRequired(self::NOT_REQUIRED);
            $this->attributeRepository->save($linkedinProfileAttribute);
        } elseif ($linkedinParamValue == self::IS_OPTIONAL) {
            $linkedinProfileAttribute->setIsVisible(self::VISIBLE)
                ->setIsRequired(self::NOT_REQUIRED);
            $this->attributeRepository->save($linkedinProfileAttribute);
        } elseif ($linkedinParamValue == self::IS_REQUIRED) {
            $linkedinProfileAttribute->setIsVisible(self::VISIBLE)
                ->setIsRequired(self::REQUIRED);
            $this->attributeRepository->save($linkedinProfileAttribute);
        }
        return $this;
    }
}
