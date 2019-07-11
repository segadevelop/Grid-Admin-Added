<?php
/**
 * Global Functional To Work Extension
 *
 * Sega_Dev >>>>>>>>>>> Amasty Extension Work Enterprise
 *
 * Model Logic:
 *
 */

namespace Sega\Extension\Model;

/**
 * All Resource Class Libs To Work Model Resource
 */
use Magento\Catalog\Api\ProductRepositoryInterface,
    Magento\Framework\Data\Form\FormKey,
    Magento\Checkout\Model\Cart,
    Magento\Framework\View\Result\PageFactory,
    Magento\Framework\Controller\Result\JsonFactory,
    Magento\Framework\App\Request\Http,
    Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollection,
    Magento\Checkout\Controller\Cart\Add,
    Magento\Framework\App\Config\ScopeConfigInterface,
    Magento\Framework\Exception\NoSuchEntityException,
    Magento\Framework\DataObject\IdentityInterface,
    Magento\Framework\Model\AbstractModel,
    Magento\Framework\Model\ResourceModel\Db\AbstractDb,
    Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection,
    Magento\Framework\File\Csv;

use Magento\Framework\Data\CollectionFactory as DataCollection;

/**
 * Class Product
 * @package Sega\Extension\Model
 */
class Product
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var Http
     */
    private $request;

    /**
     * @var FormKey
     */
    private $formKey;

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * @var CollectionFactory
     */
    private $productCollection;

    /**
     * @var PageFactory
     */
    private $pageFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        FormKey $formKey,
        Cart $cart,
        Http $request,
        JsonFactory $jsonFactory,
        ProductCollection $productCollection,
        PageFactory $pageFactory
    ) {
        $this->productRepository = $productRepository;
        $this->formKey = $formKey;
        $this->cart = $cart;
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
        $this->productCollection = $productCollection;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @param $productObj
     * @param $params
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addCustomProduct($product, $params)
    {
        $this->cart->addProduct($product, $params);
        $this->cart->save();
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductBySku()
    {
        $request = $this->request->getMethod();
        switch ($request)
        {
            case 'POST':
                $sku = $this->request->getParam('search_sku');
                    try {
                        $product = $this->productRepository->get($sku);
                        $productId = $product->getId();
                        $params = [
                            'form_key'     => $this->formKey->getFormKey(),
                            'product_id'   => $productId,
                            'qty'          => 1
                        ];
                        $this->addCustomProduct($product, $params);
                    }
                    catch (\Exception $e){
                        return 'Error';
                    }
                break;
            default:
            case 'GET':
                //
                break;
        }
    }

    /**
     * @param $sku
     * @return array
     */
    public function findBySKU($sku)
    {
        $array = [];
        $productCollection = $this->productCollection->create()
            ->addFieldToFilter('sku', ['like' => '%' . $sku . '%'])
            ->setPageSize(15)
            ->setCurPage(1);

        foreach ($productCollection as $product ) {
            $array[$product->getSku()] = $product->getSku();
        }

        return $array;
    }

    public function addCsvListProduct()
    {
        //
    }
}
