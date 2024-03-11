<?php

// src/Menu/Builder.php
namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;


final class MenuBuilder
{

    public function __construct(private readonly FactoryInterface $factory)
    {

    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'app_home']);

        return $menu;
    }
}
