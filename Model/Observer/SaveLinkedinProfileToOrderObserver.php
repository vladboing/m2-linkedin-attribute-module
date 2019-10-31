<?php declare(strict_types=1);

namespace Elogic\Linkedin\Model\Observer;

use Magento\Customer\Model\SessionFactory;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\QuoteRepositoryFactory;

class SaveLinkedinProfileToOrderObserver implements ObserverInterface
{
    protected $quoteRepository;
    protected $session;

    /**
     * SaveLinkedinProfileToOrderObserver constructor.
     * @param QuoteRepositoryFactory $quoteRepository
     * @param SessionFactory $session
     */
    public function __construct(
        QuoteRepositoryFactory $quoteRepository,
        SessionFactory $session
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->session = $session;
    }

    /**
     * Observer execute($observer) method.
     * @param Observer $observer
     * @return $this|void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $quote = $this->quoteRepository->create()->get($observer->getOrder()->getQuoteId());
        $observer->getOrder()->setLinkedinProfile($quote->getLinkedinProfile());
        $this->session->create()->setLinkedinProfile($observer->getOrder()->getLinkedinProfile());

        return $this;
    }
}
