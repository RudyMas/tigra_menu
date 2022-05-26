<?php

namespace Tiger;

use Exception;

/**
 * Class Menu (PHP version 7.4)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2022, rmsoft.be. (https://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     7.4.1.0
 * @package     Tiger
 */
class Menu
{
    private array $menuData = [];
    private array $bootstrapOptions = [
        'brand' => [
            'image' => [
                'link' => 'none',
                'size' => 30
            ],
            'name' => 'Name Website',
            'style' => [],
        ],
        'mobile' => false,
        'mobileSize' => 'md',
        'overview' => false,
        'theme' => [
            'fore' => 'light',
            'back' => '',
            'color' => ''
        ]
    ];

    /**
     * @param array $menuArray
     * @param string $menuText
     * @param string $menuUrl
     * @param string $menuPlacement
     * @param string $menuId
     * @param string $menuClass
     */
    public function add(
        array $menuArray,
        string $menuText,
        string $menuUrl,
        string $menuPlacement = 'left',
        string $menuId = '',
        string $menuClass = ''
    ): void {
        $menuOptions = $this->getIndexes($menuArray);
        $this->menuData[$menuOptions[0]][$menuOptions[1]][$menuOptions[2]][$menuOptions[3]][$menuOptions[4]][$menuOptions[5]][$menuOptions[6]][$menuOptions[7]][$menuOptions[8]][$menuOptions[9]]['url'] = $menuUrl;
        $this->menuData[$menuOptions[0]][$menuOptions[1]][$menuOptions[2]][$menuOptions[3]][$menuOptions[4]][$menuOptions[5]][$menuOptions[6]][$menuOptions[7]][$menuOptions[8]][$menuOptions[9]]['text'] = $menuText;
        $this->menuData[$menuOptions[0]][$menuOptions[1]][$menuOptions[2]][$menuOptions[3]][$menuOptions[4]][$menuOptions[5]][$menuOptions[6]][$menuOptions[7]][$menuOptions[8]][$menuOptions[9]]['place'] = $menuPlacement;
        $this->menuData[$menuOptions[0]][$menuOptions[1]][$menuOptions[2]][$menuOptions[3]][$menuOptions[4]][$menuOptions[5]][$menuOptions[6]][$menuOptions[7]][$menuOptions[8]][$menuOptions[9]]['id'] = $menuId;
        $this->menuData[$menuOptions[0]][$menuOptions[1]][$menuOptions[2]][$menuOptions[3]][$menuOptions[4]][$menuOptions[5]][$menuOptions[6]][$menuOptions[7]][$menuOptions[8]][$menuOptions[9]]['class'] = $menuClass;
    }

    /**
     * @param string $menuType
     * @param array $arrayMenu
     * @param array $args
     * @param string $id
     * @param string $class
     * @return string
     * @throws Exception
     */
    public function create(
        string $menuType = 'tiger',
        array $arrayMenu = [],
        array $args = [],
        string $id = '',
        string $class = ''
    ): string {
        switch ($menuType) {
            case 'tiger':
                $menu = new TigerMenu($this->menuData);
                break;
            case 'bootstrap':
                $menu = new BootstrapMenu($this->menuData, $this->bootstrapOptions);
                break;
            default:
                throw new Exception("Unknown menu type: {$menuType}");
        }
        /** @var object $menu */
        return $menu->createMenu($id, $class);
    }

    /**
     * @param int $pageNumber
     * @param int $items
     * @param int $itemsPerPage
     * @param string $pageUrl
     * @param string $menuSize
     * @return string
     */
    public function createPagination(
        int $pageNumber,
        int $items,
        int $itemsPerPage,
        string $pageUrl,
        string $menuSize = ''
    ): string {
        $numberOfPages = ceil($items / $itemsPerPage);

        if ($menuSize == 'lg') {
            $output = '<ul class="pagination pagination-lg">';
        } elseif ($menuSize == 'sm') {
            $output = '<ul class="pagination pagination-sm">';
        } else {
            $output = '<ul class="pagination">';
        }

        if ($numberOfPages == 1) {
            return '';
        } else {
            $class = ($pageNumber == 1) ? 'page-item active' : 'page-item';
            $output .= '<li class="' . $class . '"><a class="page-link" href="'
                . BASE_URL . $pageUrl . '/1">1</a></li>';

            if ($numberOfPages > 2) {
                if ($numberOfPages > 15) {
                    $class = ($pageNumber < 11) ? 'page-item disabled' : 'page-item';
                    $output .= '<li class="' . $class . '"><a class="page-link" href="'
                        . BASE_URL . $pageUrl . '/' . ($pageNumber - 10) . '">&laquo;</a></li>';
                }

                if ($numberOfPages > 5) {
                    $class = ($pageNumber == 1) ? 'page-item disabled' : 'page-item';
                    $output .= '<li class="' . $class . '"><a class="page-link" href="'
                        . BASE_URL . $pageUrl . '/' . ($pageNumber - 1) . '">&lsaquo;</a></li>';
                }

                if ($numberOfPages < 8) {
                    $xStart = 2;
                    $xStop = $numberOfPages;
                } elseif ($pageNumber < 5) {
                    $xStart = 2;
                    $xStop = 7;
                } elseif ($pageNumber > ($numberOfPages - 4)) {
                    $xStart = $numberOfPages - 5;
                    $xStop = $numberOfPages;
                } else {
                    $xStart = $pageNumber - 2;
                    $xStop = $pageNumber + 3;
                }
                for ($x = $xStart; $x < $xStop; $x++) {
                    $class = ($pageNumber == $x) ? 'page-item active' : 'page-item';
                    $output .= '<li class="' . $class . '"><a class="page-link" href="'
                        . BASE_URL . $pageUrl . '/' . $x . '">' . $x . '</a></li>';
                }

                if ($numberOfPages > 5) {
                    $class = ($pageNumber == $numberOfPages) ? 'page-item disabled' : 'page-item';
                    $output .= '<li class="' . $class . '"><a class="page-link" href="'
                        . BASE_URL . $pageUrl . '/' . ($pageNumber + 1) . '">&rsaquo;</a></li>';
                }

                if ($numberOfPages > 15) {
                    $class = ($pageNumber > $numberOfPages - 10) ? 'page-item disabled' : 'page-item';
                    $output .= '<li class="' . $class . '"><a class="page-link" href="'
                        . BASE_URL . $pageUrl . '/' . ($pageNumber + 10) . '">&raquo;</a></li>';
                }
            }

            $class = ($pageNumber == $numberOfPages) ? 'page-item active' : 'page-item';
            $output .= '<li class="' . $class . '"><a class="page-link" href="'
                . BASE_URL . $pageUrl . '/' . $numberOfPages . '">' . $numberOfPages . '</a></li>';
        }

        $output .= '</ul>';

        return $output;
    }

    /**
     * @param array $args
     * @return array
     */
    public static function getIndexes(array $args): array
    {
        $arguments = ['none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none', 'none'];
        for ($x = 0; $x < count($args); $x++) {
            if (isset($args[$x])) {
                $arguments[$x] = $args[$x];
            }
        }
        return $arguments;
    }

    /**
     * Set Bootstrap brand image
     *
     * @param string $link
     * @param int $size
     */
    public function setBootstrapBrandImage(string $link, int $size = 30): void
    {
        $this->bootstrapOptions['brand']['image']['link'] = $link;
        $this->bootstrapOptions['brand']['image']['size'] = $size;
    }

    /**
     * Set Bootstrap brand option
     *
     * @param string $name
     */
    public function setBootstrapBrandName(string $name): void
    {
        $this->bootstrapOptions['brand']['name'] = $name;
    }

    /**
     * Set Bootstrap Brand Style
     *
     * @param array $style
     */
    public function setBootstrapBrandNameStyle(array $style): void
    {
        $this->bootstrapOptions['brand']['style'] = $style;
    }

    /**
     * Set is mobile support option
     *
     * @param bool $active
     */
    public function setBootstrapMobile(bool $active): void
    {
        $this->bootstrapOptions['mobile'] = $active;
    }

    /**
     * Set the size when the mobile support has to kick in
     *
     * @param string $size
     */
    public function setBootstrapMobileSize(string $size): void
    {
        $this->bootstrapOptions['mobileSize'] = $size;
    }

    /**
     * Set Bootstrap overview option
     *
     * @param bool $overview
     */
    public function setBootstrapOverview(bool $overview): void
    {
        $this->bootstrapOptions['overview'] = $overview;
    }

    /**
     * Set Bootstrap navbar to light or dark
     *
     * @param string $theme
     */
    public function setBootstrapNavbarTheme(string $theme): void
    {
        $this->bootstrapOptions['theme']['fore'] = $theme;
    }

    /**
     * Set Bootstrap background color with bg- option
     *
     * @param string $background
     */
    public function setBootstrapBackgroundBg(string $background): void
    {
        $this->bootstrapOptions['theme']['back'] = $background;
    }

    /**
     * Set Bootstrap background color by style color
     *
     * @param string $HexColor
     */
    public function setBootstrapBackgroundColor(string $HexColor): void
    {
        $this->bootstrapOptions['theme']['color'] = $HexColor;
    }

    /**
     * Get all settings for Bootstrap plugin
     *
     * @return array
     */
    public function getBootstrapOptions(): array
    {
        return $this->bootstrapOptions;
    }
}
