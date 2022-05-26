<?php

namespace Tigra;

/**
 * Class BootstrapMenu (PHP version 7.4)
 *
 * @author      Rudy Mas <rudy.mas@rmsoft.be>
 * @copyright   2022, rmsoft.be. (https://www.rmsoft.be/)
 * @license     https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version     7.4.1.0
 * @package     Tigra
 */
class BootstrapMenu
{
    private array $menuData;

    private int $numberSubmenu = 0;
    private array $remove = ['None', 'none', 'NONE'];

    /**
     * @var array $options
     *
     * Following options can be set for bootstrap
     *  - brand: array
     *      - image: array
     *          - link: string      The Logo to be used before the name (Default: none)
     *          - size: int         The height for the img to be used (Default : 30)
     *      - name: string          The name of the website (Default: Name Website)
     *      - style: array          For adding CSS styling to the brand name
     *  - mobile: bool              Include support for mobile devices (Default: false)
     *  - mobileSize: string        Set the size when mobile support has to kick in (Default: md)
     *                                  sm ≥ 576px
     *                                  md ≥ 768px
     *                                  lg ≥ 992px
     *                                  xl ≥ 1200px
     *  - overview: bool            Add 'Overview' to the submenu. 'Overview' will link to the URL set for the Menu
     *                                  option (Default: false)
     *  - theme: array
     *      - fore: string          Setting foreground color by 'light' or 'dark' background
     *      - back: string          Set the background color by bg-...
     *      - color: strong         Set the background color by hexadecimal value
     */
    private array $options;

    /**
     * 0.0 BootstrapMenu constructor.
     *
     * @param array $menuData
     * @param array $options
     */
    public function __construct(array $menuData, array $options)
    {
        $this->menuData = $menuData;
        $this->options = $options;
    }

    /**
     * 1.0 Start of creating the bootstrap menu
     *
     * @param string $id
     * @param string $class
     * @return string
     */
    public function createMenu(string $id, string $class): string
    {
        $output = $this->addOpeningNav($id);
        if ($this->options['brand']['name'] !== '') {
            $output .= $this->addBranding();
        }
        if ($this->options['mobile']) {
            $output .= $this->addMobileButton();
        }
        $output .= '<div class="collapse navbar-collapse" id="bootstrapMenu">';
        $output .= $this->createLeftMenu();
        $output .= $this->createRightMenu();
        $output .= '</div>';
        $output .= '</nav>';
        return $output;
    }

    /**
     * 1.1 Creating opening tag navigation
     *
     * @param string $id
     * @return string
     */
    private function addOpeningNav(string $id): string
    {
        $output = '<nav ';
        if ($id !== '') {
            $output .= 'id="' . $id . '" ';
        }
        $output .= 'class="navbar navbar-' . $this->options['theme']['fore'];
        if (!empty($this->options['theme']['back'])) {
            $output .= " bg-" . $this->options['theme']['back'];
        }
        if ($this->options['mobile']) {
            $output .= ' navbar-expand-' . $this->options['mobileSize'];
        } else {
            $output .= ' navbar-expand';
        }
        if (!empty($this->options['theme']['color'])) {
            $output .= '" style="background-color: #' . $this->options['theme']['color'];
        }
        $output .= '">';
        return $output;
    }

    /**
     * 1.2 Adding the brand of the website to the menu
     *
     * @return string
     */
    private function addBranding(): string
    {
        $output = '<a class="navbar-brand" href="' . BASE_URL . '/">';
        if ($this->options['brand']['image']['link'] !== 'none') {
            $output .= '<img src="' . BASE_URL . $this->options['brand']['image']['link']
                . '" height="' . $this->options['brand']['image']['size'] . '" alt="Logo Website">';
        }
        $style = '';
        if (!empty($this->options['brand']['style'])) {
            foreach ($this->options['brand']['style'] as $key => $value) {
                $key = strtolower($key);
                if (is_string($value)) {
                    $value = strtolower($value);
                }
                $style .= $key . ':' . $value . ';';
            }
        }
        $output .= '<span style="' . $style . '">' . $this->options['brand']['name'] . '</span></a>';
        return $output;
    }

    /**
     * 1.3 Mobile button to be used in the menu
     *
     * @return string
     */
    private function addMobileButton(): string
    {
        $output = '<button class="navbar-toggler navbar-' . $this->options['theme']['fore'];
        $output .= '" type="button" data-toggle="collapse" data-target="#bootstrapMenu"';
        $output .= ' aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
        $output .= '<span class="navbar-toggler-icon"></span></button>';
        return $output;
    }

    /**
     * 2.0 Creating Bootstrap's Left Menu
     *
     * @return string
     */
    private function createLeftMenu(): string
    {
        $argsCount = 0;
        $arrayKey = $this->getArrayKey($this->menuData);
        $output = '<ul class="navbar navbar-nav mr-auto">';
        foreach ($arrayKey as $menuItem) {
            list($url, $text, $place, $id, $class) = $this->getLinkInformation([$menuItem]);
            if ($place == 'left') {
                $arguments[$argsCount] = $menuItem;
                $arraySubKey = $this->getArrayKey($this->menuData[$menuItem]);
                if (count($arraySubKey) === 0) {
                    $output .= '<li class="nav-item">';
                    $output .= $this->createNavLinkUrl($arguments);
                } else {
                    $output .= '<li class="nav-item dropdown">';
                    $output .= $this->createSubmenu($arguments, $this->menuData[$menuItem]);
                }
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }

    /**
     * 3.0 Creating Bootstrap's Right Menu
     *
     * @return string
     */
    private function createRightMenu(): string
    {
        $argsCount = 0;
        $arrayKey = $this->getArrayKey($this->menuData);
        $output = '<ul class="navbar navbar-nav ml-auto">';
        foreach ($arrayKey as $menuItem) {
            list($url, $text, $place, $id, $class) = $this->getLinkInformation([$menuItem]);
            if ($place == 'right') {
                $arguments[$argsCount] = $menuItem;
                $arraySubKey = $this->getArrayKey($this->menuData[$menuItem]);
                if (count($arraySubKey) === 0) {
                    $output .= '<li class="nav-item">';
                    $output .= $this->createNavLinkUrl($arguments);
                } else {
                    $output .= '<li class="nav-item dropdown">';
                    $output .= $this->createSubmenu($arguments, $this->menuData[$menuItem]);
                }
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }

    /**
     * 4.0 Create the drop down submenu
     *
     * @param array $arguments
     * @param array $subMenu
     * @return string
     */
    private function createSubmenu(array $arguments, array $subMenu): string
    {
        $argsCount = count($arguments);
        $arrayKey = $this->getArrayKey($subMenu);
        list($url, $text, $place, $id, $class) = $this->getLinkInformation($arguments);
        $output = '<a class="nav-link dropdown-toggle';
        $output = !empty($class) ? $output . ' ' . $class . '"' : $output . '"';
        if (!empty($id)) {
            $output .= ' id="' . $id . '"';
        }
        $output .= ' href="#" id="dropdown' . ++$this->numberSubmenu . '" role="button"';
        $output .= ' data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        $output .= $text . '</a>';
        $output .= '<ul class="dropdown-menu" aria-labelledby="dropdown' . $this->numberSubmenu . '">';
        if ($this->options['overview']) {
            $output .= $this->overviewUrl($url, $id, $class);
        }
        foreach ($arrayKey as $menuItem) {
            $arguments[$argsCount] = $menuItem;
            $arraySubKey = $this->getArrayKey($subMenu[$menuItem]);
            if (count($arraySubKey) === 0) {
                $output .= '<li>';
                $output .= $this->createDropDownItemUrl($arguments);
                $output .= '</li>';
            } else {
                $output .= '<li>';
                $output .= $this->createDropDownItemSubUrl($arguments);
                $output .= $this->createSubSubmenu($arguments, $subMenu[$menuItem]);
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }

    /**
     * 5.0 Create the drop down submenu from a submenu
     *
     * @param array $arguments
     * @param array $subMenu
     * @return string
     */
    private function createSubSubmenu(array $arguments, array $subMenu): string
    {
        $argsCount = count($arguments);
        $arrayKey = $this->getArrayKey($subMenu);
        list($url, $text, $place, $id, $class) = $this->getLinkInformation($arguments);
        $output = '<ul class="dropdown-menu">';
        if ($this->options['overview']) {
            $output .= $this->overviewUrl($url, $id, $class);
        }
        foreach ($arrayKey as $menuItem) {
            $arguments[$argsCount] = $menuItem;
            $arraySubKey = $this->getArrayKey($subMenu[$menuItem]);
            if (count($arraySubKey) === 0) {
                $output .= '<li>';
                $output .= $this->createDropDownItemUrl($arguments);
                $output .= '</li>';
            } else {
                $output .= '<li>';
                $output .= $this->createDropDownItemSubUrl($arguments);
                $output .= $this->createSubSubmenu($arguments, $subMenu[$menuItem]);
                $output .= '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }

    /**
     * G.0 Create the URL for the nav-link
     *
     * @param array $args
     * @return string
     */
    private function createNavLinkUrl(array $args): string
    {
        list($url, $text, $place, $id, $class) = $this->getLinkInformation($args);
        $output = '<a class="nav-link';
        $output = !empty($class) ? $output . ' ' . $class . '"' : $output . '"';
        if (!empty($id)) {
            $output .= ' id="' . $id . '"';
        }
        $output .= ' href="' . BASE_URL . $url . '">' . $text . '</a>';
        return $output;
    }

    /**
     * G.1 Create the URL for the dropdown-item
     *
     * @param array $args
     * @return string
     */
    private function createDropDownItemUrl(array $args): string
    {
        list($url, $text, $place, $id, $class) = $this->getLinkInformation($args);
        $output = '<a class="dropdown-item';
        $output = !empty($class) ? $output . ' ' . $class . '"' : $output . '"';
        if (!empty($id)) {
            $output .= ' id="' . $id . '"';
        }
        $output .= ' href="' . BASE_URL . $url . '">' . $text . '</a>';
        return $output;
    }

    /**
     * G.2 Create the URL for the dropdown-item
     *
     * @param array $args
     * @return string
     */
    private function createDropDownItemSubUrl(array $args): string
    {
        list($url, $text, $place, $id, $class) = $this->getLinkInformation($args);
        $output = '<a class="dropdown-item dropdown-toggle';
        $output = !empty($class) ? $output . ' ' . $class . '"' : $output . '"';
        if (!empty($id)) {
            $output .= ' id="' . $id . '"';
        }
        $output .= ' href="' . BASE_URL . $url . '">' . $text . '</a>';
        return $output;
    }

    /**
     * G.3 Get the menu information
     *
     * @param array $args
     * @return array
     */
    private function getLinkInformation(array $args): array
    {
        $arg = Menu::getIndexes($args);
        $url = $this->menuData[$arg[0]][$arg[1]][$arg[2]][$arg[3]][$arg[4]][$arg[5]][$arg[6]][$arg[7]][$arg[8]][$arg[9]]['url'];
        $text = $this->menuData[$arg[0]][$arg[1]][$arg[2]][$arg[3]][$arg[4]][$arg[5]][$arg[6]][$arg[7]][$arg[8]][$arg[9]]['text'];
        $place = $this->menuData[$arg[0]][$arg[1]][$arg[2]][$arg[3]][$arg[4]][$arg[5]][$arg[6]][$arg[7]][$arg[8]][$arg[9]]['place'];
        $id = $this->menuData[$arg[0]][$arg[1]][$arg[2]][$arg[3]][$arg[4]][$arg[5]][$arg[6]][$arg[7]][$arg[8]][$arg[9]]['id'];
        $class = $this->menuData[$arg[0]][$arg[1]][$arg[2]][$arg[3]][$arg[4]][$arg[5]][$arg[6]][$arg[7]][$arg[8]][$arg[9]]['class'];
        return [$url, $text, $place, $id, $class];
    }

    /**
     * G.4 Get the keys of the current level in the menu
     *
     * @param array $menuData
     * @return array
     */
    private function getArrayKey(array $menuData): array
    {
        $arrayKey = array_keys($menuData);
        $arrayKey = array_diff($arrayKey, $this->remove);
        $arrayKey = array_values($arrayKey);
        return $arrayKey;
    }

    /**
     * G.5 Add Overview Menu option
     *
     * @param string $url
     * @param string $id
     * @param string $class
     * @return string
     */
    private function overviewUrl(string $url, string $id, string $class): string
    {
        $output = '<li>';
        $output .= '<a class="dropdown-item';
        $output = !empty($class) ? $output . ' ' . $class . '"' : $output . '"';
        if (!empty($id)) {
            $output .= ' id="' . $id . '"';
        }
        $output .= ' href="' . $url . '">Overview</a>';
        $output .= '</li>';
        return $output;
    }
}
