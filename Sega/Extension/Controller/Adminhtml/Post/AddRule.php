<?php

namespace Sega\Extension\Contorller\Adminhtml\Post;

use Magento\Framework\App\Action\Context;
use Magento\Reports\Test\Block\Adminhtml\Sales\TaxRule\Action;

class AddRule extends Action
{
    /**
     * @var
     */
    protected $pageFactory;

    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }



}