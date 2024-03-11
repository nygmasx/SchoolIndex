<?php

/*
 * This file is part of MentalWorks private bundle.
 *
 * (c) MentalWorks <contact@mentalworks.fr>
 */

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class BaseBuilder
{
    protected readonly ?Request $request;

    public function __construct(
        protected readonly FactoryInterface $factory,
        protected readonly TranslatorInterface $translator,
        protected readonly Security $security,
        RequestStack $requestStack
    ) {
        $this->request = $requestStack->getCurrentRequest();
    }

    public function createItem(
        string $name = 'root',
        array $options = [],
        bool $translateLabel = true,
        string $translationDomain = null,
        array $translationParameters = []
    ): ItemInterface {
        if ($translateLabel && isset($options['label'])) {
            $options['label'] = $this->translator->trans($options['label'], $translationParameters, $translationDomain);
        }

        return $this->factory->createItem($name, $options);
    }

    public function addItem(
        ItemInterface $menuItem,
        string $label,
        string $route = null,
        string $icon = null,
        array $routeParameters = [],
        string $role = null,
        bool $translateLabel = true,
        string $translationDomain = null,
        array $translationParameters = []
    ): ?ItemInterface {
        if (null !== $role && !$this->security->isGranted($role)) {
            return null;
        }

        $routeSetting = ['route' => $route];

        if (!empty($routeParameters)) {
            $routeSetting['routeParameters'] = $routeParameters;
        }

        if ($translateLabel) {
            $label = $this->translator->trans($label, $translationParameters, $translationDomain);
        }

        $item = $menuItem->addChild($label, $routeSetting);
        $item->setExtra('translation_domain', false);

        if (!empty($icon)) {
            $item->setExtra('icon', $icon);
        }

        if ($menuItem->isRoot()) {
            $item->setExtra('title', true);
        }

        return $item;
    }

    public function addAdminItem(
        ItemInterface $menuItem,
        string $label,
        string $prefix = null,
        string $route = null,
        string $icon = null,
        array $routeParameters = [],
        ?string $role = 'ROLE_ADMIN',
        bool $translateLabel = true,
        ?string $translationDomain = 'admin',
        array $translationParameters = [],
        bool $matchUri = false,
        bool $strictMatch = false
    ): ?ItemInterface {
        $item = $this->addItem($menuItem, $label, $route, $icon, $routeParameters, $role, $translateLabel, $translationDomain, $translationParameters);

        if ($matchUri) {
            $this->setCurrentItemByUri($item, $strictMatch);
        } elseif (null !== $prefix) {
            $this->setCurrentItem($prefix, $item);
        }

        return $item;
    }

    public function setCurrentItem(?string $prefix, ?ItemInterface $menuItem): ?ItemInterface
    {
        if (null === $menuItem) {
            return null;
        }

        $routeName = $this->request->get('_route');

        if (null !== $prefix && 0 === mb_strpos($routeName, $prefix)) {
            $menuItem->setCurrent(true);
        }

        return $menuItem;
    }
}
