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

    public function __construct(
        RequestInterface $request,
        AttributeRepository $attributeRepository
    ) {
        $this->request = $request;
        $this->attributeRepository = $attributeRepository;
    }

    public function execute(Observer $observer)
    {
        $linkedinProfileAttribute = $this->attributeRepository->get('customer', 'linkedin_profile');
        $requestParams = $this->request->getParam('groups');
        $linkedinParamValue = $requestParams['account_information']['fields']['linkedin_profile']['value'];
        if ($linkedinParamValue == 0) {
            $linkedinProfileAttribute->setIsVisible(0)
                ->setIsRequired(0);
            $this->attributeRepository->save($linkedinProfileAttribute);
        } elseif ($linkedinParamValue == 1) {
            $linkedinProfileAttribute->setIsVisible(1)
                ->setIsRequired(0);
            $this->attributeRepository->save($linkedinProfileAttribute);
        } elseif ($linkedinParamValue == 2) {
            $linkedinProfileAttribute->setIsVisible(1)
                ->setIsRequired(1);
            $this->attributeRepository->save($linkedinProfileAttribute);
        }
        return $this;
    }
}
