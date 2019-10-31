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
    private const Visible = 1;
    private const NotVisible = 0;
    private const Required = 1;
    private const NotRequired = 0;
    private const IsHidden = 0;
    private const IsOptional = 1;
    private const IsRequired = 2;

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
        if ($linkedinParamValue == self::IsHidden) {
            $linkedinProfileAttribute->setIsVisible(self::NotVisible)
                ->setIsRequired(self::NotRequired);
            $this->attributeRepository->save($linkedinProfileAttribute);
        } elseif ($linkedinParamValue == self::IsOptional) {
            $linkedinProfileAttribute->setIsVisible(self::Visible)
                ->setIsRequired(self::NotRequired);
            $this->attributeRepository->save($linkedinProfileAttribute);
        } elseif ($linkedinParamValue == self::IsRequired) {
            $linkedinProfileAttribute->setIsVisible(self::Visible)
                ->setIsRequired(self::Required);
            $this->attributeRepository->save($linkedinProfileAttribute);
        }
        return $this;
    }
}
