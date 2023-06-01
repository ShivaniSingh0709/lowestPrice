<?php

namespace Shivani\LowestPrice\Controller\Adminhtml\Index;

class Cron extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $lowest;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Shivani\LowestPrice\Cron\Lowest $lowest,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        $this->lowest       = $lowest;
        return parent::__construct($context);
    }

    public function execute()
    {
        $this->lowest->execute();
        
        return $this->_redirect($this->_redirect->getRefererUrl());
    }

}
