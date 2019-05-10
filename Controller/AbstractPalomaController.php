<?php

namespace Paloma\ShopBundle\Controller;

use Paloma\Shop\Catalog\CatalogInterface;
use Paloma\Shop\Catalog\CategoryInterface;
use Paloma\Shop\Catalog\CategoryReferenceInterface;
use Paloma\Shop\Catalog\ProductInterface;
use Paloma\Shop\Catalog\SearchRequest;
use Paloma\Shop\Checkout\CheckoutInterface;
use Paloma\ShopBundle\PalomaSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractPalomaController extends AbstractController
{
    protected $catalog;

    protected $checkout;

    protected $serializer;

    public function __construct(CatalogInterface $catalog, CheckoutInterface $checkout, PalomaSerializer $serializer)
    {
        $this->catalog = $catalog;
        $this->checkout = $checkout;
        $this->serializer = $serializer;
    }

    protected function search(Request $request, CategoryInterface $category = null)
    {
        $searchRequest = $this->searchRequest($request, $category);

        $searchResults = $this->catalog->search($searchRequest);

        // TODO proper model
        $sort = [
            'options' => [
                'position' => [
                    'property' => 'position',
                    'desc' => false,
                    'selected' => $searchRequest->getSort() === 'position',
                ],
                'relevance' => [
                    'property' => 'relevance',
                    'desc' => false,
                    'selected' => $searchRequest->getSort() === 'relevance',
                ],
                'price_asc' => [
                    'property' => 'price',
                    'desc' => false,
                    'selected' => $searchRequest->getSort() === 'price' && !$searchRequest->isOrderDesc(),
                ],
                'price_desc' => [
                    'property' => 'price',
                    'desc' => true,
                    'selected' => $searchRequest->getSort() === 'price' && $searchRequest->isOrderDesc(),
                ]
            ]
        ];

        if ($category) {
            unset($sort['options']['relevance']);
        } else {
            unset($sort['options']['position']);
        }

        foreach ($sort['options'] as $name => $option) {
            if ($option['selected']) {
                $sort['current'] = $name;
            }
        }

        return [
            'sort' => $sort,
            // TODO proper model / get from results
            'filters' => [

            ],
            'request' => $searchRequest,
            'results' => $searchResults,
        ];
    }

    protected function searchRequest(Request $request, CategoryInterface $category = null)
    {
        return new SearchRequest(
            $category ? $category->getCode() : null,
            $request->get('query'),
            [],
            true,
            (int)$request->get('page', 0),
            (int)$request->get('size', 12),
            $request->get('sort', $category ? 'position' : 'relevance'),
            $request->get('desc', false)
        );
    }

    protected function renderPalomaView($template, array $params)
    {
        return $this->render(
            '@PalomaShop/' . $template,
            $params + $this->layoutParams()
        );
    }

    protected function layoutParams()
    {
        $cart = $this->checkout->getCart();

        return [
            'main_categories' => $this->mainCategories(),
            'cart' => $cart,
        ];
    }

    protected function mainCategories()
    {
        return $this->catalog->getCategories(2);
    }

    protected function redirectToCategory(CategoryInterface $category, bool $permanent = false)
    {
        return $this->redirectToRoute('paloma_catalog_category', [
            'categorySlug' => $category->getSlug(),
            'categoryCode' => $category->getCode(),
        ],
            $permanent
                ? Response::HTTP_MOVED_PERMANENTLY
                : Response::HTTP_FOUND);
    }

    protected function redirectToProduct(ProductInterface $product, bool $permanent = false)
    {
        if (count($product->getCategories()) > 0) {
            return $this->redirectToProductAndCategory($product, $this->findBestProductCategory($product), $permanent);
        }

        return $this->redirectToRoute('paloma_catalog_product', [
            'productSlug' => $product->getSlug(),
            'itemNumber' => $product->getItemNumber(),
        ],
            $permanent
                ? Response::HTTP_MOVED_PERMANENTLY
                : Response::HTTP_FOUND);
    }

    protected function redirectToProductAndCategory(ProductInterface $product, CategoryReferenceInterface $category, bool $permanent = false)
    {
        return $this->redirectToRoute('paloma_catalog_category_product', [
            'categorySlug' => $category->getSlug(),
            'categoryCode' => $category->getCode(),
            'productSlug' => $product->getSlug(),
            'itemNumber' => $product->getItemNumber(),
        ],
            $permanent
                ? Response::HTTP_MOVED_PERMANENTLY
                : Response::HTTP_FOUND);
    }

    protected function findBestProductCategory(ProductInterface $product)
    {
        return $product->getCategories()[0]; // TODO
    }
}