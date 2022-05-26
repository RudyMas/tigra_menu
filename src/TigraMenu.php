<?php

namespace Tigra;

/**
 * Class TigraMenu (PHP version 7.4)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2022, rmsoft.be. (https://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     7.4.1.0
 * @package     Tigra
 */
class TigraMenu
{
    private array $menuData;

    /**
     * TigraMenu constructor.
     * @param array $menuData
     */
    public function __construct(array $menuData)
    {
        $this->menuData = $menuData;
    }

    /**
     * @param string $id
     * @param string $class
     * @return string
     */
    public function createMenu(string $id, string $class): string
    {
        $output = '<nav id=' . $id . '>';
        $output .= '<div id="mainMenu">';
        $output .= $this->createMainMenu([], [], '', $class);
        $output .= '</div>';
        $output .= '</nav>';
        return $output;
    }

    /**
     * @param array $arrayMenu
     * @param array $args
     * @param string $id
     * @param string $class
     * @return string
     */
    private function createMainMenu(
        array $arrayMenu = [],
        array $args = [],
        string $id = '',
        string $class = ''
    ): string {
        $numberArgs = count($args);
        if (empty($arrayMenu)) {
            $arrayMenu = $this->menuData;
        }

        $output = '';
        $remove = ['none', 'None', 'NONE'];

        $arrayKey = array_keys($arrayMenu);
        $arrayKey = array_diff($arrayKey, $remove);
        $arrayKey = array_values($arrayKey);
        $arrayCount = count($arrayKey);

        $output .= '<ul';
        if ($id <> '') {
            $output .= ' id="' . $id . '"';
        }
        if ($class <> '') {
            $output .= ' class="' . $class . '"';
        }
        $output .= '>';

        if (isset($_SESSION['numberOfMenuItems']) && $_SESSION['numberOfMenuItems'] < $arrayCount) {
            $numberExtraMenus = ceil($arrayCount / $_SESSION['numberOfMenuItems']);
            for ($x = 0; $x < $numberExtraMenus; $x++) {
                $output .= '<li>';
                $output .= '<a class="extraMenu">&lt;' . ($x + 1) . '&gt;</a>';
                $output .= '<ul class="extraMenu">';
                for ($y = $x * $_SESSION['numberOfMenuItems']; $y < ($_SESSION['numberOfMenuItems'] * $x) + $_SESSION['numberOfMenuItems'] && $y < $arrayCount; $y++) {
                    $menuItem = $arrayKey[$y];
                    $this->createSubMenu($args, $output, $numberArgs, $menuItem, $arrayMenu, $remove);
                }
                $output .= '</ul>';
                $output .= '</li>';
            }
        } else {
            foreach ($arrayKey as $menuItem) {
                $this->createSubMenu($args, $output, $numberArgs, $menuItem, $arrayMenu, $remove);
            }
        }

        $output .= '</ul>';
        return $output;
    }

    /**
     * @param array $args
     * @param string $output
     * @param int $numberArgs
     * @param string $menuItem
     * @param array $arrayMenu
     * @param array $remove
     */
    private function createSubMenu(
        array &$args,
        string &$output,
        int $numberArgs,
        string $menuItem,
        array $arrayMenu,
        array $remove
    ) {
        $args[$numberArgs] = $menuItem;
        $output .= '<li>';
        list($linkOutput, $linkClass) = $this->createMenuLink($args);
        $output .= $linkOutput;
        if (is_array($arrayMenu[$menuItem])) {
            $arrayKeyChild = array_keys($arrayMenu[$menuItem]);
            $arrayKeyChild = array_diff($arrayKeyChild, $remove);
            $arrayCountChild = count($arrayKeyChild);
            if ($arrayCountChild > 0) {
                $output .= $this->createMainMenu($arrayMenu[$menuItem], $args, '', $linkClass);
            }
        }
        $output .= '</li>';
    }

    /**
     * @param array $args
     * @return array
     */
    private function createMenuLink(array $args): array
    {
        $arguments = Menu::getIndexes($args);
        $url = $this->menuData[$arguments[0]][$arguments[1]][$arguments[2]][$arguments[3]][$arguments[4]][$arguments[5]][$arguments[6]][$arguments[7]][$arguments[8]][$arguments[9]]['url'];
        $text = $this->menuData[$arguments[0]][$arguments[1]][$arguments[2]][$arguments[3]][$arguments[4]][$arguments[5]][$arguments[6]][$arguments[7]][$arguments[8]][$arguments[9]]['text'];
        $class = $this->menuData[$arguments[0]][$arguments[1]][$arguments[2]][$arguments[3]][$arguments[4]][$arguments[5]][$arguments[6]][$arguments[7]][$arguments[8]][$arguments[9]]['class'];

        $output = '<a href="' . BASE_URL . $url . '"';
        if ($class <> '') {
            $output .= ' class="' . $class . '"';
        }
        $output .= '>' . $text . '</a>';

        return [$output, $class];
    }
}
